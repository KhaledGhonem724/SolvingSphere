<?php

// app/Models/Sheet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    protected $fillable = [
        'title',
        'description',
        'visibility',
        'owner_id',
        'share_token',
    ];

   
    public function problems()
    {
        return $this->belongsToMany(Problem::class)->withTimestamps()->as('sheet_problem');
    }
}

