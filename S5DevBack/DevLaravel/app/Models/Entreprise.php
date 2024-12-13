<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Define a one-to-many relationship with the Activite model.
     *
     * Each Entreprise can be associated with zero or more Activite entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activite(): HasMany
    {
        return $this->hasMany(Activite::class);
    }

    /**
     * Define a many-to-many relationship with the Activite and the User model by Travailler.
     *
     * Each Entreprise is associated with many Activite and many User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function travailler_activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'travailler', 'idEntreprise', 'idActivite')->withPivot('idUser', 'statut')->withTimestamps();
    }
    public function travailler_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'travailler', 'idEntreprise', 'idUser')->withPivot('idActivite', 'statut')->withTimestamps();
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
        return $this->hasMany(JourneeType::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function plages(): HasMany
    {
        return $this->hasMany(Plage::class);
    }

    /**
     * Define a one-to-many relationship with the Creneau model.
     *
     * Each Entreprise can be associated with one or more Creneau entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creneaux(): hasMany
    {
        return $this->hasMany(Creneau::class);
    }
}
