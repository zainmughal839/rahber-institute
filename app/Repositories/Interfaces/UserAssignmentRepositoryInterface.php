<?php

namespace App\Repositories\Interfaces;

use App\Models\UserAssignment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserAssignmentRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?UserAssignment;

    public function create(array $data): UserAssignment;

    public function update(int $id, array $data): bool;

    public function delete($id);

    public function getByUserId(int $userId): ?UserAssignment;
}