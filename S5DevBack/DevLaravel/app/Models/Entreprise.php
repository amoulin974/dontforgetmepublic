<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'siren',
        'adresse',
        'metier',
        'description',
        'type',
        'numTel',
        'email',
        'cheminImg',
        'publier',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publier' => 'integer',
        'cheminImg' => 'array',
    ];

    /**
     * Get the comments for the blog post.
     */
    public function plages(): HasMany
    {
        return $this->hasMany(Plage::class);
    }
}
