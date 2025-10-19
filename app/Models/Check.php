<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'status',
        'score',
        'breakdown',
        'summary',
        'fetched_title',
        'fetched_author',
        'fetched_published_at',
        'error_message',
    ];

    protected $casts = [
        'breakdown' => 'array',
        'fetched_published_at' => 'datetime',
    ];

    /**
     * Povezan korisnik (user koji je pokrenuo proveru)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
