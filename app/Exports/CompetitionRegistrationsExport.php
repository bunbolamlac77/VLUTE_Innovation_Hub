<?php

namespace App\Exports;

use App\Models\CompetitionRegistration;

/**
 * Export class for Competition Registrations
 * 
 * Hỗ trợ cả Maatwebsite Excel (XLSX) và CSV fallback
 */
class CompetitionRegistrationsExport
{
    protected $competitionId;

    public function __construct($competitionId)
    {
        $this->competitionId = $competitionId;
    }

    /**
     * Lấy dữ liệu từ database
     */
    public function collection()
    {
        return CompetitionRegistration::with(['user', 'user.profile'])
            ->where('competition_id', $this->competitionId)
            ->get();
    }

    /**
     * Tiêu đề các cột trong file Excel/CSV
     */
    public function headings(): array
    {
        return [
            'ID Đăng ký',
            'Tên Nhóm / Cá nhân',
            'Họ và tên trưởng nhóm',
            'Email',
            'Số điện thoại',
            'Lớp',
            'Ngày đăng ký',
        ];
    }

    /**
     * Ánh xạ dữ liệu từng dòng
     */
    public function map($registration): array
    {
        return [
            $registration->id,
            $registration->team_name ?? 'Cá nhân',
            $registration->user->name,
            $registration->user->email,
            $registration->user->profile->phone ?? 'N/A',
            $registration->user->profile->class ?? 'N/A',
            $registration->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Tạo mảng dữ liệu CSV (dùng cho fallback)
     */
    public function toCSV(): array
    {
        $rows = [];
        $rows[] = $this->headings();
        foreach ($this->collection() as $item) {
            $rows[] = $this->map($item);
        }
        return $rows;
    }
}
