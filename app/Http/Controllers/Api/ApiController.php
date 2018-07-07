<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller {

    protected $validationRules = [];
    protected $validationMessages = [];

    public function checkPermission($moduleName)
    {
        $this->middleware("ability:superadmin,{$moduleName}.index")->only(['index']);
        $this->middleware("ability:superadmin,{$moduleName}.show")->only('show');
        $this->middleware("ability:superadmin,{$moduleName}.store")->only('store');
        $this->middleware("ability:superadmin,{$moduleName}.update")->only('update');
        $this->middleware("ability:superadmin,{$moduleName}.destroy")->only('destroy');
        $this->middleware("ability:superadmin,{$moduleName}.export")->only('excelExport');
        $this->middleware("ability:superadmin,{$moduleName}.fake_login")->only('fakeLogin');
    }
}
