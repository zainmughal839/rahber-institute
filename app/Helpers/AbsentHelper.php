<?php

use App\Models\McqPaper;
use App\Models\McqAttempt;
use App\Models\Student;
use Carbon\Carbon;

if (!function_exists('markAbsentStudents')) {

function markAbsentStudents(McqPaper $paper)
{
    if (!$paper->task) {
        return;
    }

    $now = now();

    $paperStart = Carbon::parse($paper->task->paper_date);
    $fullEnd = $paperStart->copy()
        ->addMinutes(($paper->per_mcqs_time ?? 0) + ($paper->subjective_time ?? 0));

        
    if ($now->lessThan($fullEnd)) {
        return;
    }

    
    $students = \DB::table('mcq_paper_student')
        ->where('mcq_paper_id', $paper->id)
        ->pluck('student_id');

    foreach ($students as $studentId) {

        $attemptExists = McqAttempt::where('mcq_paper_id', $paper->id)
            ->where('student_id', $studentId)
            ->exists();

            
        if (!$attemptExists) {

            McqAttempt::create([
                'mcq_paper_id' => $paper->id,
                'student_id' => $studentId,
                'status' => 'absent',
                'total_questions' => $paper->questions()->count(),
                'correct' => 0,
                'wrong' => $paper->questions()->count(),
                'score' => 0,
                'subjective_submitted' => false,
            ]);
        }
    }
}


}
