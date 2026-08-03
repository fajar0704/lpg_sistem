<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        if ($this->data['reportType'] === 'stok') {
            return collect([
                (object) [
                    'stokAwal' => $this->data['stockSummary']['stokAwal'],
                    'masukTotal' => $this->data['stockSummary']['masukTotal'],
                    'keluarTotal' => $this->data['stockSummary']['keluarTotal'],
                    'stokAkhir' => $this->data['stockSummary']['stokAkhir'],
                ]
            ]);
        }
        return $this->data['records'];
    }

    public function headings(): array
    {
        if ($this->data['reportType'] === 'penjualan') {
            return [
                'Tanggal Transaksi',
                'Nama Pelanggan',
                'Kategori Pelanggan',
                'Jenis LPG',
                'Jumlah Tabung',
            ];
        } elseif ($this->data['reportType'] === 'stok') {
            return [
                'Stok Awal',
                'Total Masuk',
                'Total Keluar',
                'Stok Akhir Periode',
            ];
        } elseif ($this->data['reportType'] === 'pelanggan' || $this->data['reportType'] === 'pelanggan_sub_pangkalan') {
            $headings = [
                'Nama Pelanggan',
                'NIK',
                'Kategori',
                'Tanggal Terdaftar',
                'No Telepon',
            ];
            if ($this->data['reportType'] === 'pelanggan_sub_pangkalan') {
                $headings[] = 'Sub Pangkalan';
            }
            return $headings;
        }

        return [];
    }

    public function map($row): array
    {
        if ($this->data['reportType'] === 'penjualan') {
            return [
                \Carbon\Carbon::parse($row->transaction_date)->format('d/m/Y'),
                $row->nama_pembeli ?? ($row->customer ? $row->customer->name : 'Anonim'),
                ucfirst(str_replace('_', ' ', $row->customer_type)),
                $row->tabung_type,
                $row->quantity,
            ];
        } elseif ($this->data['reportType'] === 'stok') {
            return [
                $row->stokAwal,
                $row->masukTotal,
                $row->keluarTotal,
                $row->stokAkhir,
            ];
        } elseif ($this->data['reportType'] === 'pelanggan' || $this->data['reportType'] === 'pelanggan_sub_pangkalan') {
            $rowMap = [
                $row->name,
                $row->ktp,
                $row->category === 'rumah_tangga' ? 'Rumah Tangga' : ($row->category === 'usaha_mikro' ? 'Usaha Mikro' : ($row->category === 'konsumen_umum' ? 'Konsumen Umum' : ucfirst(str_replace('_', ' ', $row->category)))),
                $row->created_at ? \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y') : '-',
                $row->phone ?? '-',
            ];
            if ($this->data['reportType'] === 'pelanggan_sub_pangkalan') {
                $rowMap[] = $row->sub_pangkalan_name ?? '-';
            }
            return $rowMap;
        }

        return [];
    }
}
