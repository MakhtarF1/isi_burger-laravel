<?php

namespace App\Models;

// Imports...
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * Vérifie si l'utilisateur est un gestionnaire.
     *
     * @return bool
     */
    public function isGestionnaire()
    {
        return $this->role === 'gestionnaire';
    }

    /**
     * Vérifie si l'utilisateur est un client.
     *
     * @return bool
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Relation avec les commandes.
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}