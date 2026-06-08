<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberTransactionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil customer yang sedang login
        $member = Auth::guard('customer')->user();

        if (!$member) {
            $transactions = collect();
            return view('member.transactions.index', compact('transactions'));
        }

        // Ambil transaksi milik customer yang login dengan eager loading paket
        // dan gunakan pagination untuk menghindari full page reload & beban berat
        $transactions = Transaction::where('customer_id', $member->id)
                                   ->with('package') // eager load related package
                                   ->latest()
                                   ->paginate(15); // 15 per page, lazy load via pagination links

        // Filament / Blade pagination links will be rendered in view
        return view('member.transactions.index', compact('transactions'));
    }
}
