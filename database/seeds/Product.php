<?php

use Illuminate\Database\Seeder;

class Product extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->delete();
        $data = [
        	[
        		'name' 		  => 'Thiết kế mới',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Thiết kế lại',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Chế bản đen trắng',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Chế bản màu',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Scan ảnh',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Scan tài liệu',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Đánh máy',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Sửa tài liệu',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Ghi đĩa CD',
        		'type' => 1
        	],
        	[
        		'name' 		  => 'Photo lẻ',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'In lẻ',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'In luận văn',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'Photo sách đóng ghim',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'Photo sách keo nhiệt',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'In sách',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'Photo hồ sơ thầu',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'Photo khổ lớn',
        		'type' => 3
        	],
        	[
        		'name' 		  => 'In khổ lớn',
        		'type' => 3
        	],
        	[
        		'name' 		  => 'In tờ rời',
        		'type' => 3
        	],
        	[
        		'name' 		  => 'In card visit',
        		'type' => 3
        	],
        	[
        		'name' 		  => 'In bìa sách',
        		'type' => 2
        	],
        	[
        		'name' 		  => 'In tem vỡ - tem bảo hành',
        		'type' => 3
        	],
            [
                'name'        => 'In thực đơn',
                'type' => 3
            ],
            [
                'name'        => 'In giấy tiêu đề – Letter Head',
                'type' => 3
            ],
            [
                'name'        => 'In Catalogue',
                'type' => 3
            ],
            [
                'name'        => 'In Đề can – In Decal',
                'type' => 3
            ]
        ];

        \DB::table('products')->insert($data);
    }
}
