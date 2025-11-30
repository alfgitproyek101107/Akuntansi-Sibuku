<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppLog extends Model
{
    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'report_type',
        'phone_number',
        'status',
        'message',
        'response_data',
        'error_message',
        'retry_count',
        'sent_at',
        'user_id',
        'branch_id',
    ];

    protected $casts = [
        'response_data' => 'array',
        'sent_at' => 'datetime',
        'retry_count' => 'integer',
    ];

    /**
     * Get the user that owns the log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch that owns the log.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Scope for successful logs
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed logs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for specific report type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('report_type', $type);
    }
}
