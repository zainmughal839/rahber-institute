<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectiveAnswer extends Model
{
    

protected $fillable = ['subjective_question_id', 'student_id', 'obtained_marks'];

    // All student uploaded images
    public function images()
    {
        return $this->hasMany(SubjectiveAnswerImage::class, 'subjective_answer_id');
    }

    public function studentImages()
{
    return $this->hasMany(SubjectiveAnswerImage::class, 'subjective_answer_id')
                ->where('type', 'student');
}

public function teacherImages()
{
    return $this->hasMany(SubjectiveAnswerImage::class, 'subjective_answer_id')
                ->where('type', 'teacher');
}


    public function question()
    {
        return $this->belongsTo(SubjectiveQuestion::class,'subjective_question_id');
    }

    // App/Models/SubjectiveAnswer.php

public function feedbackImages()
{
    return $this->hasMany(SubjectiveAnswerImage::class, 'subjective_answer_id')
                ->where('type', 'feedback'); // optional: to distinguish
}
}