<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Teacher;

interface TeacherRepositoryInterface
{
    public function paginate(int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator;
    public function all(): \Illuminate\Database\Eloquent\Collection;

    public function find(int $id): ?Teacher;
    public function create(array $data): Teacher;
    public function update(int $id, array $data): Teacher;
    public function delete(int $id): bool;
}
