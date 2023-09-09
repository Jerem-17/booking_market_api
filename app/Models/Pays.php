<?php

namespace App\Models;

use App\Models\Boutique;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pays extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom'
    ];

    public function boutiques() : HasMany
    {
        return $this->hasMany(Boutique::class);
    }
}
