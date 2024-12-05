<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
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
        'siren',
        'adresse',
        'metier',
        'description',
        'type',
        'numTel',
        'email',
        'cheminImg',
        'publier',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publier' => 'integer',
        'cheminImg' => 'array',
    ];


    // METHODES

    /**
     * Define a one-to-many relationship with the JourneeType model.
     *
     * Each Entreprise can be associated with zero or more JourneeType entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function journeeTypes(): HasMany
    {
        return $this->hasMany(JourneeType::class);
    }

    /**
     * Define a one-to-many relationship with the SemaineType model.
     *
     * Each Entreprise can be associated with zero or more SemaineType entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function semaineTypes(): HasMany
    {
        return $this->hasMany(JourneeType::class);
    }
}
