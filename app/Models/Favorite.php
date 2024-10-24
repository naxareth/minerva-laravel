<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites';

    // Specify the primary key as an array for composite keys
    protected $primaryKey = ['user_id', 'anime_id'];

    // Disable auto-incrementing
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'anime_id', // Include anime_id for the composite key
        'title',
        'image',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}