<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Problem extends Model
{
    protected $primaryKey = 'problem_handle';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['problem_handle', 'link','website', 
                            'title', 'timelimit', 'memorylimit', 
                            'statement', 'testcases', 'notes'];

    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class, 'problem_tag', 'problem_id', 'tag_id'); }
    public function sheets(): BelongsToMany { return $this->belongsToMany(Sheet::class, 'sheet_problem', 'problem_id', 'sheet_id'); }
    public function topicItems(): HasMany { return $this->hasMany(TopicItem::class, 'problem_id'); }
    public function submissions(): HasMany { return $this->hasMany(Submiton::class, 'problem_id'); }
    public function solutions(): HasMany { return $this->hasMany(Solution::class, 'problem_id'); }
}