<?php

namespace App\Repositories\Interfaces;

interface SessionProgramRepositoryInterface
{
    public function all();

    public function paginate($perPage = 10);

    public function create(array $data);

    public function find($id);

    public function update($id, array $data);

    public function delete($id);

    public function getProgramsBySession($sessionId);
public function deleteBySession($sessionId);

}
