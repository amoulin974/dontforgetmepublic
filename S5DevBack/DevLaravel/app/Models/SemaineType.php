<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'planning' => 'array'
    ];


    // METHODES 

    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each SemaineType is associated with exactly one Entreprise.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Define a many-to-many relationship with the JourneeType model.
     *
     * Each SemaineType is associated with zero or more JourneeType entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function journeeTypes(): BelongsToMany
    {
        return $this->belongsToMany(JourneeType::class, 'constituer', 'idSemaineType', 'idJourneeType');
    }
}
