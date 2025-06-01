<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        "titre",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function conversations() {
        return $this->hasMany(Conversation::class);
    }

}
