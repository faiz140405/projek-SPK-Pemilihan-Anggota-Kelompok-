<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\TeamMember;
use App\Models\MemberCriteriaRating;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data Ringkasan
        $totalCriterias = Criteria::count();
        $totalMembers = TeamMember::count();
        $totalRatings = MemberCriteriaRating::count();

        // Data untuk Grafik (misalnya, 5 anggota tim teratas)
        $topMembersData = [];
        $calculationMessage = null;

        $criterias = Criteria::all();
        $members = TeamMember::with('ratings')->get();

        // --- Logika perhitungan SPK dimulai di sini ---
        if ($criterias->isEmpty() || $members->isEmpty()) {
            $calculationMessage = 'Tambahkan kriteria dan anggota tim untuk melihat hasil peringkat.';
        } else {
            $totalWeight = $criterias->sum('weight');
            $results = [];

            foreach ($members as $member) {
                $totalScore = 0;
                $hasAllRatings = true;

                foreach ($criterias as $criteria) {
                    $rating = $member->ratings->where('criteria_id', $criteria->id)->first();
                    if ($rating) {
                        $normalizedWeight = $criteria->weight / $totalWeight;
                        $totalScore += ($rating->score * $normalizedWeight);
                    } else {
                        $hasAllRatings = false;
                        break;
                    }
                }

                if ($hasAllRatings) {
                    $results[] = [
                        'member' => $member,
                        'final_score' => $totalScore,
                    ];
                }
            }

            usort($results, function($a, $b) {
                if ($a['final_score'] === null && $b['final_score'] === null) {
                    return 0;
                }
                if ($a['final_score'] === null) {
                    return 1;
                }
                if ($b['final_score'] === null) {
                    return -1;
                }
                return $b['final_score'] <=> $a['final_score'];
            });

            $topRecommended = array_filter($results, fn($r) => $r['final_score'] !== null);
            $topRecommended = array_slice($topRecommended, 0, 5);

            foreach ($topRecommended as $res) {
                $topMembersData['labels'][] = $res['member']->name;
                $topMembersData['scores'][] = number_format($res['final_score'], 2);
            }

            if (empty($topMembersData['labels'])) {
                $calculationMessage = 'Belum ada anggota tim dengan penilaian lengkap untuk semua kriteria.';
            }
        }
        // --- Logika perhitungan SPK berakhir di sini ---

        // dd($topMembersData, $calculationMessage); // <-- Pindahkan dd() ke sini (sementara)

        // --- Pastikan hanya ada SATU return view() di akhir metode ---
        return view('dashboard', compact(
            'totalCriterias',
            'totalMembers',
            'totalRatings',
            'topMembersData',
            'calculationMessage'
        ));
    }
}