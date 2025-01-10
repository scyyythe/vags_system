<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibit extends Model
{
    /** @use HasFactory<\Database\Factories\ExhibitFactory> */
    use HasFactory;

    protected $primaryKey = 'exhibit_id';
    protected $table = 'exhibits';

    protected $fillable = [
        'exhibit_title',
        'exhibit_description',
        'exhibit_date',
        'exhibit_type',
        'exhibit_status',
        'accepted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'artworks_exhibits', 'exhibit_id', 'post_id');
    }

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class, 'exhibit_id');
    }
    public function artworks()
    {
        return $this->hasMany(ArtworksExhibit::class, 'exhibit_id', 'exhibit_id');
    }
}
