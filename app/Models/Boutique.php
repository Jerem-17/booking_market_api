<?php

namespace App\Models;

use App\Models\Pays;
use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use App\Models\ProductShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Boutique extends Model
{
    use HasFactory,HasUuids, SoftDeletes;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'image',
        'etablissement',
        'user_id',
        'pays_id',
        'region',
        'service_id',
        'latitude',
        'longitude',
        'contact'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pays() : BelongsTo
    {
        return $this->belongsTo(Pays::class);
    }

    public function service() : BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productshops() : HasMany
    {
        return $this->hasMany(ProductShop::class);
    }
    public function commandes(): BelongsToMany
    {
        return  $this->belongsToMany(Commande::class);
    }

    protected $hidden = ['id', 'created_at', 'updated_at', 'user_id'];
}
