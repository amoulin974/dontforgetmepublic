<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @brief The Notification model represents a notification related to a reservation.
 *
 * This model defines the attributes, type casting, and relationships for notifications.
 * A notification is associated with a single reservation and contains details such as its
 * category, delay before sending, status, and content.
 */
class Notification extends Model
{
    use HasFactory;

    // VARIABLES

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'categorie',
        'delai',
        'etat',
        'contenu',
        'reservation_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delai'          => 'datetime:H:i:s',
        'etat'           => 'integer',
        'reservation_id' => 'integer',
    ];

    // METHODS

    /**
     * Define a many-to-one relationship with the Reservation model.
     *
     * Each notification belongs to a single reservation.
     *
     * @return BelongsTo Returns a belongs-to relationship instance linking the notification to a reservation.
     */
    public function reservations(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
