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

    /**
     * Recursive relationship to get all children and their guest counts.
     */
    public function childrenRecursive()
    {
        // Eager load children and their guest counts recursively
        return $this->children()->with('childrenRecursive')->withCount('guests');
    }

    /**
     * Accessor to get the total number of guests in this folder and all sub-folders.
     * This requires the childrenRecursive relationship to be loaded.
     */
    public function getTotalGuestsCountAttribute()
    {
        // Start with the count of guests directly in this folder
        // The `guests_count` attribute must be loaded with withCount() in the query
        $count = $this->guests_count ?? 0;

        // If the recursive children relationship is loaded, iterate and add their counts
        if ($this->relationLoaded('childrenRecursive')) {
            foreach ($this->childrenRecursive as $child) {
                $count += $child->total_guests_count; // Recursively call the accessor for each child
            }
        }

        return $count;
    }
}
