<?php

namespace App\Repositories\Eloquent;

use App\Models\StuCategory;
use App\Repositories\Interfaces\StuCategoryRepositoryInterface;

class StuCategoryRepository implements StuCategoryRepositoryInterface
{
    public function all()
    {
        return StuCategory::orderBy('id', 'DESC')->get();
    }

    public function find($id)
    {
        return StuCategory::findOrFail($id);
    }

    public function store($data)
    {
        return StuCategory::create($data);
    }

    public function update($id, $data)
    {
        $category = StuCategory::findOrFail($id);

        return $category->update($data);
    }

    public function delete($id)
    {
        $category = StuCategory::findOrFail($id);

        return $category->delete();
    }
}