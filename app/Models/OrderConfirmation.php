<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderConfirmation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'confirmed_by',
        'product_name',
        'client_name',
        'client_number',
        'client_address',
        'confirmation_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'confirmation_date' => 'datetime',
    ];

    /**
     * Validation rules for order confirmation
     */
    public static $rules = [
        'project_id' => 'required|exists:projects,id',
        'confirmed_by' => 'required|exists:employees,id',
        'product_name' => 'required|string|max:255',
        'client_name' => 'required|string|max:255',
        'client_number' => 'required|string|max:20',
        'client_address' => 'required|string',
        'confirmation_date' => 'required|date',
        'status' => 'required|in:confirmed,cancelled,pending',
        'notes' => 'nullable|string',
    ];

    /**
     * Get the project that owns the confirmation.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the employee who confirmed the order.
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'confirmed_by');
    }
}
