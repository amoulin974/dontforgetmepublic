<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        /* 'reservation_id', */
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delai' => 'datetime:H:i:s',
        'etat' => 'integer',
        /* 'reservation_id' => 'integer', */
    ];

    // MÉTHODES

    /**
     * Define a many-to-one relationship with the Reservation model.
     *
     * Each notification is associated with a single reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservations(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
