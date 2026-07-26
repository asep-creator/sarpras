<?php

use App\Http\Controllers\ProfileController;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function (Request $request) {
    $year = $request->query('year', now()->year);

    $totalLaporan = Laporan::count();
    $belum = Laporan::where('status', 'baru')->count();
    $selesai = Laporan::where('status', 'selesai')->count();

    $categories = [
        'Sipil' => 0,
        'Electrical' => 0,
        'Plumbing' => 0,
    ];

    $categoryCounts = Laporan::select('kategori', DB::raw('count(*) as total'))
        ->groupBy('kategori')
        ->pluck('total', 'kategori')
        ->all();

    foreach ($categories as $name => $value) {
        $categories[$name] = $categoryCounts[$name] ?? 0;
    }

    $categoryPercents = array_map(function ($count) use ($totalLaporan) {
        return $totalLaporan > 0 ? round($count / $totalLaporan * 100) : 0;
    }, $categories);

    $monthlyCounts = Laporan::selectRaw('MONTH(tanggal) as month, count(*) as total')
        ->whereYear('tanggal', $year)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->all();

    $monthlyData = [];
    for ($month = 1; $month <= 12; $month++) {
        $monthlyData[$month] = $monthlyCounts[$month] ?? 0;
    }

    $maxMonthCount = max($monthlyData) ?: 1;

    $availableYears = Laporan::selectRaw('YEAR(tanggal) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year')
        ->filter()
        ->values()
        ->all();

    if (empty($availableYears)) {
        $availableYears = [now()->year];
    }

    return view('dashboard', compact('year', 'totalLaporan', 'belum', 'selesai', 'categories', 'categoryPercents', 'monthlyData', 'maxMonthCount', 'availableYears'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/laporan/riwayat', [App\Http\Controllers\LaporanController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('laporan.riwayat');

Route::get('/data/sipil', [App\Http\Controllers\LaporanController::class, 'category'])->defaults('category', 'sipil')->middleware(['auth', 'verified'])->name('data.sipil');
Route::post('/data/sipil', [App\Http\Controllers\LaporanController::class, 'storeCategory'])->defaults('category', 'sipil')->middleware(['auth', 'verified'])->name('data.sipil.store');
Route::get('/data/sipil/laporan/{laporan}/kerjakan', [App\Http\Controllers\LaporanController::class, 'showWorkForm'])->defaults('category', 'sipil')->middleware(['auth', 'verified', 'role:admin'])->name('data.sipil.work');
Route::post('/data/sipil/laporan/{laporan}/kerjakan', [App\Http\Controllers\LaporanController::class, 'storeWork'])->defaults('category', 'sipil')->middleware(['auth', 'verified', 'role:admin'])->name('data.sipil.work.store');
Route::get('/data/sipil/laporan/{laporan}/review', [App\Http\Controllers\LaporanController::class, 'showReview'])->defaults('category', 'sipil')->middleware(['auth', 'verified', 'role:admin'])->name('data.sipil.review');
Route::get('/data/sipil/laporan/{laporan}/edit', [App\Http\Controllers\LaporanController::class, 'showEditForm'])->defaults('category', 'sipil')->middleware(['auth', 'verified', 'role:admin'])->name('data.sipil.edit');
Route::put('/data/sipil/laporan/{laporan}', [App\Http\Controllers\LaporanController::class, 'updateReport'])->defaults('category', 'sipil')->middleware(['auth', 'verified', 'role:admin'])->name('data.sipil.update');
Route::post('/data/sipil/delete', [App\Http\Controllers\LaporanController::class, 'deleteCategory'])->defaults('category', 'sipil')->middleware(['auth', 'verified', 'role:admin'])->name('data.sipil.delete');
Route::get('/data/sipil/export', [App\Http\Controllers\LaporanController::class, 'exportCategory'])->defaults('category', 'sipil')->middleware(['auth', 'verified', 'role:admin'])->name('data.sipil.export');

Route::get('/data/electrical', [App\Http\Controllers\LaporanController::class, 'category'])->defaults('category', 'electrical')->middleware(['auth', 'verified'])->name('data.electrical');
Route::post('/data/electrical', [App\Http\Controllers\LaporanController::class, 'storeCategory'])->defaults('category', 'electrical')->middleware(['auth', 'verified'])->name('data.electrical.store');
Route::get('/data/electrical/laporan/{laporan}/kerjakan', [App\Http\Controllers\LaporanController::class, 'showWorkForm'])->defaults('category', 'electrical')->middleware(['auth', 'verified', 'role:admin'])->name('data.electrical.work');
Route::post('/data/electrical/laporan/{laporan}/kerjakan', [App\Http\Controllers\LaporanController::class, 'storeWork'])->defaults('category', 'electrical')->middleware(['auth', 'verified', 'role:admin'])->name('data.electrical.work.store');
Route::get('/data/electrical/laporan/{laporan}/review', [App\Http\Controllers\LaporanController::class, 'showReview'])->defaults('category', 'electrical')->middleware(['auth', 'verified', 'role:admin'])->name('data.electrical.review');
Route::get('/data/electrical/laporan/{laporan}/edit', [App\Http\Controllers\LaporanController::class, 'showEditForm'])->defaults('category', 'electrical')->middleware(['auth', 'verified', 'role:admin'])->name('data.electrical.edit');
Route::put('/data/electrical/laporan/{laporan}', [App\Http\Controllers\LaporanController::class, 'updateReport'])->defaults('category', 'electrical')->middleware(['auth', 'verified', 'role:admin'])->name('data.electrical.update');
Route::post('/data/electrical/delete', [App\Http\Controllers\LaporanController::class, 'deleteCategory'])->defaults('category', 'electrical')->middleware(['auth', 'verified', 'role:admin'])->name('data.electrical.delete');
Route::get('/data/electrical/export', [App\Http\Controllers\LaporanController::class, 'exportCategory'])->defaults('category', 'electrical')->middleware(['auth', 'verified', 'role:admin'])->name('data.electrical.export');

Route::get('/data/plumbing', [App\Http\Controllers\LaporanController::class, 'category'])->defaults('category', 'plumbing')->middleware(['auth', 'verified'])->name('data.plumbing');
Route::post('/data/plumbing', [App\Http\Controllers\LaporanController::class, 'storeCategory'])->defaults('category', 'plumbing')->middleware(['auth', 'verified'])->name('data.plumbing.store');
Route::get('/data/plumbing/laporan/{laporan}/kerjakan', [App\Http\Controllers\LaporanController::class, 'showWorkForm'])->defaults('category', 'plumbing')->middleware(['auth', 'verified', 'role:admin'])->name('data.plumbing.work');
Route::post('/data/plumbing/laporan/{laporan}/kerjakan', [App\Http\Controllers\LaporanController::class, 'storeWork'])->defaults('category', 'plumbing')->middleware(['auth', 'verified', 'role:admin'])->name('data.plumbing.work.store');
Route::get('/data/plumbing/laporan/{laporan}/review', [App\Http\Controllers\LaporanController::class, 'showReview'])->defaults('category', 'plumbing')->middleware(['auth', 'verified', 'role:admin'])->name('data.plumbing.review');
Route::get('/data/plumbing/laporan/{laporan}/edit', [App\Http\Controllers\LaporanController::class, 'showEditForm'])->defaults('category', 'plumbing')->middleware(['auth', 'verified', 'role:admin'])->name('data.plumbing.edit');
Route::put('/data/plumbing/laporan/{laporan}', [App\Http\Controllers\LaporanController::class, 'updateReport'])->defaults('category', 'plumbing')->middleware(['auth', 'verified', 'role:admin'])->name('data.plumbing.update');
Route::post('/data/plumbing/delete', [App\Http\Controllers\LaporanController::class, 'deleteCategory'])->defaults('category', 'plumbing')->middleware(['auth', 'verified', 'role:admin'])->name('data.plumbing.delete');
Route::get('/data/plumbing/export', [App\Http\Controllers\LaporanController::class, 'exportCategory'])->defaults('category', 'plumbing')->middleware(['auth', 'verified', 'role:admin'])->name('data.plumbing.export');

Route::get('/grafik', function () {
    return view('blank-page');
})->middleware(['auth', 'verified', 'role:admin'])->name('grafik');

Route::get('/laporan', function () {
    return view('blank-page');
})->middleware(['auth', 'verified', 'role:admin'])->name('laporan');

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/pengguna', [App\Http\Controllers\UserController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [App\Http\Controllers\UserController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [App\Http\Controllers\UserController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('pengguna.destroy');
});

Route::get('/pengaturan', function () {
    return view('blank-page');
})->middleware(['auth', 'verified'])->name('pengaturan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
