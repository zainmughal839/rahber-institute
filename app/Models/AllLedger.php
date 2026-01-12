<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllLedger extends Model
{
    protected $table = 'all_ledger';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'amount',
        'type',
        'ledger_category',
        'title',
        'description_fee',
        'challan_no',
        'voucher_no',
        'voucher_date',
        'image_path',
        'description',
    ];

    
    public function scopeForProfitLoss($query, $fromDate = null, $toDate = null)
    {
        // ❌ Exclude total_fee completely
        $query->where('ledger_category', '!=', 'total_fee');

        // ✅ Date filter (voucher_date OR created_at fallback)
        if ($fromDate) {
            $query->where(function ($q) use ($fromDate) {
                $q->whereDate('voucher_date', '>=', $fromDate)
                ->orWhereDate('created_at', '>=', $fromDate);
            });
        }

        if ($toDate) {
            $query->where(function ($q) use ($toDate) {
                $q->whereDate('voucher_date', '<=', $toDate)
                ->orWhereDate('created_at', '<=', $toDate);
            });
        }

        return $query;
    }


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    
}
