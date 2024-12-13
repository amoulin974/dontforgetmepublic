<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ATTRIBUTS

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'delaiAvantNotif' => 'datetime',
            'superadmin' => 'integer',
        ];
    }

    // METHODES

    /**
     * Define a many-to-many relationship with the Entreprise and the Activite model by Travailler.
     *
     * Each User is associated with many Entreprise and many Activite.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function travailler_activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'travailler', 'idUser', 'idActivite')->withPivot('idEntreprise', 'statut')->withTimestamps();
    }
    public function travailler_entreprises(): BelongsToMany
    {
        return $this->belongsToMany(Entreprise::class, 'travailler', 'idUser', 'idEntreprise')->withPivot('idActivite', 'statut')->withTimestamps();
    }

    /**
     * Define a zero-to-many relationship with the Creneau by Etre Disponible.
     *
     * Each User is associated with zero or more Creneau.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function disponible_creneaux(): BelongsToMany
    {
        return $this->belongsToMany(Creneau::class, 'etre_disponible')->withTimestamps();
    }

    /**
     * Define a one-to-many relationship with the Creneau and Reservation model by Affecter.
     *
     * Each User is associated with one or more Creneau and Reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affecter_creneaux(): BelongsToMany
    {
        return $this->belongsToMany(Creneau::class, 'affecter', 'idUser', 'idCreneau')->withPivot('idReservation')->withTimestamps();
    }
    public function affecter_reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'affecter', 'idUser', 'idReservation')->withPivot('idCreneau')->withTimestamps();
    }

    /**
     * Define a one-to-many relationship with the Creneau and Reservation model by Affecter.
     *
     * Each User is associated with one or more Creneau and Reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function effectuer_reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'effectuer', 'idUser', 'idReservation')->withPivot('idReservation', 'dateReservation', 'typeNotif', 'numTel')->withTimestamps();
    }
    public function effectuer_activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'effectuer', 'idUser', 'idActivite')->withPivot('idCreneau', 'dateReservation', 'typeNotif', 'numTel')->withTimestamps();
    }
}
