<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->orderBy('id', 'desc')->paginate($perPage);
    }

    public function all()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $user = $this->find($id);
        if (!$user) {
            return null;
        }
        $user->update($data);

        return $user;
    }

    public function delete(int $id)
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        return $user->delete();
    }
}
