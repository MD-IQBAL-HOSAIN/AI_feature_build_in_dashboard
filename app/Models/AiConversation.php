<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiConversation extends Model
{
    use HasFactory;

    protected $table = 'ai_conversations';

    protected $fillable = [
        'user_id',
        'title',
        'messages',
        'last_activity',
    ];

    protected $casts = [
        'messages' => 'array',
        'last_activity' => 'datetime',
    ];
}
