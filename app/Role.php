<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

// use OwenIt\Auditing\Auditable;
// use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Role extends EntrustRole
{
    // use Auditable;

    /**
     * Audit threshold.
     *
     * @var int
     */
    // protected $auditThreshold = 10;

    protected $fillable = ['name', 'display_name', 'description'];
}
