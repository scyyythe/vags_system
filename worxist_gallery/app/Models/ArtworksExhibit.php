<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtworksExhibit extends Model
{
    use HasFactory;

    protected $fillable = ['exhibit_id', 'post_id'];
    public $timestamps = false;

    /**
     * Get the exhibit associated with this record.
     */
    public function exhibit()
    {
        return $this->belongsTo(Exhibit::class, 'exhibit_id', 'exhibit_id');
    }

    /**
     * Get the post (artwork) associated with this record.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
