<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'numTel',
        'email',
        'cheminImg',
        'publier',
        'typeRdv',
        'capaciteMax',
        'idCreateur'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publier' => 'integer',
        'cheminImg' => 'array',
        'typeRdv' => 'array',
        'idCreateur' => 'integer'
    ];


    // METHODES

    /**
     * Define a one-to-many relationship with the Activite model.
     *
     * Each Entreprise can be associated with zero or more Activite entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activites(): HasMany
    {
        return $this->hasMany(Activite::class, 'idEntreprise');
    }

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
        return $this->hasMany(SemaineType::class);
    }

    /**
     * Define a one-to-many relationship with the Plage model.
     *
     * Each Entreprise can be associated with zero or more Plage entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plages(): HasMany
    {
        return $this->hasMany(Plage::class);
    }

    /**
     * Define a many-to-many relationship with the Creneau model.
     *
     * Each entreprise can be associated with one or more creneaux.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function creneaux(): BelongsToMany
    {
        return $this->belongsToMany(Creneau::class, 'ouvrir', 'idEntreprise', 'idCreneau')->withTimestamps();
    }

    /**
     * Get the activites associated with the entreprise via Travailler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function travailler_activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'travailler', 'idEntreprise', 'idActivite')->withPivot('idUser', 'statut')->withTimestamps();
    }

    /**
     * Get the users associated with the entreprise via Travailler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function travailler_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'travailler', 'idEntreprise', 'idUser')->withPivot('idActivite', 'statut')->withTimestamps();
    }
}
