<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nickname',
        'phone',
        'info',
        'id_user',
        'id_folder',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'id_folder');
    }
}
