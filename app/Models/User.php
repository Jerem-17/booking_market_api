<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Boutique;
use App\Models\Commande;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'profil',
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'activated'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['id', 'created_at', 'updated_at', 'user_id','deleted_at', 'email_verified_at','password','activated'];


    //ok
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //ok
    public function activation(): HasOne
    {
        return $this->hasOne(activation::class);
    }
    //ok
    public function boutiques(): HasMany
    {
        return $this->hasMany(Boutique::class);
    }
    //ok
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
    //ok
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
    //ok
    public function roles(): BelongsToMany
    {
        return  $this->belongsToMany(Role::class);
    }
    //ok
    public function isUser()
    {
        return $this->roles()->where('name', 'user')->first();
    }
    //ok
    public function isSeller()
    {
        return $this->roles()->where('name', 'seller')->first();
    }
    //ok
    public function isRunner()
    {
        return $this->roles()->where('name', 'runner')->first();
    }
    //ok
    public function isSuperAdmin()
    {
        return $this->roles()->where('name', 'SUPERADMIN')->first();
    }
    //ok
    public function hasAnyRoles(array $roles)
    {
        return $this->roles()->whereIn('name', $roles)->first();
    }

}
