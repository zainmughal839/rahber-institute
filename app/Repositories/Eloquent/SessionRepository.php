<?php

namespace App\Repositories\Eloquent;

use App\Models\Session;
use App\Repositories\Interfaces\SessionRepositoryInterface;

class SessionRepository implements SessionRepositoryInterface
{
    protected $model;

    public function __construct(Session $session)
    {
        $this->model = $session;
    }

    // public function all()
    // {
    //     return $this->model->all();
    // }
    public function all()
    {
        return $this->model->orderBy('id', 'DESC')->paginate(10);
    }

    public function allRecords()
    {
        return $this->model->orderBy('id', 'DESC')->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $session = $this->model->find($id);
        if ($session) {
            $session->update($data);
        }

        return $session;
    }

    public function delete($id)
    {
        $session = $this->model->find($id);
        if ($session) {
            $session->delete();
        }

        return $session;
    }
}
