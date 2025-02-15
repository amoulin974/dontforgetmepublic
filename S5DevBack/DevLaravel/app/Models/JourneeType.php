<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @brief The JourneeType model represents a type of day schedule.
 *
 * This model defines the attributes and relationships for a day type schedule,
 * including its planning details and its relationships with an enterprise and week types.
 */
class JourneeType extends Model
{
    use HasFactory;

    // VARIABLES

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'planning',
        'idEntreprise'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'planning' => 'array',
        'idEntreprise' => 'integer'
    ];

    // METHODS

    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each JourneeType is associated with exactly one Entreprise.
     *
     * @return BelongsTo Returns a belongs-to relationship instance linking this day type to an enterprise.
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Define a many-to-many relationship with the SemaineType model.
     *
     * Each JourneeType can be associated with zero or more SemaineType entries.
     * This relationship is defined via the "constituer" pivot table.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function semaineTypes(): BelongsToMany
    {
        return $this->belongsToMany(SemaineType::class, 'constituer', 'idJourneeType', 'idSemaineType');
    }
}
