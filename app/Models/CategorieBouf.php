<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategorieBouf extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "categorieboufs";

    protected $fillable = [
        'type'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    protected $hidden = [
        "updated_at",
        "created_at",
        "deleted_at",
    ];
    
}
