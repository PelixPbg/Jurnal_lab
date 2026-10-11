<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// Template Excel kosong yang hanya berisi baris judul kolom
class TemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    private array $judul;

    public function __construct(array $judul)
    {
        $this->judul = $judul;
    }

    public function headings(): array
    {
        return $this->judul;
    }

    public function array(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Kolom A (NIS/NIP) dijadikan format Teks supaya angka panjang
        // tidak berubah jadi notasi ilmiah dan angka 0 di depan tidak hilang
        $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('1:1')->getFont()->setBold(true);

        return [];
    }
}