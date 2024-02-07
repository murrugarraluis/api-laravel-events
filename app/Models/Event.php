<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'description',
        'poster',
        'date',
        'time',
        'category_id'
    ];
    protected $with = ['category'];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
