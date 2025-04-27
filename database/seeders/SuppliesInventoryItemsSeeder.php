<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuppliesInventory;
use Illuminate\Support\Facades\Schema;

class SuppliesInventoryItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Only seed if the table exists and is empty
        if (Schema::hasTable('supplies_inventory') && SuppliesInventory::count() === 0) {
            $now = now(); // Get current timestamp
            
            $items = [
                ['control_code' => 'SUP-BTCBD2YO', 'product_name' => 'PENCIL', 'quantity' => 21, 'unit_type' => 'Pack', 'product_image' => 'supplies/qGoiEUOnRvrQZZ5E26off2NvONaLV3EWhiRjQdGp.jpg'],
                ['control_code' => 'SUP-U2V6KA8Q', 'product_name' => 'ACETATE', 'quantity' => 75, 'unit_type' => 'Sheet', 'product_image' => null],
                ['control_code' => 'SUP-IMDPGSKY', 'product_name' => 'ADHESIVE (GLUE)', 'quantity' => 88, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-6ZWYFNJR', 'product_name' => 'AIR FRESHENER', 'quantity' => 50, 'unit_type' => 'Litre', 'product_image' => null],
                ['control_code' => 'SUP-A6SGOJPY', 'product_name' => 'ALCOHOL (500 ml)', 'quantity' => 20, 'unit_type' => 'Bottle', 'product_image' => null],
                ['control_code' => 'SUP-7WI3CDDK', 'product_name' => 'ALCOHOL (GAL.)', 'quantity' => 1, 'unit_type' => 'Gallon', 'product_image' => null],
                ['control_code' => 'SUP-CXWIWGI4', 'product_name' => 'BATTERY (AAA)', 'quantity' => 50, 'unit_type' => 'Pack', 'product_image' => null],
                ['control_code' => 'SUP-1MXXMJZV', 'product_name' => 'BATTERY (AA)', 'quantity' => 20, 'unit_type' => 'Pack', 'product_image' => null],
                ['control_code' => 'SUP-TSCDANIR', 'product_name' => 'BINDER RING (32 MM)', 'quantity' => 16, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-YYZ5URVD', 'product_name' => 'BINDER RING (1/2")', 'quantity' => 10, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-5LVKAKCY', 'product_name' => 'BINDER RING (2")', 'quantity' => 5, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-0U02TYT6', 'product_name' => 'BOOK (Record, 300 PP)', 'quantity' => 5, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-GEHJRXFF', 'product_name' => 'BOOK (Record, 500 PP)', 'quantity' => 77, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-NINOA5KF', 'product_name' => 'BROOM STICK', 'quantity' => 9, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-BZTO8VJI', 'product_name' => 'CARTOLINA', 'quantity' => 38, 'unit_type' => 'Piece', 'product_image' => null],
                ['control_code' => 'SUP-5ALFWUR1', 'product_name' => 'CHALK', 'quantity' => 18, 'unit_type' => 'Box', 'product_image' => null],
                ['control_code' => 'SUP-V4I9DBLB', 'product_name' => 'CLEANER (SPLENDA)', 'quantity' => 15, 'unit_type' => 'Bottle', 'product_image' => null],
                ['control_code' => 'SUP-FE1DSJ7O', 'product_name' => 'CLEARBOOK (A4)', 'quantity' => 20, 'unit_type' => 'Ream', 'product_image' => 'supplies/hP7OsJ0NST0IReJxThA3D12p0m2EBaFMJtgwU5Ds.jpg'],
                ['control_code' => 'SUP-AZ4JMESJ', 'product_name' => 'CLIP (binder, 3/4")', 'quantity' => 20, 'unit_type' => 'Box', 'product_image' => 'supplies/MV3Qo0KjECQPji8aRoKIiR2sezZVj0V1aUmS3MAM.jpg'],
                ['control_code' => 'SUP-40AXDPBH', 'product_name' => 'TAPE (masking, 1")', 'quantity' => 51, 'unit_type' => 'Piece', 'product_image' => 'supplies/bvvX4tjtvlLoBQ5tGS7K2RwshNkP6EaQTLwFbn3x.jpg'],
            ];

            foreach ($items as $item) {
                SuppliesInventory::create(array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now
                ]));
            }
            
            // Add the rest of the items in batches
            $moreItems = [
                ['control_code' => 'SUP-DQV4RD6M', 'product_name' => 'CLIP (backfold, 25mm)', 'quantity' => 12, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-H39U1QPD', 'product_name' => 'CLIP (backfold, 50mm)', 'quantity' => 21, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-PDG3BDMU', 'product_name' => 'CLIP (binder, 1 ¼")', 'quantity' => 12, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-2WIAP3I8', 'product_name' => 'CORRECTION (tape)', 'quantity' => 56, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-T5BDAIYE', 'product_name' => 'DATA (file box)', 'quantity' => 32, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-PDJOBPCR', 'product_name' => 'DISK (dvd, 16x speed, 4.7gb)', 'quantity' => 12, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-UN07Y55B', 'product_name' => 'DISK (dvd, 4x speed, 4.7gb)', 'quantity' => 24, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-BHYBPK8U', 'product_name' => 'DISPENSER (tape, table top)', 'quantity' => 45, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-NFRKKWGO', 'product_name' => 'DRIVE (flash)', 'quantity' => 23, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-ED7A6XOA', 'product_name' => 'ENVELOPE (10"x 15")', 'quantity' => 42, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-ZXDB2QKW', 'product_name' => 'ENVELOPE (9"x 12")', 'quantity' => 43, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-UKMICMZF', 'product_name' => 'ENVELOPE (mailing)', 'quantity' => 33, 'unit_type' => 'Pack'],
                ['control_code' => 'SUP-K2PU5TDT', 'product_name' => 'ENVELOPE (expanding)', 'quantity' => 14, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-SGD7HAKF', 'product_name' => 'ENVELOPE (plastic)', 'quantity' => 52, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-RZZF1M8T', 'product_name' => 'ERASER (for whiteboard)', 'quantity' => 32, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-W5RAFPTJ', 'product_name' => 'ERASER (rubber)', 'quantity' => 14, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-ROYPX7RR', 'product_name' => 'FASTENER (metal)', 'quantity' => 23, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-7MPAJNNU', 'product_name' => 'FILM (carbon)', 'quantity' => 41, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-1B3O8TSQ', 'product_name' => 'FOLDER (tagboard, legal)', 'quantity' => 62, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-XVSOFRHC', 'product_name' => 'FOLDER (Morocco, long)', 'quantity' => 82, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-YMPGTY8O', 'product_name' => 'FOLDER (fancy, A4)', 'quantity' => 63, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-SASNIAPF', 'product_name' => 'FOLDER (I-type, A4)', 'quantity' => 27, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-XXHDZZA3', 'product_name' => 'FOLDER (data, 3"x9"x15")', 'quantity' => 57, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-9JJOJ5WP', 'product_name' => 'FOLDER (tagboard, A4)', 'quantity' => 45, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-W9CRP84L', 'product_name' => 'FOLDER (I-type, legal)', 'quantity' => 68, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-6OBMQ9KU', 'product_name' => 'GLOVES', 'quantity' => 21, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-9IJZEVBT', 'product_name' => 'INDEX TAB', 'quantity' => 14, 'unit_type' => 'Pack'],
                ['control_code' => 'SUP-W6IZ0VHB', 'product_name' => 'INK (stamp pad)', 'quantity' => 12, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-LRBVYRI2', 'product_name' => 'INK (pixma, PG-810)', 'quantity' => 14, 'unit_type' => 'Cartridges'],
                ['control_code' => 'SUP-SEYCKCJF', 'product_name' => 'INSECTICIDE', 'quantity' => 21, 'unit_type' => 'Bottle'],
                ['control_code' => 'SUP-CP5VX2TE', 'product_name' => 'LOOSELEAF COVER', 'quantity' => 0, 'unit_type' => 'Pack'],
            ];

            foreach ($moreItems as $item) {
                SuppliesInventory::create(array_merge($item, [
                    'product_image' => null,
                    'created_at' => $now,
                    'updated_at' => $now
                ]));
            }

            // Add the final batch of items
            $finalItems = [
                ['control_code' => 'SUP-HX1UZMB7', 'product_name' => 'MOP HEAD', 'quantity' => 32, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-63ZIKP01', 'product_name' => 'NOTEPAD (3"X3")', 'quantity' => 57, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-DLJFPANR', 'product_name' => 'NOTEPAD (3"X4")', 'quantity' => 75, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-MLR2RRZH', 'product_name' => 'NOTEPAD (2" X3)', 'quantity' => 76, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-M9TLS3XH', 'product_name' => 'PEN (ballpen, black)', 'quantity' => 54, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-GCFW5PPO', 'product_name' => 'PEN (ballpen, blue)', 'quantity' => 54, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-OYM3MMWC', 'product_name' => 'PEN (ballpen, red)', 'quantity' => 54, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-AXN0JI5F', 'product_name' => 'PEN (gel pen, orange)', 'quantity' => 21, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-RA5WHI3Q', 'product_name' => 'PEN (gel pen, violet)', 'quantity' => 23, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-8HY5HFCL', 'product_name' => 'PEN (gel pen, green)', 'quantity' => 24, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-L7NYD9AO', 'product_name' => 'PEN (permanent, black)', 'quantity' => 51, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-FUBLT227', 'product_name' => 'PEN (permanent, blue)', 'quantity' => 35, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-JTA098NR', 'product_name' => 'PEN (sign pen, black)', 'quantity' => 21, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-ZS9IPYSW', 'product_name' => 'PEN (sign pen, blue)', 'quantity' => 34, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-VLWHO2ZE', 'product_name' => 'PEN (sign pen, red)', 'quantity' => 31, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-9RCUNRNG', 'product_name' => 'PEN (whiteboard marker)', 'quantity' => 41, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-MHB0V0QB', 'product_name' => 'PUMP (toilet bowl)', 'quantity' => 30, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-SFXWX4N9', 'product_name' => 'PUSH PIN', 'quantity' => 15, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-Z9Y4Z1JI', 'product_name' => 'SANITIZER (handwash 500ml)', 'quantity' => 33, 'unit_type' => 'Bottle'],
                ['control_code' => 'SUP-FVQQK25D', 'product_name' => 'SCISSORS', 'quantity' => 41, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-7DDSU66V', 'product_name' => 'SCOURING PAD', 'quantity' => 31, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-RE0OKGVP', 'product_name' => 'STAMP PAD', 'quantity' => 21, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-N4CUFZDP', 'product_name' => 'STAPLER (heavy duty)', 'quantity' => 31, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-EVK3FG6T', 'product_name' => 'STAPLE REMOVER', 'quantity' => 8, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-JLQ2R6QQ', 'product_name' => 'STAPLE WIRE', 'quantity' => 23, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-4CO0LKRC', 'product_name' => 'TAPE (masking, 48 mm)', 'quantity' => 13, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-SBTO9GA2', 'product_name' => 'TAPE (transparent, 24 mm)', 'quantity' => 15, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-MCK5GXYK', 'product_name' => 'TONER (CE505A)', 'quantity' => 21, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-UZYWK42J', 'product_name' => 'TONER (CE285A)', 'quantity' => 16, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-966I0QWV', 'product_name' => 'TONER (MX-235FT)', 'quantity' => 25, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-ZAFVVXYX', 'product_name' => 'TONER (kyocera, tk1114)', 'quantity' => 21, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-BRRVFNVR', 'product_name' => 'TONER (kyocera, tk5234c)', 'quantity' => 15, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-N3LQCWUK', 'product_name' => 'TONER (kyocera, tk5234m)', 'quantity' => 32, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-3ASYRJPL', 'product_name' => 'TONER (kyocera, tk5234y)', 'quantity' => 24, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-REA5HHTM', 'product_name' => 'TONER (kyocera, tk5234k)', 'quantity' => 16, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-IA0QM59Q', 'product_name' => 'TONER (fuji)', 'quantity' => 21, 'unit_type' => 'Piece'],
                ['control_code' => 'SUP-LDLBCM8B', 'product_name' => 'TRASHBAG (plastic)', 'quantity' => 56, 'unit_type' => 'Roll'],
                ['control_code' => 'SUP-ZT8LU9IL', 'product_name' => 'ACETATE', 'quantity' => 12, 'unit_type' => 'Box'],
                ['control_code' => 'SUP-DSK0HYV6', 'product_name' => 'ACETATE', 'quantity' => 12, 'unit_type' => 'Sheet'],
            ];

            foreach ($finalItems as $item) {
                SuppliesInventory::create(array_merge($item, [
                    'product_image' => null,
                    'created_at' => $now,
                    'updated_at' => $now
                ]));
            }
        }
    }
} 