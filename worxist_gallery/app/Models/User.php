<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function exhibits()
    {
        return $this->hasMany(Exhibit::class);
    }
    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'collaborators', 'exhibit_id', 'user_id');
    }

    // Admin
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    //Organizer
    public function isOrganizer()
    {
        return $this->role === 'Organizer';
    }

    // Interactions
    // liikes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    //favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function favoritedPosts()
    {
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id');
    }

    //saved_posts
    public function saved_post()
    {
        return $this->hasMany(Saved::class);
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved', 'user_id', 'post_id');
    }

    //comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // followres
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }


    //notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    //like exhibti
    // User Model
    public function exhibitlike()
    {
        return $this->hasMany(LikeExhibit::class);
    }
    public function exhibitLiked()
    {
        return $this->hasMany(LikeExhibit::class, 'exhibitlike', 'user_id', 'exhibit_id');
    }
}
