<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function check(Request $request)
{
    $code = strtoupper(trim($request->code));

    $voucher = Voucher::whereRaw('UPPER(code) = ?', [$code])
        ->where('active', 1)
        ->first();

    if (!$voucher) {
        return response()->json([
            'valid' => false,
            'message' => 'Kode voucher tidak valid'
        ]);
    }

    if ($voucher->valid_from && now()->lt($voucher->valid_from)) {
        return response()->json([
            'valid' => false,
            'message' => 'Voucher belum bisa digunakan'
        ]);
    }

    if ($voucher->valid_until && now()->gt($voucher->valid_until)) {
        return response()->json([
            'valid' => false,
            'message' => 'Voucher sudah kadaluarsa'
        ]);
    }

    if ($voucher->usage_limit !== null &&
        $voucher->used_count >= $voucher->usage_limit) {
        return response()->json([
            'valid' => false,
            'message' => 'Voucher sudah mencapai batas pemakaian'
        ]);
    }

    return response()->json([
        'valid'        => true,
        'type'         => $voucher->type, // percent | nominal
        'value'        => (int) $voucher->value,
        'max_discount' => $voucher->max_discount ? (int) $voucher->max_discount : null,
    ]);
}

}