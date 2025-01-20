<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'datePlage',
        'heureDeb',
        'heureFin',
        'interval',
        'planTables',
        'entreprise_id', // Pour permettre l'insertion
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datePlage' => 'datetime',
        'heureDeb' => 'string',
        'heureFin' => 'string',
        'interval' => 'string',
        'planTables' => 'array',
        'entreprise_id' => 'integer',
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
