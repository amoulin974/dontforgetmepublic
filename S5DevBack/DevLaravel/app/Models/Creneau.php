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
     * Define a zero-to-many relationship with the Entreprise model.
     *
     * Each Creneau can be associated with zero or more Entreprise entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entreprises(): belongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Define a zero-to-many relationship with the Reservation model.
     *
     * Each Creneau can be associated with zero or more Reservation entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservations(): belongsTo
    {
        return $this->belongsTo(reservation::class);
    }

    /**
     * Define a zero-to-many relationship with the User model by Etre Disponible.
     *
     * Each Creneau is associated with zero or more User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function disponible_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'etre_disponible')->withTimestamps();
    }

    /**
     * Define a zero-to-many relationship with the User and Reservation model by Affecter.
     *
     * Each Creneau is associated with zero or more User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affecter_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idCreneau','idUser')->withPivot('idReservation')->withTimestamps();
    }
    public function affecter_reservations(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idCreneau','idReservation')->withPivot('idUser')->withTimestamps();
    }
}
