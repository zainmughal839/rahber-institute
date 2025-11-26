<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

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
        return $this->model->with('permissions')->findOrFail($id);
    }

    public function create(array $data)
    {
        $role = $this->model->create([
            'name' => $data['name'],
            'display_name' => $data['display_name'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return $role;
    }

    public function update($id, array $data)
    {
        $role = $this->model->findOrFail($id);
        $role->update([
            'name' => $data['name'],
            'display_name' => $data['display_name'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return $role;
    }

    public function delete($id)
    {
        $role = $this->model->findOrFail($id);
        $role->permissions()->detach();
        $role->users()->detach(); // Detach users from role

        return $role->delete();
    }

    public function syncPermissions($id, array $permissionIds)
    {
        $role = $this->model->findOrFail($id);
        $role->permissions()->sync($permissionIds);

        return $role;
    }
}
