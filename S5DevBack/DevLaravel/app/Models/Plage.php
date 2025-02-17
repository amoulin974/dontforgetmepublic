<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Entreprise;
use App\Models\Activite;
use App\Models\User;

/**
 * @brief The Plage model represents a time slot.
 *
 * This model defines the attributes and relationships for a time slot (Plage).
 * A Plage is associated with an enterprise, may have many activities, and can be linked
 * to employees via a pivot table.
 */
class Plage extends Model
{
    use HasFactory;

    // VARIABLES

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'datePlage',
        'heureDeb',
        'heureFin',
        'interval',
        'planTables',
        'entreprise_id', // To allow insertion
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datePlage'     => 'datetime',
        'heureDeb'      => 'string',
        'heureFin'      => 'string',
        'interval'      => 'string',
        'planTables'    => 'array',
        'entreprise_id' => 'integer',
    ];

    // METHODS

    /**
     * Define a many-to-one relationship with the Entreprise model.
     *
     * Each Plage is associated with exactly one Entreprise.
     *
     * @return BelongsTo Returns a belongs-to relationship instance linking this time slot to an enterprise.
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Define a many-to-many relationship with the Activite model via the "composer" pivot table.
     *
     * This relationship associates a Plage with one or more Activite entries.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance.
     */
    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'composer', 'idPlage', 'idActivite')
            ->withTimestamps();
    }

    /**
     * Define a many-to-many relationship with the User model via the "placer" pivot table.
     *
     * This relationship associates a Plage with employees.
     *
     * @return BelongsToMany Returns a belongs-to-many relationship instance linking time slots to users.
     */
    public function employes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'placer', 'idPlage', 'idUser')
            ->withTimestamps();
    }
}
