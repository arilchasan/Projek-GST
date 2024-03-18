<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Tax extends Model
{
    use HasFactory,Notifiable;

    protected $table = 'tax';

    protected $fillable = [
        'file_path',
        'file_name',
        'user_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
