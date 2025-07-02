<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCriteriaRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id',
        'criteria_id',
        'score',
    ];

    // Definisi relasi (akan berguna nanti)
    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}