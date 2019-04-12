<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            [
                'name' => 'Công ty CPCS Bắc Hapulico',
                'short_name' => 'BHPL'
            ],
            [
                'name' => 'Công ty CPCS Nam Hapulico',
                'short_name' => 'NHPL'
            ],
            [
                'name' => 'Công ty CP vật tư công nghiệp Hà Nội',
                'short_name' => 'VTCN'
            ],
            [
                'name' => 'Chi nhánh Đà Nẵng',
                'short_name' => 'CNDN'
            ],
            [
                'name' => 'Phòng Kế hoạch Kinh doanh',
                'short_name' => 'KHKD'
            ],
            [
                'name' => 'Xí nghiệp quản lý điện chiếu sáng',
                'short_name' => 'XNVH'
            ]
        ]);

        DB::table('categories')->insert([
            [
                'name' => 'Cột thép <12m',
            ],
            [
                'name' => 'Cột cao (đến 17m, 2 đoạn)',
            ],
            [
                'name' => 'Cột THGT có tay vươn',
            ],
            [
                'name' => 'Cần đèn, lọng',
            ],
            [
                'name' => 'Cột monopole, thân nâng hạ',
            ],
            [
                'name' => 'Tay lắp đèn cầu trên cột',
            ],
            [
                'name' => 'Cột trên đế gang',
            ],
            [
                'name' => 'Cột sử dụng các đoạn ống mua sẵn',
            ],
            [
                'name' => 'Đèn đường phố',
            ],
            [
                'name' => 'Đèn sân vườn',
            ],
            [
                'name' => 'Đèn pha',
            ],
            [
                'name' => 'Đèn Led',
            ]
        ]);
    }
}
