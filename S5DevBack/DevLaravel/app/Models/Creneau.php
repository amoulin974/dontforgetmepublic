<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Creneau extends Model
{
    use HasFactory;

    // VARIABLE

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'dateC',
        'heureDeb',
        'heureFin'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dateC' => 'datetime',
        'heureDeb' => 'datetime:H:i:s',
        'heureFin' => 'datetime:H:i:s'
    ];

    // METHODES
    /**
     * Define a many-to-many relationship with the Entreprise model.
     *
     * Each creneau can be associated with zero or more entreprises.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entreprises(): BelongsToMany
    {
        return $this->belongsToMany(Entreprise::class, 'ouvrir', 'idCreneau', 'idEntreprise')->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Reservation model.
     *
     * Each creneau can be associated with zero or more reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'decomposer', 'idCreneau', 'idReservation')->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the User model via Etre Disponible.
     *
     * Each user can be associated with zero or more creneaux.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function disponible_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'etre_disponible', 'idCreneau', 'idUser')->withTimestamps();
    }

    /**
     * Get the users associated with the creneau via Affecter.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affecter_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idCreneau','idUser')->withPivot('idReservation')->withTimestamps();
    }

    /**
     * Get the users associated with the creneau via Affecter.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affecter_reservations(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idCreneau','idReservation')->withPivot('idUser')->withTimestamps();
    }
}
