<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_name',
        'user_email',
        'message',
        'role',
        'response',
        'ai_model',
        'tokens_used',
        'is_training_data',
        'metadata',
    ];

    protected $casts = [
        'is_training_data' => 'boolean',
        'metadata' => 'array',
        'tokens_used' => 'integer',
    ];

    public function scopeBySession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeRecent($query, int $limit = 50)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeTrainingData($query)
    {
        return $query->where('is_training_data', true);
    }
}
