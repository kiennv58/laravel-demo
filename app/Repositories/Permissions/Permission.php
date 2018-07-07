<?php

namespace App\Repositories\Permissions;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name', 'display_name', 'description'];

    protected static $prefixs = [
        'index'        => 'Xem',
        'show'         => 'Xem chi tiết',
        'store'        => 'Tạo mới',
        'update'       => 'Cập nhật',
        'destroy'      => 'Xóa',
        'confirm'      => 'Xác nhận',
        'export'       => 'Xuất excel',
    ];

    public static function getPrefixs()
    {
        return self::$prefixs;
    }
}
