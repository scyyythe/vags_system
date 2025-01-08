<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibit extends Model
{
    /** @use HasFactory<\Database\Factories\ExhibitFactory> */
    use HasFactory;

    protected $primaryKey = 'exhibit_id';
    public $timestamps = false;

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
}
