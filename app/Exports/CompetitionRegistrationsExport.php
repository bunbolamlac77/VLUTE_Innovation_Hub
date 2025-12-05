<?php

namespace App\Exports;

use App\Models\CompetitionRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompetitionRegistrationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
     * Tiêu đề các cột trong file Excel
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
            optional($registration->user->profile)->phone ?? 'N/A', // Lấy từ profile
            optional($registration->user->profile)->class_name ?? 'N/A',
            $registration->created_at->format('d/m/Y H:i'),
        ];
    }
}
