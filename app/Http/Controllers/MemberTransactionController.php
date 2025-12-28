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

        // Ambil transaksi milik customer yang login
        $transactions = Transaction::where('customer_id', $member->id)
                                   ->latest()
                                   ->get();

        return view('member.transactions.index', compact('transactions'));
    }
}
