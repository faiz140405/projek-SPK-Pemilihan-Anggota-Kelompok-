<?php
// routes/web.php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/spk/criteria', [SpkController::class, 'criteriaInput'])->name('spk.criteria');
    Route::post('/spk/criteria', [SpkController::class, 'storeCriteria'])->name('spk.store_criteria');
    Route::get('/spk/members', [SpkController::class, 'teamMembers'])->name('spk.members');
    Route::post('/spk/members', [SpkController::class, 'storeMember'])->name('spk.store_member');
    Route::get('/spk/ratings', [SpkController::class, 'inputRatings'])->name('spk.input_ratings');
    Route::post('/spk/ratings', [SpkController::class, 'storeRatings'])->name('spk.store_ratings');
    Route::get('/spk/results', [SpkController::class, 'showResults'])->name('spk.results');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk SPK Anda
    Route::get('/spk/criteria', [SpkController::class, 'criteriaInput'])->name('spk.criteria');
    Route::post('/spk/criteria', [SpkController::class, 'storeCriteria'])->name('spk.store_criteria');
    Route::get('/spk/members', [SpkController::class, 'teamMembers'])->name('spk.members');
    Route::post('/spk/members', [SpkController::class, 'storeMember'])->name('spk.store_member');
    Route::get('/spk/results', [SpkController::class, 'showResults'])->name('spk.results');

    Route::get('/spk/ratings', [SpkController::class, 'inputRatings'])->name('spk.input_ratings');
    Route::post('/spk/ratings', [SpkController::class, 'storeRatings'])->name('spk.store_ratings');
});

require __DIR__.'/auth.php';
