<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criteria;
use App\Models\TeamMember;
use App\Models\MemberCriteriaRating;

class SpkController extends Controller
{
    public function criteriaInput()
    {
        // Ambil semua kriteria yang sudah ada dari database
        $criterias = Criteria::all();
        return view('spk.criteria-input', compact('criterias'));
    }

    public function storeCriteria(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|integer|min:1|max:10',
        ]);

        // Simpan kriteria baru ke database
        Criteria::create([
            'name' => $request->name,
            'weight' => $request->weight,
        ]);

        return redirect()->route('spk.criteria')->with('success', 'Kriteria berhasil disimpan!');
    }

    public function teamMembers()
    {
        $members = TeamMember::all(); // <-- Pastikan baris ini ada
        return view('spk.team-members', compact('members'));
    }

    public function storeMember(Request $request)
    {
        {
        // dd($request->all()); // Baris ini yang harus dihapus/dikomentari

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:team_members,email',
        ]);

        TeamMember::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('spk.members')->with('success', 'Anggota tim berhasil disimpan!');
    }
    }

    public function inputRatings()
    {
        $members = TeamMember::all();
        $criterias = Criteria::all();

        // Mengambil rating yang sudah ada untuk mengisi form (jika ada)
        // Ini akan membuat query ke database untuk setiap member dan kriteria,
        // jadi untuk jumlah data besar, pertimbangkan eager loading
        $ratings = MemberCriteriaRating::all()->keyBy(function($item) {
            return $item['team_member_id'] . '-' . $item['criteria_id'];
        });


        return view('spk.input-ratings', compact('members', 'criterias', 'ratings'));
    }

    public function storeRatings(Request $request)
    {
        // Validasi input
        $rules = [];
        foreach ($request->input('ratings') as $memberId => $criteriaScores) {
            foreach ($criteriaScores as $criteriaId => $score) {
                $rules["ratings.{$memberId}.{$criteriaId}"] = 'required|integer|min:1|max:10'; // Sesuaikan min/max score Anda
            }
        }
        $request->validate($rules);

        // Simpan atau perbarui rating
        foreach ($request->input('ratings') as $memberId => $criteriaScores) {
            foreach ($criteriaScores as $criteriaId => $score) {
                MemberCriteriaRating::updateOrCreate(
                    [
                        'team_member_id' => $memberId,
                        'criteria_id' => $criteriaId
                    ],
                    [
                        'score' => $score
                    ]
                );
            }
        }

        return redirect()->route('spk.input_ratings')->with('success', 'Penilaian anggota tim berhasil disimpan!');
    }

    public function showResults()
    {
        // 1. Ambil semua kriteria dan bobotnya
        $criterias = Criteria::all();
        $totalWeight = $criterias->sum('weight'); // Hitung total bobot untuk normalisasi

        // Jika tidak ada kriteria, tidak bisa menghitung
        if ($criterias->isEmpty()) {
            return view('spk.results')->with('message', 'Tidak ada kriteria yang ditemukan. Harap tambahkan kriteria terlebih dahulu.');
        }

        // 2. Ambil semua anggota tim beserta ratingnya (gunakan eager loading untuk efisiensi)
        $members = TeamMember::with('ratings')->get();

        // Jika tidak ada anggota tim, tidak bisa menghitung
        if ($members->isEmpty()) {
            return view('spk.results')->with('message', 'Tidak ada anggota tim yang ditemukan. Harap tambahkan anggota tim terlebih dahulu.');
        }

        // 3. Lakukan perhitungan SPK untuk setiap anggota tim
        $results = [];
        foreach ($members as $member) {
            $totalScore = 0;
            $hasAllRatings = true; // Flag untuk mengecek apakah semua rating ada

            foreach ($criterias as $criteria) {
                // Cari rating untuk anggota tim ini dan kriteria ini
                $rating = $member->ratings->where('criteria_id', $criteria->id)->first();

                if ($rating) {
                    // Normalisasi bobot kriteria
                    $normalizedWeight = $criteria->weight / $totalWeight;

                    // Hitung skor: (nilai rating * bobot ternormalisasi)
                    $totalScore += ($rating->score * $normalizedWeight);
                } else {
                    // Jika ada rating yang hilang, anggap anggota tim ini belum siap dihitung
                    $hasAllRatings = false;
                    break; // Keluar dari loop kriteria
                }
            }

            if ($hasAllRatings) {
                $results[] = [
                    'member' => $member,
                    'final_score' => $totalScore,
                ];
            } else {
                // Tambahkan pesan jika ada anggota tim yang belum lengkap ratingnya
                $results[] = [
                    'member' => $member,
                    'final_score' => null, // Atau nilai lain yang menunjukkan belum lengkap
                    'message' => 'Penilaian belum lengkap untuk anggota tim ini.',
                ];
            }
        }

        // 4. Urutkan hasil berdasarkan skor tertinggi
        usort($results, function($a, $b) {
            // Tangani kasus null (belum lengkap) agar diurutkan di akhir
            if ($a['final_score'] === null && $b['final_score'] === null) {
                return 0;
            }
            if ($a['final_score'] === null) {
                return 1; // a (null) goes after b
            }
            if ($b['final_score'] === null) {
                return -1; // b (null) goes after a
            }
            return $b['final_score'] <=> $a['final_score']; // Urutkan dari tertinggi ke terendah
        });


        return view('spk.results', compact('results', 'criterias', 'members'));
    }
}

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
        $calculationMessage = null; // Untuk pesan jika data kurang untuk perhitungan

        $criterias = Criteria::all();
        $members = TeamMember::with('ratings')->get();

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

            // Urutkan hasil dan ambil top 5 (atau sesuai keinginan)
            usort($results, function($a, $b) {
                return $b['final_score'] <=> $a['final_score'];
            });

            // Ambil hanya anggota yang memiliki skor final dan batasi top N
            $topRecommended = array_filter($results, fn($r) => $r['final_score'] !== null);
            $topRecommended = array_slice($topRecommended, 0, 5); // Ambil 5 teratas

            foreach ($topRecommended as $res) {
                $topMembersData['labels'][] = $res['member']->name;
                $topMembersData['scores'][] = number_format($res['final_score'], 2); // Format untuk tampilan
            }

            if (empty($topMembersData)) {
                $calculationMessage = 'Belum ada anggota tim dengan penilaian lengkap untuk semua kriteria.';
            }
        }


        return view('dashboard', compact(
            'totalCriterias',
            'totalMembers',
            'totalRatings',
            'topMembersData',
            'calculationMessage'
        ));
    }
}