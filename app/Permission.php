<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Permission extends EntrustPermission
{
    // use Auditable;

    /**
     * Audit threshold.
     *
     * @var int
     */
    // protected $auditThreshold = 10;
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
