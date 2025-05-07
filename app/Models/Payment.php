<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payment_type_id',
        'gross',
        'cnss',
        'tax_rate',
        'income_tax',
        'net'
    ];

    protected $casts = [
        'gross' => 'double',
        'tax_rate' => 'double',
        'income_tax' => 'double',
        'net' => 'double',
        'cnss' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
} 