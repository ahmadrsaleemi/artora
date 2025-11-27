<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'userEmail', // Custom email column
        'userPassword', // Custom password column
    ];

    protected $hidden = [
        'userPassword', // Hide custom password column
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Ensure this method returns 'id' (the primary key column name)
    public function getAuthIdentifierName()
    {
        return 'id'; // Default primary key column
    }

    // Return the email used for login
    public function getEmailForAuthentication()
    {
        return $this->userEmail; // Use custom email column
    }

    // Ensure the password column is correctly identified
    public function getAuthPassword()
    {
        return $this->userPassword; // Custom password column
    }
}
