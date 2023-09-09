<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retour extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'commande_id'
    ];
    protected $hidden = ['updated_at', 'commande_id','deleted_at'];
    function objettemps() : HasMany
    {
       return $this->hasMany(ObjetTemp::class);
    }

    function commande() : BelongsTo
    {
       return $this->belongsTo(Commande::class);
    }
}
