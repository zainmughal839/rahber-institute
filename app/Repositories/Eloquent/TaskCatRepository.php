<?php

namespace App\Repositories\Eloquent;

use App\Models\TaskCat;
use App\Repositories\Interfaces\TaskCatRepositoryInterface;

class TaskCatRepository implements TaskCatRepositoryInterface
{
    public function all()
    {
        return TaskCat::orderBy('id', 'DESC')->get();
    }

    public function find($id)
    {
        return TaskCat::findOrFail($id);
    }

    public function store(array $data)
    {
        return TaskCat::create($data);
    }

    public function update($id, array $data)
    {
        $record = TaskCat::findOrFail($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        $record = TaskCat::findOrFail($id);
        return $record->delete();
    }
}
