<?php

namespace App\Repositories\Interfaces;

interface ClassTeacherRepositoryInterface
{
    public function paginate(int $perPage = 10);

    public function all();

    public function find($id);

    public function store(array $data);

    public function update($id, array $data);

    public function delete($id);
}