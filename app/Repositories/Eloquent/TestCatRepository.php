<?php

namespace App\Repositories\Eloquent;

use App\Models\TestCat;
use App\Repositories\Interfaces\TestCatRepositoryInterface;

class TestCatRepository implements TestCatRepositoryInterface
{
    public function all()
    {
        return TestCat::latest()->get();
    }

    public function find($id)
    {
        return TestCat::findOrFail($id);
    }

    public function store(array $data)
    {
        return TestCat::create($data);
    }

    public function update($id, array $data)
    {
        return TestCat::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return TestCat::destroy($id);
    }
}
