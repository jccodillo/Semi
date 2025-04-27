<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\SuppliesInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    /**
     * Display a listing of the procurements.
     */
    public function index()
    {
        $procurements = Procurement::with('creator')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);
        
        return view('admin.procurement.index', compact('procurements'));
    }

    /**
     * Show the form for creating a new procurement.
     */
    public function create()
    {
        $units = [
            'Box', 'Piece', 'Pack', 'Ream', 'Roll', 'Bottle', 
            'Cartridges', 'Gallon', 'Litre', 'Meter', 'Pound', 'Sheet'
        ];
        
        // Generate a new IAR number
        $iarNo = Procurement::generateIarNo();
        
        return view('admin.procurement.create', compact('units', 'iarNo'));
    }

    /**
     * Store a newly created procurement in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'iar_no' => 'required|string|unique:procurements,iar_no',
            'supplier' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.stock_no' => 'required|string',
            'items.*.product_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_type' => 'required|string',
            'items.*.price_per_unit' => 'required|numeric|min:0.01',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Calculate the total amount
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['price_per_unit'];
            }
            
            // Create the procurement record
            $procurement = Procurement::create([
                'iar_no' => $request->iar_no,
                'supplier' => $request->supplier,
                'created_by' => Auth::id(),
                'total_amount' => $totalAmount,
            ]);
            
            // Create items and update inventory
            foreach ($request->items as $item) {
                // Create the procurement item
                ProcurementItem::create([
                    'procurement_id' => $procurement->id,
                    'stock_no' => $item['stock_no'],
                    'product_name' => $item['product_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_type' => $item['unit_type'],
                    'price_per_unit' => $item['price_per_unit'],
                    'total_amount' => $item['quantity'] * $item['price_per_unit']
                ]);
                
                // First check if inventory exists with the same stock number
                $existingInventory = SuppliesInventory::where('control_code', $item['stock_no'])->first();
                
                if ($existingInventory) {
                    // If exists with same stock number, check if product name or unit type is different
                    if ($existingInventory->product_name != $item['product_name'] || 
                        $existingInventory->unit_type != $item['unit_type']) {
                        // Log the conflict and update the existing record
                        \Log::warning('Stock number conflict in procurement: ' . $item['stock_no'] . 
                                     ' already exists with name: ' . $existingInventory->product_name . 
                                     ' and unit: ' . $existingInventory->unit_type);
                        
                        // Update the existing inventory record to match the current procurement
                        $existingInventory->product_name = $item['product_name'];
                        $existingInventory->unit_type = $item['unit_type'];
                        $existingInventory->quantity += $item['quantity'];
                        // Update the description field as well
                        if (!empty($item['description'])) {
                            $existingInventory->description = $item['description'];
                        }
                        $existingInventory->save();
                    } else {
                        // Same product, just update quantity
                        $existingInventory->quantity += $item['quantity'];
                        // Update the description field if provided
                        if (!empty($item['description']) && empty($existingInventory->description)) {
                            $existingInventory->description = $item['description'];
                        }
                        $existingInventory->save();
                    }
                } else {
                    // No item with this stock number exists, check if same product name and unit type exists
                    $inventoryByName = SuppliesInventory::where('product_name', $item['product_name'])
                                                      ->where('unit_type', $item['unit_type'])
                                                      ->first();
                    
                    if ($inventoryByName) {
                        // Product exists but with different stock number - update stock number and quantity
                        \Log::info('Updating control code for ' . $item['product_name'] . 
                                  ' from ' . $inventoryByName->control_code . 
                                  ' to ' . $item['stock_no']);
                        
                        $inventoryByName->control_code = $item['stock_no'];
                        $inventoryByName->quantity += $item['quantity'];
                        // Update the description field if provided
                        if (!empty($item['description']) && empty($inventoryByName->description)) {
                            $inventoryByName->description = $item['description'];
                        }
                        $inventoryByName->save();
                    } else {
                        // Completely new item
                        SuppliesInventory::create([
                            'control_code' => $item['stock_no'],
                            'product_name' => $item['product_name'],
                            'quantity' => $item['quantity'],
                            'unit_type' => $item['unit_type'],
                            'description' => $item['description'] ?? null
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.procurement.index')
                            ->with('success', 'Procurement created successfully with IAR No: ' . $request->iar_no);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                        ->with('error', 'Error creating procurement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified procurement.
     */
    public function show($id)
    {
        $procurement = Procurement::with(['items', 'creator'])->findOrFail($id);
        
        return view('admin.procurement.show', compact('procurement'));
    }

    /**
     * Generate and download the IAR report for a procurement
     */
    public function generateIAR($id)
    {
        $procurement = Procurement::with(['items', 'creator'])->findOrFail($id);
        
        // Load the template
        $templatePath = storage_path('app/public/IAR FILE/TUPM-SUP-INSPECTION-AND-ACCEPTANCE-REPORT.docx');
        
        // Check if template exists
        if (!file_exists($templatePath)) {
            return back()->with('error', 'IAR template file not found at: ' . $templatePath);
        }
        
        // Generate a temporary file
        $tempFile = storage_path('app/temp_iar_' . time() . '.docx');
        
        // Create directory if needed
        $tempDir = dirname($tempFile);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        try {
            // Just directly copy the template file first
            if (!copy($templatePath, $tempFile)) {
                return back()->with('error', 'Failed to copy template file');
            }
            
            // Use PHP's native ZIP handling to modify the document XML directly
            // This bypasses PHPWord library issues
            $zip = new \ZipArchive();
            
            if ($zip->open($tempFile) !== true) {
                return back()->with('error', 'Failed to open the document for editing');
            }
            
            // Try to disable protected view by adding/modifying content types
            // Add content type override if it doesn't exist
            if ($zip->locateName('[Content_Types].xml') !== false) {
                $contentTypesXml = $zip->getFromName('[Content_Types].xml');
                // Add a Trust flag if possible
                if (strpos($contentTypesXml, 'TrustLevel="0"') !== false) {
                    $contentTypesXml = str_replace('TrustLevel="0"', 'TrustLevel="1"', $contentTypesXml);
                    $zip->deleteName('[Content_Types].xml');
                    $zip->addFromString('[Content_Types].xml', $contentTypesXml);
                }
            }
            
            // Also modify the document properties to mark it as trusted
            if ($zip->locateName('docProps/core.xml') !== false) {
                $coreXml = $zip->getFromName('docProps/core.xml');
                if ($coreXml) {
                    // Add some properties that might help
                    if (strpos($coreXml, '</cp:coreProperties>') !== false) {
                        $newProps = '<dc:creator>TUPM System</dc:creator>';
                        $coreXml = str_replace('</cp:coreProperties>', $newProps . '</cp:coreProperties>', $coreXml);
                        $zip->deleteName('docProps/core.xml');
                        $zip->addFromString('docProps/core.xml', $coreXml);
                    }
                }
            }
            
            // Read the main document content
            $content = $zip->getFromName('word/document.xml');
            if ($content === false) {
                $zip->close();
                return back()->with('error', 'Failed to read document content');
            }
            
            // Simple string replacements for all potential variables
            $replacements = [
                '${IAR_NO}' => $procurement->iar_no,
                '${SUPPLIER}' => $procurement->supplier,
                '${DATE}' => $procurement->created_at->format('m/d/Y'),
                '${INV_NO}' => 'N/A',
                '${INV_DATE}' => $procurement->created_at->format('m/d/Y'),
                '${PO_NO}' => 'N/A',
                
                // Also try without ${}
                'IAR_NO' => $procurement->iar_no,
                'SUPPLIER' => $procurement->supplier,
                'DATE' => $procurement->created_at->format('m/d/Y'),
                
                // Try with spaces
                'IAR No.' => $procurement->iar_no,
                'Supplier:' => $procurement->supplier,
                'Date:' => $procurement->created_at->format('m/d/Y'),
                
                // Also try direct substitutions in text that might be in the document
                '${IAR No.}' => $procurement->iar_no,
                '${Supplier}' => $procurement->supplier,
                '${PO/JO No.}' => 'N/A',
            ];
            
            // Apply replacements to document XML
            foreach ($replacements as $search => $replace) {
                // XML-encode the replacement values
                $replace = htmlspecialchars($replace, ENT_XML1, 'UTF-8');
                // Look for the search term inside XML text tags
                $content = str_replace('>' . $search . '<', '>' . $replace . '<', $content);
                // Also try with search term as a separate XML element
                $content = str_replace('<w:t>' . $search . '</w:t>', '<w:t>' . $replace . '</w:t>', $content);
            }
            
            // SIMPLIFY: Create a completely new table with items from scratch
            if (strpos($content, 'Stock No.') !== false) {
                \Log::info('Using the direct table replacement approach');
                
                // Create a string with all items in proper Word XML format
                $itemRows = '';
                foreach ($procurement->items as $item) {
                    // Calculate total amount for this item
                    $totalAmount = $item->price_per_unit * $item->quantity;
                    
                    // Create a very explicit, well-formed row with all formatting
                    $itemRows .= '
                    <w:tr w:rsidR="00000000" w:rsidTr="00000000">
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="1500" w:type="dxa"/>
                                <w:tcBorders>
                                    <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                </w:tcBorders>
                            </w:tcPr>
                            <w:p w:rsidR="00000000" w:rsidRDefault="00000000">
                                <w:pPr><w:jc w:val="left"/></w:pPr>
                                <w:r><w:rPr><w:sz w:val="20"/></w:rPr><w:t>' . htmlspecialchars($item->stock_no, ENT_XML1, 'UTF-8') . '</w:t></w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="3000" w:type="dxa"/>
                                <w:tcBorders>
                                    <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                </w:tcBorders>
                            </w:tcPr>
                            <w:p w:rsidR="00000000" w:rsidRDefault="00000000">
                                <w:pPr><w:jc w:val="left"/></w:pPr>
                                <w:r><w:rPr><w:sz w:val="20"/></w:rPr><w:t>' . htmlspecialchars($item->product_name . ($item->description ? ' ( ' . $item->description . ' )' : ''), ENT_XML1, 'UTF-8') . '</w:t></w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="1500" w:type="dxa"/>
                                <w:tcBorders>
                                    <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                </w:tcBorders>
                            </w:tcPr>
                            <w:p w:rsidR="00000000" w:rsidRDefault="00000000">
                                <w:pPr><w:jc w:val="right"/></w:pPr>
                                <w:r><w:rPr><w:sz w:val="20"/></w:rPr><w:t>₱' . htmlspecialchars(number_format($totalAmount, 2), ENT_XML1, 'UTF-8') . '</w:t></w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="1500" w:type="dxa"/>
                                <w:tcBorders>
                                    <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                </w:tcBorders>
                            </w:tcPr>
                            <w:p w:rsidR="00000000" w:rsidRDefault="00000000">
                                <w:pPr><w:jc w:val="center"/></w:pPr>
                                <w:r><w:rPr><w:sz w:val="20"/></w:rPr><w:t>' . htmlspecialchars($item->unit_type, ENT_XML1, 'UTF-8') . '</w:t></w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="1500" w:type="dxa"/>
                                <w:tcBorders>
                                    <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                </w:tcBorders>
                            </w:tcPr>
                            <w:p w:rsidR="00000000" w:rsidRDefault="00000000">
                                <w:pPr><w:jc w:val="center"/></w:pPr>
                                <w:r><w:rPr><w:sz w:val="20"/></w:rPr><w:t>' . htmlspecialchars((string)$item->quantity, ENT_XML1, 'UTF-8') . '</w:t></w:r>
                            </w:p>
                        </w:tc>
                    </w:tr>';
                }
                
                // Find the items table by looking for a table with the Stock No. header
                $tablePattern = '/<w:tbl>.*?Stock No\..*?<\/w:tbl>/s';
                if (preg_match($tablePattern, $content, $matches)) {
                    $table = $matches[0];
                    
                    // Find the header row
                    $headerRowPattern = '/<w:tr>.*?Stock No\..*?<\/w:tr>/s';
                    if (preg_match($headerRowPattern, $table, $headerMatches)) {
                        $headerRow = $headerMatches[0];
                        
                        // Find the Description cell
                        $descCellPattern = '/<w:tc>.*?Description.*?<\/w:tc>/s';
                        if (preg_match($descCellPattern, $headerRow, $descCellMatches)) {
                            $descCell = $descCellMatches[0];
                            
                            // Create a Total Amount cell with same formatting
                            $totalAmountCell = str_replace('Description', 'Total Amount', $descCell);
                            
                            // Insert it after the Description cell
                            $newHeaderRow = str_replace($descCell, $descCell . $totalAmountCell, $headerRow);
                            
                            // Replace the header row in the table
                            $table = str_replace($headerRow, $newHeaderRow, $table);
                        }
                    }
                    
                    // Find the header row end
                    $headerEnd = strpos($table, '</w:tr>') + 7; // +7 for '</w:tr>'
                    
                    // Create new table with just the header + our item rows
                    $newTable = substr($table, 0, $headerEnd) . $itemRows . '</w:tbl>';
                    
                    // Replace the old table with our new one
                    $content = str_replace($table, $newTable, $content);
                } else {
                    // Fallback: try to find and modify any table with "Description" header
                    $tablePattern = '/<w:tbl>.*?Description.*?<\/w:tbl>/s';
                    if (preg_match($tablePattern, $content, $matches)) {
                        $table = $matches[0];
                        
                        // Find the header row
                        $headerRowPattern = '/<w:tr>.*?Description.*?<\/w:tr>/s';
                        if (preg_match($headerRowPattern, $table, $headerMatches)) {
                            $headerRow = $headerMatches[0];
                            
                            // Find the Description cell
                            $descCellPattern = '/<w:tc>.*?Description.*?<\/w:tc>/s';
                            if (preg_match($descCellPattern, $headerRow, $descCellMatches)) {
                                $descCell = $descCellMatches[0];
                                
                                // Create a Total Amount cell with same formatting
                                $totalAmountCell = str_replace('Description', 'Total Amount', $descCell);
                                
                                // Insert it after the Description cell
                                $newHeaderRow = str_replace($descCell, $descCell . $totalAmountCell, $headerRow);
                                
                                // Replace the header row in the table
                                $table = str_replace($headerRow, $newHeaderRow, $table);
                            }
                        }
                        
                        // Find the header row end
                        $headerEnd = strpos($table, '</w:tr>') + 7; // +7 for '</w:tr>'
                        
                        // Create new table with just the header + our item rows
                        $newTable = substr($table, 0, $headerEnd) . $itemRows . '</w:tbl>';
                        
                        // Replace the old table with our new one
                        $content = str_replace($table, $newTable, $content);
                    }
                }
            }
            
            // Add a visible comment in the document about editing
            $content = str_replace('</w:body>', '<w:p><w:r><w:t>Items updated on: ' . date('Y-m-d H:i:s') . '</w:t></w:r></w:p></w:body>', $content);
            
            // Put the modified content back
            $zip->deleteName('word/document.xml');
            $zip->addFromString('word/document.xml', $content);
            $zip->close();
            
            // Return the file as a download
            return response()->download($tempFile, 'IAR_' . $procurement->iar_no . '.docx')->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            \Log::error('Failed to generate IAR: ' . $e->getMessage() . ' at line ' . $e->getLine());
            
            // Fall back to just returning the original template if all else fails
            return response()->download($templatePath, 'IAR_' . $procurement->iar_no . '.docx');
        }
    }
    
    /**
     * Helper method to safely set a value in the template
     */
    private function setValueSafely($templateProcessor, $key, $value)
    {
        try {
            $templateProcessor->setValue($key, $value);
        } catch (\Exception $e) {
            // If the variable doesn't exist in the template, just log it
            \Log::info('Template variable not found: ' . $key);
        }
    }

    /**
     * Convert number to words for the IAR report
     */
    private function numberToWords($number)
    {
        // Use a custom number to words conversion as NumberFormatter is not available
        $words = $this->convertNumberToWords($number);
        return ucfirst($words) . ' Pesos Only';
    }
    
    /**
     * Custom implementation of number to words conversion
     */
    private function convertNumberToWords($number) 
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );
        
        if (!is_numeric($number)) {
            return false;
        }
        
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        
        if ($number < 0) {
            return $negative . $this->convertNumberToWords(abs($number));
        }
        
        $string = $fraction = null;
        
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[(int) $hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convertNumberToWords($remainder);
                }
                break;
        }
        
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        
        return $string;
    }

    /**
     * Create a basic IAR template if none exists
     */
    private function createBasicIARTemplate($templatePath)
    {
        // Create directory if it doesn't exist
        $directory = dirname($templatePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Create new PHPWord instance
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Add styles
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 14, 'allCaps' => true]);
        $headerStyle = ['bold' => true, 'size' => 11];
        $normalStyle = ['size' => 10];
        $tableHeaderStyle = ['bold' => true, 'size' => 10];
        $cellStyle = ['valign' => 'center'];
        $cellHeaderStyle = ['valign' => 'center', 'bgColor' => 'DDDDDD'];
        
        // Create the document
        $section = $phpWord->addSection(['marginTop' => 800, 'marginRight' => 800, 'marginBottom' => 800, 'marginLeft' => 800]);
        
        // Header with TUPM logo and info
        $headerTable = $section->addTable(['borderSize' => 1, 'borderColor' => '000000', 'width' => 100, 'unit' => 'pct']);
        
        // First row with logo and university name
        $headerTable->addRow();
        $logoCell = $headerTable->addCell(2000, ['vMerge' => 'restart', 'valign' => 'center']);
        // We can't add an image here so just add text as placeholder
        $logoCell->addText('TUPM LOGO', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $universityCell = $headerTable->addCell(6000, $cellStyle);
        $universityCell->addText('TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES', ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $universityCell->addText('Ayala Blvd, Ermita, Manila, 1000, Philippines | Tel No. +632-5301-3001 local 124', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $universityCell->addText('Fax No. +632-5321-0051 | Email: supply@tup.edu.ph | Website: www.tup.edu.ph', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $indexCell = $headerTable->addCell(2000, $cellStyle);
        $indexTable = $indexCell->addTable(['borderSize' => 1, 'borderColor' => '000000', 'width' => 100, 'unit' => 'pct']);
        $indexTable->addRow();
        $indexTable->addCell(1000, $cellStyle)->addText('Index No', ['size' => 8, 'bold' => true]);
        $indexTable->addCell(1000, $cellStyle)->addText('F-SUP-8.9-AR', ['size' => 8]);
        
        $indexTable->addRow();
        $indexTable->addCell(1000, $cellStyle)->addText('Issue No.', ['size' => 8, 'bold' => true]);
        $indexTable->addCell(1000, $cellStyle)->addText('01', ['size' => 8]);
        
        $indexTable->addRow();
        $indexTable->addCell(1000, $cellStyle)->addText('Revision No', ['size' => 8, 'bold' => true]);
        $indexTable->addCell(1000, $cellStyle)->addText('01', ['size' => 8]);
        
        // Second header row for report title
        $headerTable->addRow();
        $headerTable->addCell(null, ['vMerge' => 'continue']);
        $titleCell = $headerTable->addCell(6000, $cellStyle);
        $titleCell->addText('INSPECTION AND ACCEPTANCE REPORT', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $dateTable = $headerTable->addCell(2000, $cellStyle)->addTable(['borderSize' => 1, 'borderColor' => '000000', 'width' => 100, 'unit' => 'pct']);
        $dateTable->addRow();
        $dateTable->addCell(1000, $cellStyle)->addText('Date', ['size' => 8, 'bold' => true]);
        $dateTable->addCell(1000, $cellStyle)->addText('${DATE}', ['size' => 8]);
        
        $dateTable->addRow();
        $dateTable->addCell(1000, $cellStyle)->addText('Page', ['size' => 8, 'bold' => true]);
        $dateTable->addCell(1000, $cellStyle)->addText('1/1', ['size' => 8]);
        
        $dateTable->addRow();
        $dateTable->addCell(1000, $cellStyle)->addText('QAC No', ['size' => 8, 'bold' => true]);
        $dateTable->addCell(1000, $cellStyle)->addText('QC-07132018', ['size' => 8]);
        
        // Third header row with form code
        $headerTable->addRow();
        $headerTable->addCell(null, ['vMerge' => 'continue']);
        $codeCell = $headerTable->addCell(6000, $cellStyle);
        $codeCell->addText('VAF-SUP', ['size' => 9], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
        $headerTable->addCell(2000, $cellStyle);
        
        $section->addTextBreak(1);
        
        // Procurement details table
        $procTable = $section->addTable(['borderSize' => 1, 'borderColor' => '000000', 'width' => 100, 'unit' => 'pct']);
        $procTable->addRow();
        
        $leftCell = $procTable->addCell(5000, $cellStyle);
        $leftCell->addText('Supplier: ${SUPPLIER}', $normalStyle);
        $leftCell->addText('PO/JO No.: ${PO_NO}', $normalStyle);
        $leftCell->addText('Requisitioning Office/Dept.: SUPPLY OFFICE (FOR ISSUANCE)', $normalStyle);
        $leftCell->addText('Responsibility Center Code', $normalStyle);
        
        $rightCell = $procTable->addCell(5000, $cellStyle);
        $rightCell->addText('IAR No.: ${IAR_NO}', $normalStyle);
        $rightCell->addText('INV. No.: ${INV_NO}', $normalStyle);
        $rightCell->addText('INV. Date: ${INV_DATE}', $normalStyle);
        
        $section->addTextBreak(1);
        
        // Items table
        $itemsTable = $section->addTable(['borderSize' => 1, 'borderColor' => '000000', 'width' => 100, 'unit' => 'pct']);
        
        // Table header
        $itemsTable->addRow();
        $itemsTable->addCell(1500, $cellHeaderStyle)->addText('Stock No.', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $itemsTable->addCell(4000, $cellHeaderStyle)->addText('Description', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $itemsTable->addCell(1500, $cellHeaderStyle)->addText('Unit', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $itemsTable->addCell(1500, $cellHeaderStyle)->addText('Quantity', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        // Sample row for cloning (with placeholders)
        $itemsTable->addRow();
        $itemsTable->addCell(1500, $cellStyle)->addText('${Stock_No}', $normalStyle);
        $itemsTable->addCell(4000, $cellStyle)->addText('${Description}', $normalStyle);
        $itemsTable->addCell(1500, $cellStyle)->addText('${Unit}', $normalStyle);
        $itemsTable->addCell(1500, $cellStyle)->addText('${Quantity}', $normalStyle);
        
        // Add empty rows
        for ($i = 0; $i < 10; $i++) {
            $itemsTable->addRow();
            $itemsTable->addCell(1500, $cellStyle)->addText('', $normalStyle);
            $itemsTable->addCell(4000, $cellStyle)->addText('', $normalStyle);
            $itemsTable->addCell(1500, $cellStyle)->addText('', $normalStyle);
            $itemsTable->addCell(1500, $cellStyle)->addText('', $normalStyle);
        }
        
        $section->addTextBreak(2);
        
        // Signature section
        $signatureTable = $section->addTable(['width' => 100, 'unit' => 'pct']);
        $signatureTable->addRow();
        $signatureTable->addCell(5000)->addText('Inspection', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $signatureTable->addCell(5000)->addText('Acceptance', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $signatureTable->addRow(1000);
        $signatureTable->addCell(5000, ['valign' => 'bottom'])->addText('Date Inspected: __________________', $normalStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $signatureTable->addCell(5000, ['valign' => 'bottom'])->addText('Date Accepted: __________________', $normalStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $signatureTable->addRow();
        $signatureTable->addCell(5000)->addText('${CREATED_BY}', $normalStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $signatureTable->addCell(5000)->addText('Supply Officer', $normalStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        // Save the document
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word');
        $objWriter->save($templatePath);
    }
}
