<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plage extends Model
{
    use HasFactory;

    // VARIABLES

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'heureDeb',
        'heureFin',
        'intervalle',
        'planTables',
        'entreprise_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'heureDeb' => 'datetime:H:i:s',
        'heureFin' => 'datetime:H:i:s',
        'intervalle' => 'datetime:H:i:s',
        'planTables' => 'array',
        'reservation_id' => 'integer',
    ];

    // MÉTHODES

    /**
     * Get the reservation associated with this notification.
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Get the reservation associated with this notification.
     */
    public function activites(): HasMany
    {
        return $this->hasMany(Activite::class);
    }
}
