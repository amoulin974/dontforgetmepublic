<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @brief The Activite model represents an activity associated with an enterprise.
 *
 * This model defines the attributes and relationships for an activity.
 * It supports relationships to an enterprise, time slots (plages), users,
 * and reservations via various pivot tables.
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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'duree' => 'datetime:H:i:s'
        'duree' => 'string'
    ];

    // METHODS

    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each Activite is associated with exactly one Entreprise.
     *
     * @return BelongsTo Returns a belongs-to relationship instance.
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'idEntreprise');
    }

    /**
     * Define a many-to-many relationship with the Plage model via the "composer" pivot table.
     *
     * This relationship represents the time slots (plages) associated with the activity.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function plages(): BelongsToMany
    {
        return $this->belongsToMany(Plage::class, 'composer', 'idActivite', 'idPlage')->withTimestamps();
    }

    /**
     * Get the enterprises associated with the activity via the "travailler" pivot table.
     *
     * The pivot table contains additional information such as the user ID and the user's status.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function travailler_entreprises(): BelongsToMany
    {
        return $this->belongsToMany(Entreprise::class, 'travailler', 'idActivite', 'idEntreprise')
            ->withPivot('idUser', 'statut')
            ->withTimestamps();
    }

    /**
     * Get the users associated with the activity via the "travailler" pivot table.
     *
     * This relationship provides access to users with additional pivot data like the enterprise ID and status.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function travailler_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'travailler', 'idActivite', 'idUser')
            ->withPivot('idEntreprise', 'statut')
            ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the User model via the "effectuer" pivot table.
     *
     * This relationship represents the users who performed the activity and includes additional pivot data.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function effectuer_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'effectuer', 'idActivite', 'idUser')
            ->withPivot('idReservation', 'dateReservation', 'typeNotif', 'numTel')
            ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the Reservation model via the "effectuer" pivot table.
     *
     * This relationship represents the reservations linked to the activity along with pivot data.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function effectuer_reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'effectuer', 'idActivite', 'idReservation')
            ->withPivot('idUser', 'dateReservation', 'typeNotif', 'numTel')
            ->withTimestamps();
    }

    /**
     * Get the formatted duration attribute.
     *
     * This accessor converts the duration stored as a string (in "H:i:s" format) into a human-readable
     * format such as "X hours Y minutes", "X hours", or "Y minutes".
     *
     * @return string The formatted duration string.
     */
    public function getFormattedDureeAttribute(): string
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
