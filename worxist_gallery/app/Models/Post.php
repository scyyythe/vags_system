<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /** @use HasFactory<\Database\Factories\PostFactory> */
    protected $primaryKey = 'post_id';

    protected $fillable = [
        'title',
        'description',
        'category',
        'image',
        'post_status',
    ];

    /**
     * Get the user who created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exhibits where this post is included.
     */
    public function exhibits()
    {
        return $this->belongsToMany(
            Exhibit::class,        // Related model
            'artworks_exhibits',   // Pivot table
            'post_id',             // Foreign key in pivot
            'exhibit_id'           // Related key in pivot
        );
    }
}
