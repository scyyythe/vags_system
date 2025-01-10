<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';  // If your primary key is not the default 'id'
    use Notifiable;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_read',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relation for 'notifiable' (e.g., post, artwork, etc.)
    public function notifiable()
    {
        return $this->morphTo();
    }
}
