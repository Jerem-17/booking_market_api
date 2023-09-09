<?php

namespace App\Models;

use App\Models\Boutique;
use App\Models\CategorieBouf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prix',
        'description',
        'boutique_id',
        'suplement',
        'categoriebouf_id',
    ];

    public function boutique() : BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }

    public function categoriebouf() : BelongsTo
    {
        return $this->belongsTo(CategorieBouf::class);
    }
}
