<?php

namespace App\Repositories\Eloquent;

use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface
{

    protected $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return Student::with(['sessionProgram.session', 'sessionProgram.program'])
            ->latest()->get();
    }

    public function find($id)
    {
        return Student::findOrFail($id);
    }

     public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    // If you want even more consistency, you can also add:
    public function findById($id)
    {
        return $this->find($id);
    }

    public function create(array $data)
    {
        return Student::create($data);
    }

    public function update($id, array $data)
    {
        $student = Student::findOrFail($id);
        $student->update($data);

        return $student;
    }

    public function delete($id)
    {
        return Student::findOrFail($id)->delete();
    }

    public function paginate($limit = 10)
    {
        return Student::with(['sessionProgram.session', 'sessionProgram.program'])
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    public function allWithoutPagination()
    {
        return Student::with(['sessionProgram.session', 'sessionProgram.program'])
            ->orderBy('id', 'desc')
            ->get();
    }
}
