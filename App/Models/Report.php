<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    protected $fillable = ['user_id', 'status', 'file_path', 'period_from', 'period_to'];

    protected $casts = [
        'period_from' => 'datetime',
        'period_to' => 'datetime',
    ];
}

