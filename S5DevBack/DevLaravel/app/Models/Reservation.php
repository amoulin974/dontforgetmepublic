<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Define a one-to-many relationship with the Notification model.
     *
     * Each Reservation can be associated with one or more Notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Define a many-to-many relationship with the Creneau model.
     *
     * Each Reservation can be associated with one or more Creneaux.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function creneaux(): BelongsToMany
    {
        return $this->belongsToMany(Creneau::class, 'decomposer', 'idReservation', 'idCreneau')->withTimestamps();
    }

    /**
     * Get the users associated with the reservation via Affecter.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affecter_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idReservation','idUser')->withPivot('idCreneau')->withTimestamps();
    }

    /**
     * Get the creneaux associated with the reservation via Affecter.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affecter_creneaux(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idReservation','idCreneau')->withPivot('idUser')->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the User model via Effectuer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function effectuer_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'effectuer', 'idReservation', 'idUser')
                    ->withPivot('idActivite', 'dateReservation', 'typeNotif', 'numTel')
                    ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Activite model via Effectuer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function effectuer_activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'effectuer', 'idReservation', 'idActivite')
                    ->withPivot('idUser', 'dateReservation', 'typeNotif', 'numTel')
                    ->withTimestamps();
    }
}
