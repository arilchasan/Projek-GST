<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'admin';

    protected $fillable = [
        'username',
        'password'
    ];

    public function tax()
    {
        return $this->hasMany(Tax::class);
    }
}
