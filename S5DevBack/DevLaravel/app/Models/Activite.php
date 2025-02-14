<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @class Activite
 * @brief Classe représentant une activité dans l'application.
 *
 * Cette classe gère les informations liées aux activités.
 */
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
        'duree',
        'idEntreprise'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'duree' => 'datetime:H:i:s'
        'duree' => 'string'
    ];


    // METHODES 
    
    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each Activite is associated with exactly one Entreprise.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'idEntreprise');
    }

    /**
     * Define a many-to-many relationship with the Plage model via Composer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plages(): BelongsToMany
    {
        return $this->belongsToMany(Plage::class, 'composer', 'idActivite', 'idPlage')->withTimestamps();
    }

    /**
     * Get the entreprises associated with the activite via Travailler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function travailler_entreprises(): BelongsToMany
    {
        return $this->belongsToMany(Entreprise::class, 'travailler', 'idActivite', 'idEntreprise')->withPivot('idUser', 'statut')->withTimestamps();
    }

    /**
     * Get the users associated with the activite via Travailler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function travailler_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'travailler', 'idActivite', 'idUser')->withPivot('idEntreprise', 'statut')->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the User model via Effectuer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function effectuer_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'effectuer', 'idActivite', 'idUser')
                    ->withPivot('idReservation', 'dateReservation', 'typeNotif', 'numTel')
                    ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Reservation model via Effectuer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function effectuer_reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'effectuer', 'idActivite', 'idReservation')
                    ->withPivot('idUser', 'dateReservation', 'typeNotif', 'numTel')
                    ->withTimestamps();
    }


    public function getFormattedDureeAttribute()
    {
        $timeParts = explode(':', $this->duree);
        $hours = intval($timeParts[0]);
        $minutes = intval($timeParts[1]);

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} heure" . ($hours > 1 ? 's' : '') . " {$minutes} minutes";
        } elseif ($hours > 0) {
            return "{$hours} heure" . ($hours > 1 ? 's' : '');
        } else {
            return "{$minutes} minutes";
        }
    }

}
