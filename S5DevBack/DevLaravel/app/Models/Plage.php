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
    ];

    // MÉTHODES

    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each Plage is associated with exactly one Entreprise.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Define a many-to-many relationship with the Activite model via Composer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'composer', 'idPlage', 'idActivite')->withTimestamps();
    }
}
