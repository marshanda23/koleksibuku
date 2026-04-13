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
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', 'false') === 'true';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
        Config::$curlOptions  = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => 'C:/laragon/etc/ssl/cacert.pem',
        ];
    }

    public function createToken(Request $request)
    {
        $request->validate(['idpesanan' => 'required']);

        try {
            $pesanan = Pesanan::findOrFail($request->idpesanan);

            if ($pesanan->status_bayar == 1) {
                return response()->json(['error' => 'Pesanan ini sudah lunas'], 422);
            }

            $itemDetails = [
                [
                    'id'       => 'ID-' . $pesanan->idpesanan,
                    'price'    => (int) $pesanan->total,
                    'quantity' => 1,
                    'name'     => 'Pembayaran Pesanan #' . substr($pesanan->kode_pesanan, 0, 20),
                ]
            ];

            $params = [
                'transaction_details' => [
                    'order_id'     => $pesanan->kode_pesanan . '-' . rand(),
                    'gross_amount' => (int) $pesanan->total,
                ],
                'customer_details' => [
                    'first_name' => $pesanan->nama ?? 'Customer',
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);

            $pesanan->update(['midtrans_token' => $snapToken]);

            return response()->json(['token' => $snapToken]);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            $notif             = new Notification();
            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status;

            $rawOrderId = $notif->order_id;
            $orderId    = explode('-', $rawOrderId)[0];

            $statusBayar = 0;

            if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
                $statusBayar = 1;
            } elseif ($transactionStatus === 'settlement') {
                $statusBayar = 1;
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $statusBayar = 2;
            }

            Pesanan::where('kode_pesanan', $orderId)->update(['status_bayar' => $statusBayar]);

            return response()->json(['message' => 'Notification Handled']);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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