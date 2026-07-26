<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::latest()->get();

        return view('laporan-riwayat', compact('laporans'));
    }

    private function resolveCategoryLabel(string $category): string
    {
        $categoryLabels = [
            'sipil' => 'Sipil',
            'electrical' => 'Electrical',
            'plumbing' => 'Plumbing',
        ];

        if (!isset($categoryLabels[$category])) {
            abort(404);
        }

        return $categoryLabels[$category];
    }

    public function category(Request $request, string $category)
    {
        $categoryLabels = [
            'sipil' => 'Sipil',
            'electrical' => 'Electrical',
            'plumbing' => 'Plumbing',
        ];

        if (!isset($categoryLabels[$category])) {
            abort(404);
        }

        $categoryLabel = $categoryLabels[$category];
        $selectedDate = $request->query('tanggal', now()->format('Y-m-d'));
        $selectedCarbon = Carbon::parse($selectedDate);
        $selectedMonth = $selectedCarbon->month;
        $selectedYear = $selectedCarbon->year;

        $laporans = Laporan::where('kategori', $categoryLabel)
            ->whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->latest()
            ->get();

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $years = range(now()->year - 2, now()->year);

        return view('category-laporan', compact('category', 'categoryLabel', 'laporans', 'months', 'years', 'selectedMonth', 'selectedYear', 'selectedDate'));
    }

    public function deleteCategory(Request $request, string $category)
    {
        $categoryLabel = $this->resolveCategoryLabel($category);

        $validated = $request->validate([
            'laporan_ids' => ['required', 'array'],
            'laporan_ids.*' => ['integer', 'exists:laporans,id'],
        ]);

        $deletedCount = Laporan::where('kategori', $categoryLabel)
            ->whereIn('id', $validated['laporan_ids'])
            ->delete();

        return redirect()->route('data.' . $category, ['tanggal' => $request->query('tanggal', now()->format('Y-m-d'))])
            ->with('success', "$deletedCount laporan berhasil dihapus.");
    }

    public function exportCategory(Request $request, string $category)
    {
        $categoryLabel = $this->resolveCategoryLabel($category);
        $selectedDate = $request->query('tanggal', now()->format('Y-m-d'));
        $selectedCarbon = Carbon::parse($selectedDate);

        $laporans = Laporan::where('kategori', $categoryLabel)
            ->whereYear('tanggal', $selectedCarbon->year)
            ->whereMonth('tanggal', $selectedCarbon->month)
            ->latest()
            ->get();

        $filename = sprintf('laporan-%s-%02d-%d.csv', strtolower($categoryLabel), $selectedCarbon->month, $selectedCarbon->year);

        $callback = function () use ($laporans) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['ID', 'Tanggal', 'Lokasi', 'Ruangan', 'Kondisi', 'Status', 'Deskripsi', 'Tanggal Pengerjaan', 'Nama Tukang', 'Estimasi', 'Detail Pengerjaan']);

            foreach ($laporans as $laporan) {
                fputcsv($out, [
                    $laporan->id,
                    $laporan->tanggal?->format('Y-m-d') ?? '',
                    $laporan->lokasi,
                    $laporan->ruangan,
                    $laporan->kondisi,
                    $laporan->status,
                    $laporan->deskripsi,
                    $laporan->tanggal_pengerjaan?->format('Y-m-d') ?? '',
                    $laporan->nama_tukang,
                    $laporan->estimasi,
                    $laporan->detail_pengerjaan,
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function showEditForm(Request $request, Laporan $laporan)
    {
        $category = $request->route('category');
        $categoryLabel = $this->resolveCategoryLabel($category);

        if ($laporan->kategori !== $categoryLabel) {
            abort(404);
        }

        return view('category-laporan-edit', compact('laporan', 'category', 'categoryLabel'));
    }

    public function updateReport(Request $request, Laporan $laporan)
    {
        if (!auth()->user()->role || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $category = $request->route('category');
        $categoryLabel = $this->resolveCategoryLabel($category);

        if ($laporan->kategori !== $categoryLabel) {
            abort(404);
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'lokasi' => ['required', 'string'],
            'ruangan' => ['required', 'string'],
            'kondisi' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_pengerjaan' => ['nullable', 'date'],
            'nama_tukang' => ['nullable', 'string', 'max:255'],
            'estimasi' => ['nullable', 'string', 'max:255'],
            'detail_pengerjaan' => ['nullable', 'string'],
        ]);

        $tanggal = Carbon::parse($validated['tanggal']);

        $laporan->update([
            'tanggal' => $validated['tanggal'],
            'bulan' => $this->monthName($tanggal->month),
            'tahun' => $tanggal->year,
            'lokasi' => $validated['lokasi'],
            'ruangan' => $validated['ruangan'],
            'kondisi' => $validated['kondisi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tanggal_pengerjaan' => $validated['tanggal_pengerjaan'] ?? null,
            'nama_tukang' => $validated['nama_tukang'] ?? null,
            'estimasi' => $validated['estimasi'] ?? null,
            'detail_pengerjaan' => $validated['detail_pengerjaan'] ?? null,
        ]);

        return redirect()->route('data.' . $category . '.edit', $laporan)
            ->with('success', 'Data laporan berhasil diperbarui.');
    }

    private function monthName(int $month): string
    {
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $monthNames[$month] ?? '';
    }

    public function showWorkForm(Request $request, Laporan $laporan)
    {
        $category = $request->route('category');
        $categoryLabel = $this->resolveCategoryLabel($category);

        if ($laporan->kategori !== $categoryLabel) {
            abort(404);
        }

        return view('category-laporan-kerjakan', compact('laporan', 'category', 'categoryLabel'));
    }

    public function showReview(Request $request, Laporan $laporan)
    {
        $category = $request->route('category');
        $categoryLabel = $this->resolveCategoryLabel($category);

        if ($laporan->kategori !== $categoryLabel) {
            abort(404);
        }

        return view('category-laporan-review', compact('laporan', 'category', 'categoryLabel'));
    }

    public function storeCategory(Request $request, string $category)
    {
        $categoryLabels = [
            'sipil' => 'Sipil',
            'electrical' => 'Electrical',
            'plumbing' => 'Plumbing',
        ];

        if (!isset($categoryLabels[$category])) {
            abort(404);
        }

        $categoryLabel = $categoryLabels[$category];

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'lokasi' => ['required', 'string'],
            'ruangan' => ['required', 'string'],
            'kondisi' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'array'],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $fotoPaths = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('laporan-foto', 'public');
                $fotoPaths[] = $path;
            }
        }

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $tanggal = Carbon::parse($validated['tanggal']);
        $laporan = Laporan::create([
            'user_id' => auth()->id(),
            'tanggal' => $validated['tanggal'],
            'bulan' => $monthNames[$tanggal->month],
            'tahun' => $tanggal->year,
            'kategori' => $categoryLabel,
            'lokasi' => $validated['lokasi'],
            'ruangan' => $validated['ruangan'],
            'kondisi' => $validated['kondisi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto_paths' => $fotoPaths,
            'status' => 'baru',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil disimpan.',
            'laporan_id' => $laporan->id,
        ], 201);
    }

    public function storeWork(Request $request, Laporan $laporan)
    {
        if (!auth()->user()->role || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $category = $request->route('category');
        $categoryLabel = $this->resolveCategoryLabel($category);

        if ($laporan->kategori !== $categoryLabel) {
            abort(404);
        }

        $validated = $request->validate([
            'tanggal_pengerjaan' => ['required', 'date'],
            'nama_tukang' => ['required', 'string', 'max:255'],
            'estimasi' => ['nullable', 'string', 'max:255'],
            'detail_pengerjaan' => ['nullable', 'string'],
        ]);

        $laporan->update([
            'tanggal_pengerjaan' => $validated['tanggal_pengerjaan'],
            'nama_tukang' => $validated['nama_tukang'],
            'estimasi' => $validated['estimasi'] ?? null,
            'detail_pengerjaan' => $validated['detail_pengerjaan'] ?? null,
            'status' => 'review',
        ]);

        return redirect()->route('data.' . $category . '.review', $laporan)
            ->with('success', 'Detail pengerjaan berhasil disimpan. Laporan sekarang dalam review.');
    }
}
