<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Package;

class VoucherAdminController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with('packages')->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $packages = Package::where('active', 1)->get();
        return view('admin.vouchers.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code',
            'type' => 'required|in:percent,nominal',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'active' => 'boolean',
            'applicable_to' => 'required|in:all,specific',
            'packages' => 'required_if:applicable_to,specific|array',
            'packages.*' => 'exists:packages,id'
        ]);

        $voucher = Voucher::create([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'used_count' => 0,
            'valid_from' => $validated['valid_from'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,
            'active' => $validated['active'] ?? true,
            'applicable_to' => $validated['applicable_to']
        ]);

        // Attach packages jika applicable_to = specific
        if ($validated['applicable_to'] === 'specific' && !empty($validated['packages'])) {
            $voucher->packages()->attach($validated['packages']);
        }

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dibuat');
    }

    public function edit(Voucher $voucher)
    {
        $packages = Package::where('active', 1)->get();
        $selectedPackages = $voucher->packages->pluck('id')->toArray();
        
        return view('admin.vouchers.edit', compact('voucher', 'packages', 'selectedPackages'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'type' => 'required|in:percent,nominal',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'active' => 'boolean',
            'applicable_to' => 'required|in:all,specific',
            'packages' => 'required_if:applicable_to,specific|array',
            'packages.*' => 'exists:packages,id'
        ]);

        $voucher->update([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'valid_from' => $validated['valid_from'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,
            'active' => $validated['active'] ?? true,
            'applicable_to' => $validated['applicable_to']
        ]);

        // Sync packages
        if ($validated['applicable_to'] === 'specific') {
            $voucher->packages()->sync($validated['packages'] ?? []);
        } else {
            $voucher->packages()->detach();
        }

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diupdate');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus');
    }
}