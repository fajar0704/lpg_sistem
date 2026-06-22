<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Distribusi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        .info { text-align: center; margin-bottom: 20px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 10px; }
        .badge-in { background-color: #d4edda; color: #155724; }
        .badge-out { background-color: #f8d7da; color: #721c24; }
        .badge-approved { background-color: #d4edda; color: #155724; }
        .badge-pending { background-color: #fff3cd; color: #856404; }
        .badge-rejected { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h2>LAPORAN DISTRIBUSI GAS LPG</h2>
    <div class="info">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Sub Pangkalan</th>
                <th>User</th>
                <th>Jenis</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distributions as $index => $dist)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $dist->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $dist->subPangkalan->name }}</td>
                <td>{{ $dist->user->name }}</td>
                <td>
                    <span class="badge badge-{{ $dist->type }}">
                        {{ $dist->type === 'in' ? 'Masuk' : 'Keluar' }}
                    </span>
                </td>
                <td>{{ $dist->tabung_type }}</td>
                <td>{{ $dist->quantity }}</td>
                <td>
                    <span class="badge badge-{{ $dist->status }}">
                        {{ ucfirst($dist->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 11px;">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
