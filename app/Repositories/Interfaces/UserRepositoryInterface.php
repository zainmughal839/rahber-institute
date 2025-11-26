<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function paginate(int $perPage = 10);

    public function all();

    public function find(int $id);

    public function findByEmail(string $email);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
