<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan - Sistem LPG</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f5; font-weight: bold; }
        .text-center { text-align: center; }
        .summary-box { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; width: 45%; display: inline-block; vertical-align: top; }
        .summary-title { font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .summary-value { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>
            @if($reportType === 'penjualan')
                Laporan Penjualan LPG
            @elseif($reportType === 'stok')
                Laporan Stok LPG
            @elseif($reportType === 'pelanggan')
                Laporan Data Pelanggan Pangkalan (Utama)
            @elseif($reportType === 'pelanggan_sub_pangkalan')
                Laporan Data Pelanggan Sub Pangkalan (Pengecer)
            @endif
        </h1>
        <p>
            @if($period === 'daily')
                Tanggal: {{ $label }}
            @elseif($period === 'monthly')
                Periode: Bulan {{ $label }}
            @elseif($period === 'yearly')
                Periode: {{ $label }}
            @endif
        </p>
    </div>

    @if($reportType === 'penjualan')
        <p><strong>Total Transaksi:</strong> {{ collect($records)->count() }}</p>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Kategori</th>
                    <th class="text-center">Jenis LPG</th>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($r->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $r->nama_pembeli ?? ($r->customer ? $r->customer->name : 'Anonim') }}</td>
                    <td>
                        @if($r->customer_type === 'rumah_tangga') Rumah Tangga
                        @elseif($r->customer_type === 'usaha_mikro') Usaha Mikro
                        @elseif($r->customer_type === 'pengecer') Sub Pangkalan
                        @else {{ ucfirst($r->customer_type) }} @endif
                    </td>
                    <td class="text-center">{{ $r->tabung_type }}</td>
                    <td class="text-center">{{ $r->quantity }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada transaksi penjualan di periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($reportType === 'stok')
        
        <div style="margin-bottom: 20px;">
            <div class="summary-box">
                <div class="summary-title">Stok Awal</div>
                <div class="summary-value">{{ $stockSummary['stokAwal'] }} tabung</div>
            </div>
            <div class="summary-box" style="margin-left: 5%;">
                <div class="summary-title">Stok Akhir Periode</div>
                <div class="summary-value">{{ $stockSummary['stokAkhir'] }} tabung</div>
            </div>
            <div class="summary-box">
                <div class="summary-title">Total Masuk</div>
                <div class="summary-value">+{{ $stockSummary['masukTotal'] }} tabung</div>
                <div style="font-size:10px; color:#666;">(Restok: {{ $stockSummary['masukRestok'] }})</div>
            </div>
            <div class="summary-box" style="margin-left: 5%;">
                <div class="summary-title">Total Keluar</div>
                <div class="summary-value">-{{ $stockSummary['keluarTotal'] }} tabung</div>
                <div style="font-size:10px; color:#666;">(Penjualan Langsung)</div>
            </div>
        </div>

        <h3 style="margin-top: 30px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Rincian LPG Masuk (Restok)</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe Tabung</th>
                    <th class="text-center">Jumlah Masuk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($restokDetail as $item)
                <tr>
                    <td>{{ $item->received_date->format('d/m/Y') }}</td>
                    <td>Tabung {{ $item->cylinder_type }}</td>
                    <td class="text-center">+{{ $item->quantity_in }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data LPG masuk di periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <h3 style="margin-top: 30px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Rincian LPG Keluar (Penjualan)</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Penerima</th>
                    <th>Kategori</th>
                    <th class="text-center">Jumlah Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualanDetail as $item)
                <tr>
                    <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $item->nama_pembeli }}</td>
                    <td>
                        @if($item->customer_type === 'rumah_tangga') Rumah Tangga
                        @elseif($item->customer_type === 'usaha_mikro') Usaha Mikro
                        @elseif($item->customer_type === 'pengecer') Sub Pangkalan
                        @else {{ ucfirst($item->customer_type) }} @endif
                    </td>
                    <td class="text-center">-{{ $item->quantity }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data LPG keluar di periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($reportType === 'pelanggan' || $reportType === 'pelanggan_sub_pangkalan')
        <p><strong>Total Pelanggan:</strong> {{ collect($records)->count() }}</p>
        <table>
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    @if($reportType !== 'pelanggan_sub_pangkalan')
                    <th>Kategori</th>
                    @endif
                    <th>Tanggal Terdaftar</th>
                    <th class="text-center">Foto KTP</th>
                    <th class="text-center">Foto KK</th>
                    <th>Nomor HP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $c)
                <tr>
                    <td>
                        <strong>{{ $c->name }}</strong><br>
                        <span style="font-size: 10px; color: #666;">NIK: {{ $c->ktp }}</span>
                        @if($reportType === 'pelanggan_sub_pangkalan')
                            <br><span style="font-size: 9px; color: #1e40af; font-weight: bold;">Pengecer: {{ $c->sub_pangkalan_name }}</span>
                        @endif
                    </td>
                    @if($reportType !== 'pelanggan_sub_pangkalan')
                    <td>{{ $c->category }}</td>
                    @endif
                    <td>{{ $c->created_at ? \Carbon\Carbon::parse($c->created_at)->translatedFormat('d F Y') : '-' }}</td>
                    <td class="text-center">
                        @if(!empty($c->photo) && file_exists(Storage::disk('public')->path($c->photo)))
                            <img src="{{ Storage::disk('public')->path($c->photo) }}" style="width: 110px; height: 70px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                        @else
                            <span style="font-size: 10px; color: #999; font-style: italic;">Tidak ada</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if(!empty($c->kk_photo) && file_exists(Storage::disk('public')->path($c->kk_photo)))
                            <img src="{{ Storage::disk('public')->path($c->kk_photo) }}" style="width: 110px; height: 70px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                        @else
                            <span style="font-size: 10px; color: #999; font-style: italic;">Tidak ada</span>
                        @endif
                    </td>
                    <td>{{ $c->phone }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $reportType === 'pelanggan_sub_pangkalan' ? 5 : 6 }}" class="text-center">Tidak ada data pelanggan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif

</body>
</html>
