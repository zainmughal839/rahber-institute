<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
   protected $fillable = [
    'challan_no',
    'student_id',
    'issue_date',
    'due_date',
    'amount',
    'status'
];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    protected static function booted()
    {
        static::deleting(function ($challan) {
            if ($challan->status === 'paid') {
                \App\Models\AllLedger::where('ledger_category', 'fee_challan')
                    ->where('challan_no', $challan->challan_no)
                    ->delete();
            }
        });
    }
}

