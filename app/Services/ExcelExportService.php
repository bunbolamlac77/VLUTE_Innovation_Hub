<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Service để xuất dữ liệu ra file Excel (.xlsx)
 * Sử dụng PhpOffice\PhpSpreadsheet
 */
class ExcelExportService
{
    protected $spreadsheet;
    protected $sheet;
    protected $rowNumber = 1;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * Thêm tiêu đề (header row)
     */
    public function addHeaders(array $headers): self
    {
        foreach ($headers as $columnIndex => $header) {
            $columnLetter = $this->getColumnLetter($columnIndex);
            $cell = $this->sheet->getCell($columnLetter . $this->rowNumber);
            $cell->setValue($header);

            // Định dạng header: bold, background màu xanh nhạt
            $cell->getStyle()
                ->getFont()
                ->setBold(true)
                ->setColor(['rgb' => 'FFFFFF']);

            $cell->getStyle()
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('4472C4');

            $cell->getStyle()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        $this->rowNumber++;
        return $this;
    }

    /**
     * Thêm dữ liệu (data rows)
     */
    public function addRows(array $rows): self
    {
        foreach ($rows as $row) {
            foreach ($row as $columnIndex => $value) {
                $columnLetter = $this->getColumnLetter($columnIndex);
                $cell = $this->sheet->getCell($columnLetter . $this->rowNumber);
                $cell->setValue($value);

                // Căn giữa các ô
                $cell->getStyle()
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            }
            $this->rowNumber++;
        }

        return $this;
    }

    /**
     * Tự động điều chỉnh độ rộng cột
     */
    public function autoSizeColumns(): self
    {
        foreach ($this->sheet->getColumnIterator() as $column) {
            $this->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
        return $this;
    }

    /**
     * Xuất file Excel
     */
    public function download(string $fileName): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->autoSizeColumns();

        $path = storage_path('app/exports/' . $fileName);
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($path);

        return response()->download($path, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Chuyển đổi index cột thành chữ cái (0 -> A, 1 -> B, ...)
     */
    private function getColumnLetter(int $index): string
    {
        $letter = '';
        $index++;
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26);
        }
        return $letter;
    }
}

