<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminActionButtons extends Component
{
    public $model;
    public $permissions;
    public $routes;
    public $tableId;

    public function __construct(
        $model,
        $permissions = [],
        $routes = [],
        $tableId = 'datatable'
    ) {
        $this->model       = $model;
        $this->permissions = $permissions;
        $this->routes      = $routes;
        $this->tableId     = $tableId;
    }

    public function render()
    {
        return view('admin.components.admin-action-buttons');
    }
}
