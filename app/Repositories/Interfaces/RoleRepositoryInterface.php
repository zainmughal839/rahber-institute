<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function all();

    public function paginate($perPage = 10);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function syncPermissions($id, array $permissionIds);
}
