<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activite extends Model
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
        'duree'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duree' => 'datetime:H:i:s'
    ];


    // METHODES 
    
    /**
     * Define a one-to-one relationship with the Entreprise model.
     *
     * Each Activite is associated with exactly one Enreprise.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }


    /**
     * Define a many-to-many relationship with the Entreprise and the User model by Travailler.
     *
     * Each Activite is associated with many Entreprise and many User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function travailler_entreprises(): BelongsToMany
    {
        return $this->belongsToMany(Entreprise::class, 'travailler', 'idActivite', 'idEntreprise')->withPivot('idUser', 'statut')->withTimestamps();
    }
    public function travailler_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'travailler', 'idActivite', 'idUser')->withPivot('idEntreprise', 'statut')->withTimestamps();
    }
}
