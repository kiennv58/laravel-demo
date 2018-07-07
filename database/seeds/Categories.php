<?php

use Illuminate\Database\Seeder;

class Categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->delete();
        $data = [
        	[
        		'name' => 'Thiết kế'
        	],
        	[
        		'name' => 'In ấn'
        	],
        	[
        		'name' => 'Gia công sau in'
        	],
        ];

        \DB::table('categories')->insert($data);
    }
}
