<?php

namespace App\Repositories\Eloquent;

use App\Models\UserAssignment;
use App\Repositories\Interfaces\UserAssignmentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserAssignmentRepository implements UserAssignmentRepositoryInterface
{
    protected $model;

    public function __construct(UserAssignment $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
{
    $query = $this->model->newQuery()
        ->with(['user', 'assignable' => function ($morphTo) {
            $morphTo->morphWith([
                \App\Models\Student::class => [],
                \App\Models\Teacher::class => [],
            ]);
        }]);

        if (!empty($filters['panel_type'])) {
            $query->where('panel_type', $filters['panel_type']);
        }

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('email', 'like', "%$s%")
                ->orWhereHas('user', fn ($q2) => $q2->where('name', 'like', "%$s%"));
            });
        }

       return $query->orderByDesc('id')->paginate($perPage);
}

    public function find(int $id): ?UserAssignment
    {
        return $this->model->with('user', 'assignable')->find($id);
    }

    public function create(array $data): UserAssignment
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->model->find($id);
        if (!$record) {
            return false;
        }

        return $record->update($data);
    }

    // public function delete(int $id): bool
    // {
    //     $record = $this->model->find($id);
    //     if (!$record) {
    //         return false;
    //     }

    //     return $record->delete();
    // }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function getByUserId(int $userId): ?UserAssignment
    {
        return $this->model->where('user_id', $userId)->first();
    }
}