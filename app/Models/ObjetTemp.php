<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjetTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'retour_id',
        'objet_id',
        'prix',
        'quantite',
    ];
    protected $hidden = ['created_at', 'updated_at','deleted_at'];

    function objettemps() : BelongsTo
    {
       return $this->belongsTo(ObjetTemp::class);
    }
}
