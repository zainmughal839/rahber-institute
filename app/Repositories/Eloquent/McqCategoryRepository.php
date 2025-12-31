<?php

namespace App\Repositories\Eloquent;

use App\Models\McqCategory; // ✅ THIS LINE WAS MISSING
use App\Repositories\Interfaces\McqCategoryRepositoryInterface;

class McqCategoryRepository implements McqCategoryRepositoryInterface
{
    public function all()
    {
        return McqCategory::latest()->get();
    }

    public function find($id)
    {
        return McqCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        return McqCategory::create($data);
    }

    public function update($id, array $data)
    {
        $cat = McqCategory::findOrFail($id);
        $cat->update($data);
        return $cat;
    }

    public function delete($id)
    {
        return McqCategory::destroy($id);
    }
}
