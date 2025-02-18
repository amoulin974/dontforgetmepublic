<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Notification;
use App\Models\Creneau;
use App\Models\User;
use App\Models\Activite;

/**
 * @brief The Reservation model represents a reservation made by a user.
 *
 * This model defines the attributes and relationships for a reservation. A reservation
 * includes details such as the appointment date, start time, end time, and the number of people.
 * It also defines relationships with notifications, time slots (creneaux), and users (via pivot tables).
 */
class Reservation extends Model
{
    use HasFactory;

    // ATTRIBUTES

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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dateRdv'     => 'datetime',
        // 'heureDeb' and 'heureFin' are stored as strings for flexibility in formatting.
        'heureDeb'    => 'string',
        'heureFin'    => 'string',
        'nbPersonnes' => 'integer',
    ];

    // METHODS

    /**
     * Define a one-to-many relationship with the Notification model.
     *
     * Each Reservation can have one or more Notifications associated with it.
     *
     * @return HasMany Returns a has-many relationship instance linking a reservation to its notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Define a many-to-many relationship with the Creneau model.
     *
     * Each Reservation can be associated with one or more Creneaux (time slots)
     * via the "decomposer" pivot table.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance linking reservations to creneaux.
     */
    public function creneaux(): BelongsToMany
    {
        return $this->belongsToMany(Creneau::class, 'decomposer', 'idReservation', 'idCreneau')
            ->withTimestamps();
    }

    /**
     * Get the users associated with the reservation via the "affecter" pivot table.
     *
     * This relationship provides access to the users affected by the reservation.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance linking reservations to users.
     */
    public function affecter_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'affecter', 'idReservation', 'idUser')
            ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the User model via the "effectuer" pivot table.
     *
     * This relationship represents the users who performed the reservation action.
     * Additional pivot fields include the activity ID, date of reservation, type of notification, and user phone number.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance linking reservations to users.
     */
    public function effectuer_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'effectuer', 'idReservation', 'idUser')
            ->withPivot('idActivite', 'dateReservation', 'typeNotif', 'numTel')
            ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Activite model via the "effectuer" pivot table.
     *
     * This relationship links reservations to activities. The pivot table also includes
     * additional information such as the user ID, the date the reservation was made,
     * the type of notification, and the user's phone number.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance linking reservations to activities.
     */
    public function effectuer_activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'effectuer', 'idReservation', 'idActivite')
            ->withPivot('idUser', 'dateReservation', 'typeNotif', 'numTel')
            ->withTimestamps();
    }
}
