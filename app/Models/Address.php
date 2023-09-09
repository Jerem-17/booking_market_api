<?php

namespace App\Models;

use App\Models\Commande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nom',
        'latitude',
        'longitude',
    ];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
    protected $hidden = ['id', 'created_at', 'updated_at', 'user_id','deleted_at'];
}
