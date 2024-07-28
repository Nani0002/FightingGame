<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'win',
        'history',
        'place_id',
        'user_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'win' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)->withPivot(["enemy_hp", "hero_hp"]);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($contest) {
            $contest->characters->each(function ($character) use ($contest) {
                $contest->characters()->updateExistingPivot($character->id, ['deleted_at' => now()]);
            });
        });
    }
}
