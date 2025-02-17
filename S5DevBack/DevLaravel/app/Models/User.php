<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @brief The User model represents an application user.
 *
 * This model handles the authentication details, attributes, and relationships
 * associated with a user. It includes methods to retrieve related activities,
 * enterprises, reservations, and time slots.
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ATTRIBUTES

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'numTel',
        'nom',
        'prenom',
        'typeNotif',
        'delaiAvantNotif',
        'superadmin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast to native types.
     *
     * @return array<string, string> An associative array mapping attribute names to their cast types.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            // 'delaiAvantNotif' => 'datetime',
            'delaiAvantNotif'   => 'string',
            'superadmin'        => 'integer',
        ];
    }

    // METHODS

    /**
     * Get the activities associated with the user via the "travailler" pivot table.
     *
     * This relationship retrieves the activities in which the user is involved, along with additional
     * pivot data such as the enterprise ID and the user's status.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function travailler_activites(): BelongsToMany
    {
        return $this->belongsToMany(
            Activite::class,
            'travailler',
            'idUser',
            'idActivite'
        )->withPivot('idEntreprise', 'statut')->withTimestamps();
    }

    /**
     * Get the enterprises associated with the user via the "travailler" pivot table.
     *
     * This relationship retrieves the enterprises where the user is involved along with additional
     * pivot data such as the activity ID and the user's status.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function travailler_entreprises(): BelongsToMany
    {
        return $this->belongsToMany(
            Entreprise::class,
            'travailler',
            'idUser',
            'idEntreprise'
        )->withPivot('idActivite', 'statut')->withTimestamps();
    }

    /**
     * Define a zero-to-many relationship with the Creneau model via the "etre_disponible" pivot table.
     *
     * This relationship retrieves the time slots (creneaux) for which the user is available.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function disponible_creneaux(): BelongsToMany
    {
        return $this->belongsToMany(
            Creneau::class,
            'etre_disponible',
            'idUser',
            'idCreneau'
        )->withTimestamps();
    }

    /**
     * Get the reservations associated with the user via the "affecter" pivot table.
     *
     * This relationship retrieves reservations where the user is assigned (affected).
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function affecter_reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            Reservation::class,
            'affecter',
            'idUser',
            'idReservation'
        )->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Reservation model via the "effectuer" pivot table.
     *
     * This relationship represents the reservations that the user has performed,
     * including additional pivot data such as the reservation ID, date, notification type, and phone number.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function effectuer_reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            Reservation::class,
            'effectuer',
            'idUser',
            'idReservation'
        )->withPivot('idReservation', 'dateReservation', 'typeNotif', 'numTel')->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Activite model via the "effectuer" pivot table.
     *
     * This relationship links the user to activities they have performed, including additional pivot data such as
     * the time slot ID, date of reservation, notification type, and phone number.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function effectuer_activites(): BelongsToMany
    {
        return $this->belongsToMany(
            Activite::class,
            'effectuer',
            'idUser',
            'idActivite'
        )->withPivot('idCreneau', 'dateReservation', 'typeNotif', 'numTel')->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Plage model via the "placer" pivot table.
     *
     * This relationship links the user to time slots (plages) via the "placer" pivot table.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function plages(): BelongsToMany
    {
        return $this->belongsToMany(
            Plage::class,
            'placer',
            'idActivite',
            'idUser'
        )->withTimestamps();
    }
}
