<?php

namespace App\Models;

use App\Models\Boutique;
use App\Models\CategorieVitrine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductShop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'boutique_id',
        'article',
        'categorievitrine_id',
        'prix',
        'dm',
    ];

    public function categorie() : BelongsTo
    {
        return $this->belongsTo(CategorieVitrine::class);
    }

    public function boutique() : BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }
}
