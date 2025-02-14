<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'planning' => 'array',
        'idEntreprise' => 'integer'
    ];


    // METHODES

    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each JourneeType is associated with exactly one Entreprise.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Define a many-to-many relationship with the SemaineType model.
     *
     * Each JourneeType is associated with zero to or more SemaineType entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function semaineTypes(): BelongsToMany
    {
        return $this->belongsToMany(SemaineType::class, 'constituer', 'idJourneeType', 'idSemaineType');
    }
}
