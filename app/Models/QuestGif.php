<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestGif extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_quest',
        'price',
        'id_user',
        'info',
        'description',
        'date',
    ];

    /**
     * Get the guest associated with the quest gif.
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'id_quest');
    }

    /**
     * Get the user who owns the quest gif.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
