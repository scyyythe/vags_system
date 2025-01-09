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
        return $this->hasMany(Like::class, 'user_id');
    }
    //favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }
    //saved_posts
    public function saved_post()
    {
        return $this->hasMany(Saved::class, 'user_id');
    }
}
