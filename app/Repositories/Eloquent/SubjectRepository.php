<?php

namespace App\Repositories\Eloquent;

use App\Models\Subject;
use App\Repositories\Interfaces\SubjectRepositoryInterface;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function all()
    {
        return Subject::orderBy('id', 'DESC')->get();
    }

    public function find($id)
    {
        return Subject::findOrFail($id);
    }

    public function store(array $data)
    {
        return Subject::create($data);
    }

    public function update($id, array $data)
    {
        $subject = Subject::findOrFail($id);

        return $subject->update($data);
    }

    public function delete($id)
    {
        $subject = Subject::findOrFail($id);

        return $subject->delete();
    }
}