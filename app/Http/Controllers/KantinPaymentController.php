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
        // Konfigurasi dari .env
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', 'false') === 'true';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
        
        // Pengaturan Curl untuk keamanan koneksi di Localhost
        Config::$curlOptions  = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
    }

    /**
     * Membuat Snap Token untuk Midtrans
     */
    public function createToken(Request $request)
    {
        // Validasi input
        $request->validate(['idpesanan' => 'required']);

        try {
            // Ambil data pesanan berdasarkan ID
            $pesanan = Pesanan::findOrFail($request->idpesanan);

            // Cek jika pesanan sudah lunas
            if ($pesanan->status_bayar == 1) {
                return response()->json(['error' => 'Pesanan ini sudah lunas'], 422);
            }

            /**
             * PENYEDERHANAAN ITEM DETAILS
             * Mengirimkan satu item total untuk menghindari error "Undefined array key"
             * yang disebabkan oleh relasi database yang tidak terbaca sempurna.
             */
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
                    // Tambahkan rand() agar Order ID selalu unik saat testing
                    'order_id'     => $pesanan->kode_pesanan . '-' . rand(),
                    'gross_amount' => (int) $pesanan->total,
                ],
                'customer_details' => [
                    'first_name' => $pesanan->nama ?? 'Customer',
                ],
                'item_details' => $itemDetails,
            ];

            // Request token ke Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan token ke database (opsional)
            $pesanan->update(['midtrans_token' => $snapToken]);

            return response()->json(['token' => $snapToken]);

        } catch (Exception $e) {
            // Mengembalikan pesan error detail jika gagal
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage(),
                'debug'   => 'Cek baris: ' . $e->getLine()
            ], 500);
        }
    }

    /**
     * Menangani Webhook/Notifikasi Otomatis dari Midtrans
     */
    public function notification(Request $request)
    {
        try {
            $notif             = new Notification();
            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status;
            
            // Ambil kode pesanan asli (sebelum tanda '-')
            $rawOrderId        = $notif->order_id;
            $orderId           = explode('-', $rawOrderId)[0];

            $statusBayar = 0; // default pending

            if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
                $statusBayar = 1; // lunas
            } elseif ($transactionStatus === 'settlement') {
                $statusBayar = 1; // lunas
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $statusBayar = 2; // gagal
            }

            Pesanan::where('kode_pesanan', $orderId)->update(['status_bayar' => $statusBayar]);

            return response()->json(['message' => 'Notification Handled']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fallback manual untuk update status dari Frontend
     */
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

    /**
     * Cek status pembayaran untuk polling di frontend
     */
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