<?php

namespace App\Repositories\Eloquent;

use App\Models\ClassSubject;
use App\Repositories\Interfaces\ClassSubjectRepositoryInterface;

class ClassSubjectRepository implements ClassSubjectRepositoryInterface
{
    public function all()
    {
        return ClassSubject::with(['subjects', 'sessionProgram'])->latest()->get();
    }

    public function paginate($perPage = 10)
    {
        return ClassSubject::with(['subjects', 'sessionProgram'])->latest()->paginate($perPage);
    }

    public function find($id)
    {
        return ClassSubject::with(['subjects', 'sessionProgram'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return ClassSubject::create($data);
    }

    public function update($id, array $data)
    {
        $class = ClassSubject::findOrFail($id);

        return $class->update($data);
    }

    public function delete($id)
    {
        $class = ClassSubject::findOrFail($id);

        return $class->delete();
    }
}