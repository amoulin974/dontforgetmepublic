<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @brief The Entreprise model represents a company.
 *
 * This model defines the attributes and relationships for a company. An Entreprise
 * may have multiple related activities, schedules, and users.
 */
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
        'typeRdv',
        'idCreateur'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publier'    => 'integer',
        'cheminImg'  => 'array',
        'typeRdv'    => 'array',
        'idCreateur' => 'integer'
    ];

    // METHODS

    /**
     * Define a one-to-many relationship with the Activite model.
     *
     * Each Entreprise can have zero or more activities.
     *
     * @return HasMany Returns a has-many relationship instance.
     */
    public function activites(): HasMany
    {
        return $this->hasMany(Activite::class, 'idEntreprise');
    }

    /**
     * Define a one-to-many relationship with the JourneeType model.
     *
     * Each Entreprise can have zero or more JourneeType entries.
     *
     * @return HasMany Returns a has-many relationship instance.
     */
    public function journeeTypes(): HasMany
    {
        return $this->hasMany(JourneeType::class, 'idEntreprise');
    }

    /**
     * Define a one-to-many relationship with the SemaineType model.
     *
     * Each Entreprise can have zero or more SemaineType entries.
     *
     * @return HasMany Returns a has-many relationship instance.
     */
    public function semaineTypes(): HasMany
    {
        return $this->hasMany(SemaineType::class, 'idEntreprise');
    }

    /**
     * Define a one-to-many relationship with the Plage model.
     *
     * Each Entreprise can have zero or more Plage entries.
     *
     * @return HasMany Returns a has-many relationship instance.
     */
    public function plages(): HasMany
    {
        return $this->hasMany(Plage::class);
    }

    /**
     * Define a many-to-many relationship with the Creneau model.
     *
     * Each Entreprise can be associated with one or more Creneaux (time slots) via the "ouvrir" pivot table.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function creneaux(): BelongsToMany
    {
        return $this->belongsToMany(Creneau::class, 'ouvrir', 'idEntreprise', 'idCreneau')
            ->withTimestamps();
    }

    /**
     * Get the activities associated with the Entreprise via the "travailler" pivot table.
     *
     * This relationship retrieves activities along with additional pivot data (idUser and statut)
     * that indicate the user's role within the enterprise.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function travailler_activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'travailler', 'idEntreprise', 'idActivite')
            ->withPivot('idUser', 'statut')
            ->withTimestamps();
    }

    /**
     * Get the users associated with the Entreprise via the "travailler" pivot table.
     *
     * This relationship retrieves users along with additional pivot data (idActivite and statut)
     * that indicate the user's role within the enterprise.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function travailler_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'travailler', 'idEntreprise', 'idUser')
            ->withPivot('idActivite', 'statut')
            ->withTimestamps();
    }
}
