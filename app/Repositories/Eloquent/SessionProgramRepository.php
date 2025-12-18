<?php

namespace App\Repositories\Eloquent;

use App\Models\SessionProgram;
use App\Repositories\Interfaces\SessionProgramRepositoryInterface;

class SessionProgramRepository implements SessionProgramRepositoryInterface
{
    protected $model;

    public function __construct(SessionProgram $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['session', 'program'])->latest()->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, array $data)
    {
        return $this->find($id)->update($data);
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function paginate($perPage = 10)
    {
        return $this->model
            ->with(['session', 'program'])
            ->latest()
            ->paginate($perPage);
    }

    public function getAll()
    {
        return $this->model
            ->with(['session', 'program'])
            ->latest()
            ->get();
    }

    public function getProgramsBySession($sessionId)
{
    return SessionProgram::where('session_id', $sessionId)
        ->pluck('program_id')
        ->toArray();
}

public function deleteBySession($sessionId)
{
    return SessionProgram::where('session_id', $sessionId)->delete();
}

}
