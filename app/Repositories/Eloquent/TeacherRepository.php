<?php

namespace App\Repositories\Eloquent;

use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TeacherRepository implements TeacherRepositoryInterface
{
    protected $model;

    public function __construct(Teacher $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->orderBy('name')->paginate($perPage);
    }

    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    public function find(int $id): ?Teacher
    {
        return $this->model->find($id);
    }

    public function create(array $data): Teacher
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Teacher
    {
        $t = $this->find($id);
        $t->update($data);
        return $t;
    }

    public function delete(int $id): bool
    {
        $t = $this->find($id);
        if (!$t) return false;
        return (bool) $t->delete();
    }
}
