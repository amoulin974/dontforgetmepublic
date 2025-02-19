<?php
/**
 * @file Activite.php
 * @brief Eloquent model to manage activities in the Laravel application.
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @class Activite
 * @brief Represents an activity entity in the system.
 *
 * This model handles the relationship with other models such as Entreprise, Plage, User, and Reservation.
 */
class Activite extends Model
{
    use HasFactory;

    // VARIABLES
    /**
     * Attributes that are mass assigned.
     * 
     * @var array<int, string> $fillable
     */
    protected $fillable = [
        'libelle',
        'duree',
        'nbrPlaces',
        'idEntreprise'
    ];

    /**
     * Attributes that can be mass assigned.
     * 
     * @var array<int, string> $fillable
     */
    protected $casts = [
        'duree' => 'string'
    ];


    // METHODS 
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

    /**
     * Formats the activity duration into a human-readable string.
     *
     * @return string The formatted duration string.
     */
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
