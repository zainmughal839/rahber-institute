<?php

namespace App\Repositories\Eloquent;

use App\Models\ClassTeacher;
use App\Repositories\Interfaces\ClassTeacherRepositoryInterface;

class ClassTeacherRepository implements ClassTeacherRepositoryInterface
{
    public function paginate(int $perPage = 10)
    {
        return ClassTeacher::with([
            'teacher',
            'classSubject.subjects',
            'classSubject.sessionProgram.session',
            'classSubject.sessionProgram.program',
        ])->latest()->paginate($perPage);
    }

    public function all()
    {
        return ClassTeacher::with([
            'teacher',
            'classSubject.subjects',
            'classSubject.sessionProgram.session',
            'classSubject.sessionProgram.program',
        ])->latest()->get();
    }

    public function find($id)
    {
        return ClassTeacher::with([
            'teacher',
            'classSubject.subjects',
            'classSubject.sessionProgram.session',
            'classSubject.sessionProgram.program',
        ])->findOrFail($id);
    }

    public function store(array $data)
    {
        return ClassTeacher::create($data);
    }

    public function update($id, array $data)
    {
        $rec = ClassTeacher::findOrFail($id);
        $rec->update($data);

        return $rec;
    }

    public function delete($id)
    {
        $rec = ClassTeacher::findOrFail($id);

        return $rec->delete();
    }
}