<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dateRdv' => 'datetime',
        'heureDeb' => 'datetime:H:i:s',
        'heureFin' => 'datetime:H:i:s',
        'nbPersonnes' => 'integer',
    ];
}
