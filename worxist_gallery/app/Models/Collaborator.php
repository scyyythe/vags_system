<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'exhibit_id',
        'user_id',
    ];
    public $timestamps = false;
    // Define the relationship back to the exhibit
    public function exhibit()
    {
        return $this->belongsTo(Exhibit::class, 'exhibit_id');
    }

    // Define the relationship to the user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }
}
