<?php

use Illuminate\Database\Seeder;

class Pages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('assets')->delete();
        $data = [
        	[
        		'name' => 'Giấy Bãi bằng 60/84',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Bãi bằng 60/90',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Bãi bằng 70/90',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Xương giang 62/90',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Natura 70/90',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Double A 70/90',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Couche 150',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Couche 180',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Couche 200',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Couche 230',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Couche 250',
                'type' => 1
        	],
        	[
        		'name' => 'Giấy Couche 300',
                'type' => 1
        	],
        	[
        		'name' => 'Đạn ghim',
                'type' => 1
        	],
        	[
        		'name' => 'Băng dính',
                'type' => 1
        	],
        	[
        		'name' => 'Keo nhiệt',
                'type' => 1
        	],
        	[
        		'name' => 'Thớt dao xén điện',
                'type' => 1
        	]
        ];

        \DB::table('assets')->insert($data);
    }
}
