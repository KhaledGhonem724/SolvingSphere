<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Problem extends Model
{
    use HasFactory;
    
    protected $fillable = ['problem_handle', 'link','website', 
                            'title', 'timelimit', 'memorylimit', 
                            'statement', 'testcases', 'notes'];

    public function tags(): BelongsToMany { 
        return $this->belongsToMany(Tag::class, 'problem_tag', 'problem_id', 'tag_id'); 
    }
    
    public function sheets(): BelongsToMany { return $this->belongsToMany(Sheet::class, 'sheet_problem', 'problem_id', 'sheet_id'); }
    public function topicItems(): HasMany { return $this->hasMany(TopicItem::class, 'problem_id'); }
    
    public function submissions(): HasMany {
        return $this->hasMany(Submission::class, 'problem_id');
    }
    
    public function solutions(): HasMany { 
        return $this->hasMany(Solution::class, 'problem_id'); 
    }

    // very inportant to user friendly urls using problems handle 
    public function getRouteKeyName(): string
    {
        return 'problem_handle';
    }
}
