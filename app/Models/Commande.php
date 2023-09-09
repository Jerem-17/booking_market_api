<?php

namespace App\Models;

use App\Models\User;
use App\Models\Objet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'user_id',
        'address_id',
        'prix_total',
        'boutique_id',
        'statut_paiement',
    ];

    protected $hidden = ['updated_at', 'user_id','deleted_at','address_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function boutiques(): BelongsToMany{
        return  $this->belongsToMany(Boutique::class);
    }

    public function objets(): HasMany
    {
        return $this->hasMany(Objet::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function retours(): HasMany
    {
        return $this->hasMany(Retour::class);
    }
}
