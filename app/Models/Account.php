<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Account extends Model implements AuthenticatableContract
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory, Authenticatable;

    protected $fillable = ['user_id','username', 'password', 'status', 'role'];
}
