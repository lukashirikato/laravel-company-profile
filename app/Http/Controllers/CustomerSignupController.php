<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerSignupController extends Controller
{
public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20',
            'email'          => 'required|email|unique:customers,email',
            'birth_date'     => 'required|date',
            'goals'          => 'nullable|string',
            'kondisi_khusus' => 'nullable|string',
            'referensi'      => 'nullable|string',
            'pengalaman'     => 'nullable|string',
            'is_muslim'      => 'required|string', // "ya" / "tidak"
            'voucher'        => 'nullable|string|max:50',
            'agree'          => 'nullable|boolean',
        ]);

        // Normalisasi nilai is_muslim ke boolean
        $validated['is_muslim'] = $validated['is_muslim'] === 'ya';

        // Simpan ke database
        Customer::create([
            'name'           => $validated['name'],
            'phone_number'   => $validated['phone_number'],
            'email'          => $validated['email'],
            'birth_date'     => $validated['birth_date'],
            'goals'          => $validated['goals'] ?? null,
            'kondisi_khusus' => $validated['kondisi_khusus'] ?? null,
            'referensi'      => $validated['referensi'] ?? null,
            'pengalaman'     => $validated['pengalaman'] ?? null,
            'is_muslim'      => $validated['is_muslim'],
            'voucher_code'   => $validated['voucher'] ?? null,
            'is_verified'    => false,
        ]);

        return redirect()->back()->with('success', 'Sign up berhasil! Data kamu sudah masuk.');
    }
}
