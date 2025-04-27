@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Generate Reports</h6>
            </div>
            <div class="card-body pt-4 p-3">
            <div class="row mb-4">
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="department" class="form-control-label">Department</label>
                                <select name="department" id="department" class="form-control" required>
                                    <option value="">Select Department</option>
                            <option value="INTEGRATED RESEARCH AND TRAINING CENTER">Integrated Research and Training Center</option>
                            <option value="COLLEGE OF ENGINEERING">College of Engineering</option>
                            <option value="COLLEGE OF ARCHITECTURE AND FINE ARTS">College of Architecture and Fine Arts</option>
                            <option value="COLLEGE OF INDUSTRIAL EDUCATION">College of Industrial Education</option>
                            <option value="COLLEGE OF INDUSTRIAL TECHNOLOGY">College of Industrial Technology</option>
                            <option value="COLLEGE OF LIBERAL ARTS">College of Liberal Arts</option>
                            <option value="COLLEGE OF SCIENCE">College of Science</option>
                                </select>
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                        <label for="filterType" class="form-control-label">Filter By</label>
                        <select name="filterType" id="filterType" class="form-control" required>
                            <option value="week">Weekly</option>
                            <option value="month" selected>Monthly</option>
                            <option value="year">Yearly</option>
                                </select>
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date" class="form-control-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date" class="form-control-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                <button type="button" id="generateReportBtn" class="btn btn-primary me-2" style="background-color: #821131;">
                    <i class="fas fa-file-pdf me-2"></i>GENERATE REPORT
                </button>
                <button type="button" id="printReportBtn" class="btn btn-secondary" style="display: none;" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>PRINT REPORT
                        </button>
            </div>

            <!-- Report Preview -->
            <div id="reportPreview" class="mt-4" style="display: none;">
                <!-- Page Header -->
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                    <tr>
                        <td style="width: 15%; vertical-align: top; border-right: 1px solid #000;">
                            <img src="{{ asset('assets/img/TUP.logo.png') }}" alt="TUP Logo" style="height: 60px; width: auto; display: block;">
                        </td>
                        <td style="width: 60%; text-align: center; vertical-align: top; border-right: 1px solid #000; padding: 2px;">
                            <h4 style="font-size: 14px; font-weight: bold; margin: 0 0 1px 0;">TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h4>
                            <p style="font-size: 12px; margin: 0 0 1px 0;">Ayala Blvd., Ermita, Manila, 1000, Philippines</p>
                            <p style="font-size: 12px; margin: 0 0 1px 0;">Tel No. +632-301-3001 local 124| Fax No. +632-521-4063</p>
                            <p style="font-size: 12px; margin: 0;">Email: supply@tup.edu.ph | Website: www.tup.edu.ph</p>
                        </td>
                        <td style="width: 25%; vertical-align: top;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                <tr>
                                    <td style="border-bottom: 1px solid #000; padding: 1px 4px; width: 35%;">Index No.</td>
                                    <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 1px 4px;">F-SUP-8.9-RIS</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #000; padding: 1px 4px;">Issue No.</td>
                                    <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 1px 4px;">01</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #000; padding: 1px 4px;">Revision No.</td>
                                    <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 1px 4px;">00</td>
                                </tr>
                                <tr>
                                    <td style="padding: 1px 4px;">Date</td>
                                    <td style="border-left: 1px solid #000; padding: 1px 4px;" id="headerDate"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #000; border-top: 1px solid #000; padding: 2px 5px; font-size: 12px;">
                            VAF-SUP
                        </td>
                        <td style="text-align: center; border-right: 1px solid #000; border-top: 1px solid #000; padding: 2px;">
                            <span style="font-size: 14px; font-weight: bold;">REQUISITION AND ISSUE SLIP</span>
                        </td>
                        <td style="border-top: 1px solid #000;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                <tr>
                                    <td style="border-bottom: 1px solid #000; padding: 1px 4px; width: 35%;">Page</td>
                                    <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 1px 4px;">1/1</td>
                                </tr>
                                <tr>
                                    <td style="padding: 1px 4px;">QAC No.</td>
                                    <td style="border-left: 1px solid #000; padding: 1px 4px;">CC-11242017</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Items Header Section -->
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin-top: 5px; font-size: 12px;">
                    <tr>
                        <td style="width: 75%; border: 1px solid #000; padding: 2px 4px;">Division</td>
                        <td style="width: 25%; border: 1px solid #000; padding: 2px 4px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>Responsibility Center Code:</span>
                                <span style="font-weight: bold;">IRTC</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px 4px;">
                            Office <span style="font-weight: bold;">INTEGRATED RESEARCH AND TRAINING CENTER</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 2px 4px;">
                            <div>
                                <div>RIS No. <span style="font-weight: bold;">24-09-1703</span></div>
                                <div>SAI No.</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px 4px; text-align: center; font-style: italic;">
                            R e q u i s i t i o n
                        </td>
                        <td style="border: 1px solid #000; padding: 2px 4px;">
                            <div>
                                <div>IAR No.: <span style="font-weight: bold;">24-08-0185</span></div>
                                <div style="font-style: italic;">I s s u a n c e</div>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Items Table -->
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin-top: -1px; font-size: 12px;">
                    <tr>
                        <th style="border: 1px solid #000; padding: 2px 4px; width: 15%; text-align: left; font-weight: bold; font-size: 13px;">Stock No.</th>
                        <th style="border: 1px solid #000; padding: 2px 4px; width: 10%; text-align: left; font-weight: bold; font-size: 13px;">Unit</th>
                        <th style="border: 1px solid #000; padding: 2px 4px; width: 45%; text-align: left; font-weight: bold; font-size: 13px;">Description</th>
                        <th style="border: 1px solid #000; padding: 2px 4px; width: 10%; text-align: left; font-weight: bold; font-size: 13px;">Quantity</th>
                        <th style="border: 1px solid #000; padding: 2px 4px; width: 10%; text-align: left; font-weight: bold; font-size: 13px;">Quantity</th>
                        <th style="border: 1px solid #000; padding: 2px 4px; width: 10%; text-align: left; font-weight: bold; font-size: 13px;">Remarks</th>
                    </tr>
                    <tbody id="reportItems">
                        <!-- Items will be populated here by JavaScript -->
                    </tbody>
                </table>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <p><strong>Purpose:</strong> <span id="reportPurpose">For office use</span></p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <div style="flex: 1;">
                                <p style="margin-bottom: 50px;">Requested by:</p>
                                <div class="signature-line"></div>
                            </div>
                            <div style="flex: 1;">
                                <p style="margin-bottom: 50px;">Approved by:</p>
                                <div class="signature-line"></div>
                            </div>
                            <div style="flex: 1;">
                                <p style="margin-bottom: 50px;">Issued by:</p>
                                <div class="signature-line"></div>
                            </div>
                            <div style="flex: 1;">
                                <p style="margin-bottom: 50px;">Received by:</p>
                                <div class="signature-line"></div>
                            </div>
            </div>
        </div>
    </div>
</div>

            <!-- Hidden Print Layout -->
            <div id="printLayout" style="display: none;">
                <div class="print-only">
                    <div class="report-header">
                        <img src="{{ asset('assets/img/TUP.logo.png') }}" alt="TUP Logo" class="logo">
                        <h4>TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h4>
                        <p>Ayala Blvd., Ermita, Manila, 1000, Philippines</p>
                        <p>Tel No. +632-301-3001 local 505, 506</p>
                        <h5>REQUISITION AND ISSUE SLIP</h5>
                    </div>
                    
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Stock No.</th>
                                <th>Unit</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="printItems"></tbody>
                    </table>

                    <div class="report-footer">
                        <p>Purpose: For office use</p>
                        <div class="signatures">
                            <div class="signature-block">
                                <div class="signature-line">____________________</div>
                                <p>Requested by</p>
                            </div>
                            <div class="signature-block">
                                <div class="signature-line">____________________</div>
                                <p>Approved by</p>
                            </div>
                            <div class="signature-block">
                                <div class="signature-line">____________________</div>
                                <p>Issued by</p>
                            </div>
                        </div>
            </div>
            </div>
            </div>
        </div>
    </div>
</div>

<style>
.signature-line {
    border-bottom: 1px solid #000;
    width: 90%;
    margin: 0 auto;
}
.report-header {
    margin-bottom: 20px;
}
.report-header h4 {
    font-size: 16px;
    font-weight: bold;
    margin: 0;
    line-height: 1.2;
}
.report-header h5 {
    font-size: 14px;
    font-weight: bold;
    margin: 0;
}
.report-header p {
    font-size: 12px;
    margin: 0;
    line-height: 1.2;
}
.report-header .table-bordered {
    border: 1px solid #000;
}
.report-header .table-bordered td {
    border: 1px solid #000;
    padding: 2px 4px;
    font-size: 12px;
}
.report-header span {
    font-size: 12px;
}
.table-bordered {
    border: 1px solid #000;
}
.table-bordered td {
    border: 1px solid #000;
    padding: 2px 4px;
}
.form-fields p {
    font-size: 14px;
}
.underline {
    border-bottom: 1px solid #000;
    padding-bottom: 2px;
}

@media print {
    /* Hide everything initially */
    body * {
        visibility: hidden;
    }
    
    /* Show only the report content */
    #reportPreview, #reportPreview * {
        visibility: visible !important;
    }
    
    /* Position the report at the top of the page */
    #reportPreview {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Hide ALL header elements and navigation */
    .navbar, .sidenav, .fixed-plugin, .nav-link, 
    .card-header, .btn, #navbar, #sidenav-main,
    nav, header, .page-header {
        display: none !important;
    }

    /* Hide specific date and UniVault elements */
    time, .time, .date, .univault, .header-text,
    [class*="univault"], [class*="header"] > *:not(.report-header *) {
        display: none !important;
    }

    /* Ensure clean page margins */
    @page {
        margin: 0.5cm;
    }

    /* Report header specific styles */
    .report-header {
        margin-top: 0 !important;
        padding-top: 0.5cm !important;
    }
    
    .report-header img {
        height: 60px !important;
        margin-bottom: 10px !important;
    }

    .report-header h4 {
        font-size: 16px !important;
        font-weight: bold !important;
    }

    .report-header h5 {
        font-size: 14px !important;
        font-weight: bold !important;
    }

    .report-header p {
        font-size: 12px !important;
    }

    /* Table styles */
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 20px !important;
    }
    
    .table th, .table td {
        border: 1px solid black !important;
        padding: 8px !important;
        text-align: left !important;
    }

    /* Remove all card and container styling */
    .card, .container-fluid, .card-body {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: none !important;
    }

    /* Updated signature section styles */
    .signature-line {
        border-bottom: 1px solid #000 !important;
        width: 90% !important;
        margin: 0 auto !important;
    }

    .d-flex.justify-content-between > div {
        flex: 1 !important;
        text-align: left !important;
        padding: 0 10px !important;
    }

    .d-flex.justify-content-between p {
        margin-bottom: 50px !important;
        font-weight: normal !important;
    }

    /* Ensure proper spacing for the entire signature section */
    .row.mt-4:last-child {
        margin-top: 30px !important;
        margin-bottom: 20px !important;
    }

    .form-fields {
        border: 1px solid #000 !important;
        padding: 15px !important;
        margin-top: 20px !important;
    }

    .underline {
        border-bottom: 1px solid #000 !important;
        padding-bottom: 2px !important;
    }
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range based on current filter
    updateDateRange();
    
    // Set the header date
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const year = today.getFullYear();
    document.getElementById('headerDate').textContent = `${month}${day}${year}`;
    
    // Add event listeners
    document.getElementById('generateReportBtn').addEventListener('click', generateReport);
    document.getElementById('filterType').addEventListener('change', updateDateRange);
    document.getElementById('start_date').addEventListener('change', updateEndDateBasedOnFilter);
    
    // Add event listener for department change
    document.getElementById('department').addEventListener('change', function() {
        // Clear any existing report when department changes
        document.getElementById('reportPreview').style.display = 'none';
        document.getElementById('printReportBtn').style.display = 'none';
    });
});

function updateEndDateBasedOnFilter() {
    const filterType = document.getElementById('filterType').value;
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (!startDateInput.value) return;
    
    const startDate = new Date(startDateInput.value);
    let endDate;
    
    switch(filterType) {
        case 'week':
            // Set end date to 6 days after start date (for a total of 7 days)
            endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            break;
        case 'month':
            // Set end date to the last day of the same month
            endDate = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
            break;
        case 'year':
            // Set end date to December 31 of the same year
            endDate = new Date(startDate.getFullYear(), 11, 31);
            break;
    }
    
    // Format the date as YYYY-MM-DD for the input
    const formattedEndDate = endDate.toISOString().split('T')[0];
    endDateInput.value = formattedEndDate;
}

function updateDateRange() {
    const filterType = document.getElementById('filterType').value;
    const today = new Date();
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    // Reset dates based on filter type
    switch(filterType) {
        case 'week':
            // Set to the beginning of current week (Sunday)
            const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
            startDate.valueAsDate = firstDay;
            endDate.valueAsDate = new Date(today.setDate(firstDay.getDate() + 6)); // End of week (Saturday)
            break;
        case 'month':
            // Set to the first day of current month
            startDate.valueAsDate = new Date(today.getFullYear(), today.getMonth(), 1);
            // Set to the last day of current month
            endDate.valueAsDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case 'year':
            // Set to the first day of current year
            startDate.valueAsDate = new Date(today.getFullYear(), 0, 1);
            // Set to the last day of current year
            endDate.valueAsDate = new Date(today.getFullYear(), 11, 31);
            break;
    }
}

function generateReport() {
    const filterType = document.getElementById('filterType').value;
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const department = document.getElementById('department').value;

    // Validate all required fields
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }
    
    if (!department) {
        alert('Please select a department');
        return;
    }

    // Validate date range
    const start = new Date(startDate);
    const end = new Date(endDate);
    if (end < start) {
        alert('End date cannot be earlier than start date');
        return;
    }

    // Disable button and show loading state
    const button = document.getElementById('generateReportBtn');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';

    // Make API call with all filters
    fetch('/admin/reports/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            filter_type: filterType,
            start_date: startDate,
            end_date: endDate,
            department: department
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.error || 'Error generating report');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Show report preview and print button
        document.getElementById('reportPreview').style.display = 'block';
        document.getElementById('printReportBtn').style.display = 'inline-block';
        
        // Update department name and responsibility center code
        const departmentName = document.getElementById('department').options[document.getElementById('department').selectedIndex].text;
        const departmentCode = document.getElementById('department').value;

        // Update office name and department
        const officeTds = document.querySelectorAll('table tr td');
        officeTds.forEach(td => {
            if (td.textContent.trim().startsWith('Office')) {
                td.innerHTML = `Office <span style="font-weight: bold;">${departmentName}</span>`;
            }
        });

        // Update responsibility center code
        const rcCodeTds = document.querySelectorAll('table tr td div');
        rcCodeTds.forEach(div => {
            if (div.textContent.includes('Responsibility Center Code:')) {
                // Map full department names to their abbreviations
                const departmentMap = {
                    'INTEGRATED RESEARCH AND TRAINING CENTER': 'IRTC',
                    'COLLEGE OF ENGINEERING': 'COE',
                    'COLLEGE OF ARCHITECTURE AND FINE ARTS': 'CAFA',
                    'COLLEGE OF INDUSTRIAL EDUCATION': 'CIE',
                    'COLLEGE OF INDUSTRIAL TECHNOLOGY': 'CIT',
                    'COLLEGE OF LIBERAL ARTS': 'CLA',
                    'COLLEGE OF SCIENCE': 'COS'
                };
                
                // Use the abbreviation if available, otherwise use the full name
                const displayCode = departmentMap[department] || department;
                
                div.innerHTML = `<span>Responsibility Center Code:</span><span style="font-weight: bold;">${displayCode}</span>`;
            }
        });
        
        // Populate table with filtered data
        const tbody = document.getElementById('reportItems');
        if (tbody) {
            tbody.innerHTML = '';
            
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    tbody.innerHTML += `
                        <tr>
                            <td style="border: 1px solid #000; padding: 2px 4px;">${item.stock_no || ''}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px;">${item.unit || ''}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px;">${item.description || ''}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px; text-align: right;">${item.quantity || ''}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px; text-align: right;">${item.quantity || ''}</td>
                            <td style="border: 1px solid #000; padding: 2px 4px;">${item.remarks || ''}</td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="6" style="border: 1px solid #000; padding: 2px 4px; text-align: center;">No items found for the selected department and date range</td></tr>';
            }
        }

        // Hide any previous error messages
        const errorAlert = document.querySelector('.alert-danger');
        if (errorAlert) {
            errorAlert.remove();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Hide the report preview and print button
        document.getElementById('reportPreview').style.display = 'none';
        document.getElementById('printReportBtn').style.display = 'none';
        
        // Remove any existing error alerts
        const existingAlert = document.querySelector('.alert-danger');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Show error message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger text-white';
        alertDiv.style.backgroundColor = '#fd5c70';
        alertDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${error.message}`;
        
        const filterRow = document.querySelector('.row.mb-4');
        filterRow.parentNode.insertBefore(alertDiv, filterRow);
        
        // Auto-dismiss alert after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    })
    .finally(() => {
        // Reset button state
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function printReport() {
    // Show the report preview if it's hidden
    const reportPreview = document.getElementById('reportPreview');
    reportPreview.style.display = 'block';
    
    // Wait a brief moment to ensure content is rendered
    setTimeout(() => {
        window.print();
    }, 100);
}

// Update the print button onclick
document.getElementById('printReportBtn').onclick = printReport;
</script>
@endpush
