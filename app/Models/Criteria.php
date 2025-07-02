<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
        use HasFactory;
        protected $table = 'criteria';

    // Tentukan kolom mana yang bisa diisi secara massal
    protected $fillable = [
        'name',
        'weight',
    ];
}
