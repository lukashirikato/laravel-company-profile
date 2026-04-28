<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
            'agree'          => 'nullable',
        ]);

        // Normalisasi nilai is_muslim ke boolean
        $validated['is_muslim'] = $validated['is_muslim'] === 'ya';

        // Simpan ke database hanya untuk kolom yang memang ada di tabel customers
        $customerData = [
            'name'           => $validated['name'],
            'phone_number'   => $validated['phone_number'],
            'email'          => $validated['email'],
            'birth_date'     => $validated['birth_date'],
            'goals'          => $validated['goals'] ?? null,
            'kondisi_khusus' => $validated['kondisi_khusus'] ?? null,
            'referensi'      => $validated['referensi'] ?? null,
            'pengalaman'     => $validated['pengalaman'] ?? null,
            'is_verified'    => false,
        ];

        if (Schema::hasColumn('customers', 'is_muslim')) {
            $customerData['is_muslim'] = $validated['is_muslim'];
        }

        if (Schema::hasColumn('customers', 'voucher_code')) {
            $customerData['voucher_code'] = $validated['voucher'] ?? null;
        }

        Customer::create($customerData);

        return redirect()->to(route('home') . '#signup')
            ->with('success', 'Sign up berhasil! Data kamu sudah masuk.');
    }
}
