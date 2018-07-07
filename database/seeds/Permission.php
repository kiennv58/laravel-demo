<?php

use Illuminate\Database\Seeder;

class Permission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'order.index',
                'display_name' => 'Xem danh sách đơn hàng'
            ],
            [
                'name' => 'order.show',
                'display_name' => 'Xem đơn hàng'
            ],
            [
                'name' => 'order.update',
                'display_name' => 'Sửa đơn hàng'
            ],
            [
                'name' => 'order.destroy',
                'display_name' => 'Xóa đơn hàng'
            ],
            [
                'name' => 'product.index',
                'display_name' => 'Xem danh sách sản phẩm'
            ],
            [
                'name' => 'product.show',
                'display_name' => 'Xem sản phẩm'
            ],
            [
                'name' => 'product.update',
                'display_name' => 'Sửa sản phẩm'
            ],
            [
                'name' => 'product.destroy',
                'display_name' => 'Xóa sản phẩm'
            ],
            [
                'name' => 'customer.index',
                'display_name' => 'Xem danh sách khách hàng'
            ],
            [
                'name' => 'customer.show',
                'display_name' => 'Xem khách hàng'
            ],
            [
                'name' => 'customer.update',
                'display_name' => 'Sửa khách hàng'
            ],
            [
                'name' => 'customer.destroy',
                'display_name' => 'Xóa khách hàng'
            ]
        ]);
    }
}
