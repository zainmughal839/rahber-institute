<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->orderBy('id', 'desc')->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        $role = $this->model->create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        if (!empty($data['permissions'])) {
            // permissions must be NAMES (NOT IDs)
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function update($id, array $data)
    {
        $role = $this->model->findOrFail($id);

        $role->update([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        return $role;
    }

    public function delete($id)
    {
        $role = $this->model->findOrFail($id);
        $role->syncPermissions([]);

        return $role->delete();
    }

    public function syncPermissions($id, array $permissionNames)
    {
        $role = $this->model->findOrFail($id);
        $role->syncPermissions($permissionNames);

        return $role;
    }
}
