<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Disable auto-incrementing
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'image',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}