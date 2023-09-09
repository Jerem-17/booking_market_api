<?php

namespace App\Models;

use App\Models\ProductShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategorieVitrine extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "categorievitrines";

    protected $fillable = [
        'type'
    ];

    public function productshops(): HasMany
    {
        return $this->hasMany(ProductShop::class);
    }

    protected $hidden = [
        "updated_at",
        "created_at",
        "deleted_at",
    ];
}
