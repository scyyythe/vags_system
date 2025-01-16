<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeExhibit extends Model
{
    use HasFactory;

    protected $primaryKey = 'like_id';
    protected $fillable = ['user_id', 'exhibit_id'];
    protected $table = 'exhibit_likes';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exhibit()
    {
        return $this->belongsTo(Exhibit::class, 'exhibit_id');
    }
}
