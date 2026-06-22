<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DistributionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $distributions;

    public function __construct($distributions)
    {
        $this->distributions = $distributions;
    }

    public function collection()
    {
        return $this->distributions;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Sub Pangkalan',
            'User',
            'Jenis',
            'Tipe Tabung',
            'Jumlah',
            'Status',
        ];
    }

    public function map($distribution): array
    {
        return [
            $distribution->transaction_date->format('d/m/Y'),
            $distribution->subPangkalan->name,
            $distribution->user->name,
            $distribution->type === 'in' ? 'Masuk' : 'Keluar',
            $distribution->tabung_type,
            $distribution->quantity,
            ucfirst($distribution->status),
        ];
    }
}
