<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'logo',
        'invoice_logo',
        'favicon',
        'paid_stamp',
    ];

    
    public static function getSettings()
    {
        return self::firstOrCreate([]); // always return the first row
    }
    

    // Get logo URL
    public function getLogoUrl()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-logo.png');
    }

    public function getFaviconUrl()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : asset('images/favicon.ico');
    }

    public function getPaidStampUrl()
    {
        return $this->paid_stamp ? asset('storage/' . $this->paid_stamp) : null;
    }
    
}