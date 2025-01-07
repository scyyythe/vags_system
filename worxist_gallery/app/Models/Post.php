<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    protected $primaryKey = 'post_id';
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'image',
        'post_status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
