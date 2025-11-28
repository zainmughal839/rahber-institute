<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionRepository implements PermissionRepositoryInterface
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        // DB ke hisaab se "group_name" aur "name" use kar rahe hain
        return $this->model->orderBy('name')->get();
    }

    public function findByKey($key)
    {
        return $this->model->where('name', $key)->first();
    }
}
