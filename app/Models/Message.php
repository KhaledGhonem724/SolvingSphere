<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $fillable = ['content', 'sender_id', 'reciever_id', 'sent_at'];

    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'reciever_id'); }
}