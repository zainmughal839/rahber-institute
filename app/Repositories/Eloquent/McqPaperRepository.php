<?php

namespace App\Repositories\Eloquent;

use App\Models\McqPaper;
use App\Repositories\Interfaces\McqPaperRepositoryInterface;


class McqPaperRepository implements McqPaperRepositoryInterface {

    public function all(){
        return McqPaper::latest()->get();   // ✅ NO with('questions')
    }

    public function find($id){
        return McqPaper::findOrFail($id);   // ✅
    }

    public function create(array $data){
        return McqPaper::create($data);
    }

    public function update($id,array $data){
        $paper = McqPaper::findOrFail($id);
        $paper->update($data);
        return $paper;
    }

    public function delete($id){
        return McqPaper::destroy($id);
    }
}

