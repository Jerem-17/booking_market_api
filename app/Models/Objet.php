<?php

namespace App\Models;

use App\Models\Commande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Objet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'commande_id',
        'quantite',
        'article',
        'prix_unitaire',
        'quantite_final',
        'pu_final'
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }
    protected $hidden = [ 'created_at', 'updated_at', 'commande_id','deleted_at'];
}
