<?php

namespace App\Services;

use App\Models\StockBatch;
use App\Models\StockLpg;
use App\Models\StockOutflow;

class FifoService
{
    /**
     * Keluarkan stok dengan metode FIFO.
     * Batch yang masuk duluan dikeluarkan duluan.
     */
    public function keluarkan(string $tabungType, int $quantity, string $tanggal, string $source, $sourceableType = null, $sourceableId = null): void
    {
        $sisa = $quantity;

        // Ambil batch yang masih ada sisa, urut dari yang paling lama (FIFO)
        $batches = StockBatch::where('tabung_type', $tabungType)
            ->where('quantity_remaining', '>', 0)
            ->orderBy('received_date')
            ->orderBy('id')
            ->get();

        foreach ($batches as $batch) {
            if ($sisa <= 0) break;

            $diambil = min($sisa, $batch->quantity_remaining);

            // Catat pengeluaran dari batch ini
            StockOutflow::create([
                'stock_batch_id'   => $batch->id,
                'tabung_type'      => $tabungType,
                'quantity'         => $diambil,
                'transaction_date' => $tanggal,
                'source'           => $source,
                'sourceable_type'  => $sourceableType,
                'sourceable_id'    => $sourceableId,
            ]);

            // Kurangi sisa batch
            $batch->decrement('quantity_remaining', $diambil);
            $sisa -= $diambil;
        }
    }

    /**
     * Tambah batch baru saat terima stok dari Pertamina.
     */
    public function tambahBatch(string $tabungType, int $quantity, string $tanggal, int $createdBy): StockBatch
    {
        return StockBatch::create([
            'tabung_type'        => $tabungType,
            'quantity_in'        => $quantity,
            'quantity_remaining' => $quantity,
            'received_date'      => $tanggal,
            'created_by'         => $createdBy,
        ]);
    }
}
