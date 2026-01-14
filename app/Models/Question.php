<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title',
        'content',
        'status',
        'views',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getScore()
    {
        $up = $this->votes()->where('value', 'up')->count();
        $down = $this->votes()->where('value', 'down')->count();
        return $up - $down;
    }
}