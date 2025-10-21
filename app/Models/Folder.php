<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'info',
        'id_parent',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'id_parent');
    }

    public function guests()
    {
        return $this->hasMany(Guest::class, 'id_folder');
    }
}
