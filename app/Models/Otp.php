<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    // Specify the table name if it's different from the default pluralized version
    protected $table = 'otp_tokens';

    // Specify the attributes that can be mass assigned
    protected $fillable = [
        'email',
        'otp',
        'expires_at', // Only include expires_at if you are mass assigning it
    ];

    // Optionally, if you do not want to use the updated_at timestamp
    public $timestamps = false; // Set to true if you want to use created_at and updated_at
}