<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',  // <-- PASTIKAN 'name' ADA DI SINI
        'email', // <-- PASTIKAN 'email' ADA DI SINI
        // Tambahkan kolom lain di sini jika Anda menambahkannya di migrasi
        // dan ingin bisa mengisi massal
    ];
    public function ratings()
    {
        return $this->hasMany(MemberCriteriaRating::class);
    }
}
