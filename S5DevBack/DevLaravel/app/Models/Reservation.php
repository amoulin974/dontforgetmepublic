<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    // ATTRIBUTS

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dateRdv',
        'heureDeb',
        'heureFin',
        'nbPersonnes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dateRdv' => 'datetime',
        'heureDeb' => 'datetime:H:i:s',
        'heureFin' => 'datetime:H:i:s',
        'nbPersonnes' => 'integer',
    ];

    // METHODES

    /**
     * Get the comments for the blog post.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Define a one-to-many relationship with the Creneau model.
     *
     * Each Reservation can be associated with one or more Creneau entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creneaux(): HasMany
    {
        return $this->hasMany(Creneau::class);
    }

    /**
     * Define a zero-to-many relationship with the User and Creneau model by Affecter.
     *
     * Each Reservation is associated with zero or more User and Creneau.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affecter_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idReservation','idUser')->withPivot('idCreneau')->withTimestamps();
    }
    public function affecter_creneaux(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idReservation','idCreneau')->withPivot('idUser')->withTimestamps();
    }
}
