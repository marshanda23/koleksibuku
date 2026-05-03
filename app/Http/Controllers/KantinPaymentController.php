<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Exception;

class KantinPaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

    }

    public function createToken(Request $request)
    {
        $request->validate([
            'idpesanan' => 'required'
        ]);

        $pesanan = Pesanan::findOrFail($request->idpesanan);

        // validasi basic (biar gak kirim data sampah ke Midtrans)
        if ($pesanan->status_bayar == 1) {
            return response()->json(['error' => 'Pesanan ini sudah lunas'], 422);
        }

        if (!$pesanan->total || $pesanan->total <= 0) {
            dd('Total tidak valid', $pesanan);
        }

        $orderId = $pesanan->kode_pesanan . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $pesanan->total,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama ?? 'Customer',
            ],
            'item_details' => [
                [
                    'id'       => 'ID-' . $pesanan->idpesanan,
                    'price'    => (int) $pesanan->total,
                    'quantity' => 1,
                    'name'     => 'Pesanan #' . substr($pesanan->kode_pesanan, 0, 20),
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (Exception $e) {
            dd([
                'error' => $e->getMessage(),
                'server_key' => config('services.midtrans.server_key'),
                'is_production' => config('services.midtrans.is_production'),
                'params' => $params,
            ]);
        }

        $pesanan->update(['midtrans_token' => $snapToken]);

        return response()->json([
            'token' => $snapToken
        ]);
    }

    public function notification(Request $request)
    {
        \Log::info('MIDTRANS MASUK', $request->all());
        try {
            $notif             = new Notification();
            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status;

            $rawOrderId  = $notif->order_id;
            $parts       = explode('-', $rawOrderId);
            array_pop($parts);
            $kodePesanan = implode('-', $parts);

            $statusBayar = 0;

            if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
                $statusBayar = 1;
            } elseif ($transactionStatus === 'settlement') {
                $statusBayar = 1;
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $statusBayar = 2;
            }

            Pesanan::where('kode_pesanan', $kodePesanan)
                ->update(['status_bayar' => $statusBayar]);

            return response()->json(['message' => 'Notification Handled']);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $pesanan = Pesanan::where('idpesanan', $request->idpesanan)
            ->where('kode_pesanan', $request->kode_pesanan)
            ->firstOrFail();

        if ($pesanan->status_bayar == 0) {
            $pesanan->update(['status_bayar' => 1]);
        }

        return response()->json(['success' => true]);
    }

    public function cekStatus($idpesanan)
    {
        $pesanan = Pesanan::findOrFail($idpesanan);

        return response()->json([
            'status_bayar' => $pesanan->status_bayar,
            'kode_pesanan' => $pesanan->kode_pesanan,
            'nama'         => $pesanan->nama,
        ]);
    }
}