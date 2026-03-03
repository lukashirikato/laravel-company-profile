<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        // ✅ VALIDASI INPUT - termasuk package_id
        $request->validate([
            'code' => 'required|string|max:50',
            'package_id' => 'nullable|integer|exists:packages,id',
        ]);

        $code = strtoupper(trim($request->code));
        $packageId = $request->package_id; // ✅ AMBIL PACKAGE_ID

        Log::info('Voucher check attempt', [
            'code' => $code,
            'package_id' => $packageId,
        ]);

        // Cari voucher
        $voucher = Voucher::whereRaw('UPPER(code) = ?', [$code])
            ->where('active', 1)
            ->with('packages') // ✅ EAGER LOAD PACKAGES
            ->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode voucher tidak valid'
            ], 404);
        }

        // Validasi periode
        if ($voucher->valid_from && now()->lt($voucher->valid_from)) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher belum bisa digunakan'
            ], 422);
        }

        if ($voucher->valid_until && now()->gt($voucher->valid_until)) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher sudah kadaluarsa'
            ], 422);
        }

        // Validasi usage limit
        if ($voucher->usage_limit !== null &&
            $voucher->used_count >= $voucher->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher sudah mencapai batas pemakaian'
            ], 422);
        }

        // ✅ VALIDASI PACKAGE - INI YANG PALING PENTING!
        if ($packageId && !$voucher->isApplicableToPackage($packageId)) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher tidak berlaku untuk paket ini'
            ], 422);
        }

        // ✅ Return success with flat structure
        return response()->json([
            'valid' => true,
            'message' => 'Voucher berhasil diaplikasikan',
            'type' => $voucher->type,
            'value' => (float) $voucher->value,
            'max_discount' => $voucher->max_discount ? (float) $voucher->max_discount : null,
            'code' => $voucher->code,
            'applicable_to' => $voucher->applicable_to,
        ], 200);
    }

    // ✅ TAMBAHKAN METHOD INI - untuk calculate discount
    public function calculateDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'price' => 'required|numeric|min:0',
            'package_id' => 'nullable|integer|exists:packages,id',
        ]);

        $voucher = Voucher::whereRaw('UPPER(code) = ?', [strtoupper($request->code)])
            ->where('active', 1)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan'
            ], 404);
        }

        // Validasi voucher
        $validation = $voucher->validate($request->package_id);

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 422);
        }

        // Calculate discount
        $discountAmount = $voucher->calculateDiscount($request->price);
        $finalPrice = max(0, $request->price - $discountAmount);

        return response()->json([
            'success' => true,
            'data' => [
                'original_price' => (float) $request->price,
                'discount_amount' => (float) $discountAmount,
                'final_price' => (float) $finalPrice,
                'voucher_code' => $voucher->code,
            ]
        ]);
    }
}