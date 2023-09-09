<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activation extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'expires_at'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'user_id'];

    public function user(): BelongsTo
    {
        return  $this->belongsTo(User::class);
    }
}
