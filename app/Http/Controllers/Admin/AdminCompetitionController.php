<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Exports\CompetitionRegistrationsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::latest()->paginate(20);
        return view('admin.competitions.index', compact('competitions'));
    }

    public function create()
    {
        return view('admin.competitions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_file' => 'nullable|image|max:5120',
            'banner_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,open,judging,closed,archived',
        ]);

        $payload = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'status' => $data['status'],
        ];

        // Xử lý banner: ưu tiên file, nếu không có thì dùng URL
        if ($request->hasFile('banner_file')) {
            $path = $request->file('banner_file')->store('competitions', 'public');
            $payload['banner_url'] = $path;
        } elseif (!empty($data['banner_url'])) {
            $payload['banner_url'] = $data['banner_url'];
        }

        $payload['slug'] = Str::slug($data['title']) . '-' . substr(Str::uuid()->toString(), 0, 8);
        $payload['created_by'] = $request->user()->id;

        Competition::create($payload);

        return redirect()->route('admin.competitions.index')->with('status', 'Tạo cuộc thi thành công');
    }

    public function edit(Competition $competition)
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,open,judging,closed,archived',
        ]);

        // Không thay đổi slug sau khi tạo để tránh gãy liên kết công khai
        $competition->update($data);

        return redirect()->route('admin.competitions.index')->with('status', 'Cập nhật cuộc thi thành công');
    }

    public function destroy(Competition $competition)
    {
        $competition->delete(); // Soft delete
        return redirect()->route('admin.competitions.index')->with('status', 'Đã xóa cuộc thi');
    }

    /**
     * Xuất danh sách đăng ký ra Excel/CSV
     */
    public function exportRegistrations(Competition $competition)
    {
        // Kiểm tra quyền (nếu cần)
        // $this->authorize('view', $competition);

        $export = new CompetitionRegistrationsExport($competition->id);

        // Thử sử dụng Maatwebsite Excel nếu có và hoạt động
        if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
            try {
                $fileName = 'DS_DangKy_' . $competition->slug . '_' . date('d-m-Y') . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download($export, $fileName, \Maatwebsite\Excel\Excel::XLSX);
            } catch (\Exception $e) {
                // Nếu lỗi (ví dụ: thiếu writer), fallback sang CSV
                \Log::warning('Excel export failed, falling back to CSV: ' . $e->getMessage());
            }
        }

        // Fallback: Xuất ra CSV
        $fileName = 'DS_DangKy_' . $competition->slug . '_' . date('d-m-Y') . '.csv';
        $csvData = $export->toCSV();

        // Tạo file CSV
        $path = storage_path('app/exports/' . $fileName);
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $file = fopen($path, 'w');
        
        // Thiết lập BOM cho UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }

        fclose($file);

        return response()->download($path, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(true);
    }
}

