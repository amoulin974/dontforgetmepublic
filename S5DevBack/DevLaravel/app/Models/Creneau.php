<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @brief The Creneau model represents a time slot.
 *
 * This model defines the attributes and relationships for a time slot (creneau).
 * A time slot can be associated with one or more enterprises, reservations, and users.
 */
class Creneau extends Model
{
    use HasFactory;

    // VARIABLES

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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dateC'    => 'datetime',
        'heureDeb' => 'datetime:H:i:s',
        'heureFin' => 'datetime:H:i:s'
    ];

    // METHODS

    /**
     * Define a many-to-many relationship with the Entreprise model.
     *
     * Each creneau can be associated with zero or more enterprises.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function entreprises(): BelongsToMany
    {
        return $this->belongsToMany(Entreprise::class, 'ouvrir', 'idCreneau', 'idEntreprise')
            ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Reservation model.
     *
     * Each creneau can be associated with zero or more reservations.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'decomposer', 'idCreneau', 'idReservation')
            ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the User model via the "etre_disponible" pivot table.
     *
     * Each user can be associated with zero or more creneaux (time slots).
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function disponible_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'etre_disponible', 'idCreneau', 'idUser')
            ->withTimestamps();
    }
}
