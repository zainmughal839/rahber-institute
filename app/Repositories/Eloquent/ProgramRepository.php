<?php

namespace App\Repositories\Eloquent;

use App\Models\Program;
use App\Repositories\Interfaces\ProgramRepositoryInterface;

class ProgramRepository implements ProgramRepositoryInterface
{
    protected $model;

    public function __construct(Program $model)
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
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $program = $this->find($id);

        return $program->update($data);
    }

    public function delete($id)
    {
        $program = $this->find($id);

        return $program->delete();
    }

    public function syncSubjects($programId, $subjectIds)
{
    $program = Program::findOrFail($programId);
    $program->subjects()->sync($subjectIds);
    return true;
}

}
