<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Zizaco\Entrust\Entrust;

class AdminController extends Controller
{
    public function checkPermission($moduleName)
    {
        $this->middleware("ability:superadmin|owner,{$moduleName}.index")->only(['index']);
        $this->middleware("ability:superadmin|owner,{$moduleName}.show")->only('show');
        $this->middleware("ability:superadmin|owner,{$moduleName}.store")->only(['store', 'create']);
        // $this->middleware("ability:superadmin,{$moduleName}.edit")->only(['update', 'edit']);
        $this->middleware("ability:superadmin|owner,{$moduleName}.update")->only(['update', 'edit', 'active']);
        $this->middleware("ability:superadmin|owner,{$moduleName}.destroy")->only('destroy');
    }
}
