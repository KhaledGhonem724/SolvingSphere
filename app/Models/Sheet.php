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
        return $this->belongsToMany(
            Problem::class,
            'sheet_problem',     // Pivot table name
            'sheet_id',          // Foreign key on pivot table for Sheet
            'problem_id'         // Foreign key on pivot table for Problem
        )->withTimestamps();
    }
}

