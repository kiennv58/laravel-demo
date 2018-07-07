<?php

use Illuminate\Database\Seeder;

class Customer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('customers')->delete();
        $data = [
        	[
        		'name' => 'Nguyễn Văn Kiên',
        		'phone' => '01682601994',
        		'address' => '102 Thái Thịnh, Đống Đa, Hà Nội'
        	],
        	[
        		'name' => 'Hoàng Thị Hoa',
        		'phone' => '0976855943',
        		'address' => '63 Đội Cấn, Hà Nội'
        	],
        	[
        		'name' => 'Trần Minh Anh',
        		'phone' => '0974453311',
        		'address' => 'Ngõ 66 Hồ Tùng Mậu, Hà Nội'
        	],
        ];

        \DB::table('customers')->insert($data);
    }
}
