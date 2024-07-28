<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'imagename',
        'imagename_hash',
    ];

    public function Contests(): HasMany
    {
        return $this->hasMany(Contest::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($place) {
            $place->contests->each(function ($contest) {
                $contest->delete();
            });
        });
    }
}
