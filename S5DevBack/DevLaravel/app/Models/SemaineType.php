<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @brief The SemaineType model represents a week schedule type.
 *
 * This model defines the attributes and relationships for a SemaineType.
 * A SemaineType is associated with a single enterprise and can be linked to
 * multiple day schedule (JourneeType) entries via a pivot table.
 */
class SemaineType extends Model
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
        'planning'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'planning' => 'array'
    ];

    // METHODS

    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each SemaineType is associated with exactly one Entreprise.
     *
     * @return BelongsTo Returns a belongs-to relationship instance linking the week schedule type to an enterprise.
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Define a many-to-many relationship with the JourneeType model.
     *
     * Each SemaineType can be associated with zero or more JourneeType entries.
     * This relationship is established via the "constituer" pivot table.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance linking SemaineType to JourneeType.
     */
    public function journeeTypes(): BelongsToMany
    {
        return $this->belongsToMany(JourneeType::class, 'constituer', 'idSemaineType', 'idJourneeType');
    }
}
