<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $guarded = [];
    public const manager_route =  'chat_messages';
}
