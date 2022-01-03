<?php

namespace App\Exports;
use DB;
use App\Models\Main\Stok;
use App\Models\Master\Produk;
use App\Models\Transaction\Penjualan;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Transaction\PenjualanDetail;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportPenjualan implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(int $month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        $data = [];
        $penjualan  = Penjualan::with([
            'penjualan_detail' => function ($q)
                {
                    $q->select(DB::raw('sum(detailpenjualan_qty) as total, detailpenjualan_id, penjualan_id, produk_id'));
                    $q->groupBy('produk_id');
                },
            'member',
            'user'
        ])
        ->whereMonth('created_at', $this->month)
        ->orderBy('penjualan_id', 'DESC')
        ->get();

        $stoks = Stok::all();
        $produk = Produk::all();
        foreach ($produk as $value) {
            $new = [
                'produk_nama' => $value->produk_nama,
                'produk_jual' => $value->produk_jual,
                'produk_beli' => $value->produk_beli,
                'Ket_hasil_penjualan' => 0,
                'Ket_barang_pokok' => 0,
                'terjual' => 0,
                'revenue' => 0,
                'supply' => 0,
                'stock' => 0
            ];
            foreach ($penjualan as $sale) {
                foreach ($sale->penjualan_detail as $detail) {
                    if ($value->produk_id == $detail->produk_id) {
                        $new['Ket_hasil_penjualan'] += ($value->produk_jual * $detail->total);
                        $new['Ket_barang_pokok'] += ($value->produk_beli * $detail->total);
                        $new['terjual'] += $detail->total;
                    }
                }
            }
            foreach ($stoks as $stok) {
                if ($stok->produk_id == $value->produk_id) {
                    $new['supply'] = $new['produk_beli'] * $stok->stok_jumlah;
                    $new['stock'] = $stok->stok_jumlah;
                }
            }
            $new['revenue'] = $new['Ket_hasil_penjualan'] - $new['Ket_barang_pokok'];
            array_push($data, $new);
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Uraian Barang',
            'Terjual',
            'Harga Barang',
            'Ket Hasil Penjualan',
            'Harga Pokok',
            'Ket Barang Terjual',
            'Laba',
            'Stok Akhir',
            'Persediaan Akhir'
        ];
    }

    /**
    * @var Penjualan $penjualan
    */
    public function map($penjualan): array
    {
        return [
            $penjualan['produk_nama'],
            $penjualan['terjual'],
            $penjualan['produk_jual'],
            $penjualan['Ket_hasil_penjualan'],
            $penjualan['produk_beli'],
            $penjualan['Ket_barang_pokok'],
            $penjualan['revenue'],
            $penjualan['stock'],
            $penjualan['supply'],
        ];
    }
}
