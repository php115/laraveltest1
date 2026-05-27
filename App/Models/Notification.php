<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property string $status
 * @property string $channel
 */
class Notification extends Model
{
    use HasFactory;

    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SENT = 'sent';
    public const STATUS_ERROR = 'error';

    protected $fillable = [
        'user_id',
        'text',
        'status',
        'channel',
    ];
}
