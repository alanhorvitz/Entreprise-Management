<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason'
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
} 