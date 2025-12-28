<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\WhatsAppHelper;
use Carbon\Carbon;

class CustomerController extends Controller
{
    // ✅ METHOD VERIFIKASI CUSTOMER
    public function verifyCustomer($id)
    {
        /** @var Customer $customer */
        $customer = Customer::findOrFail($id);

        if ($customer->is_verified) {
            return back()->with('info', 'Customer sudah diverifikasi.');
        }

        $password = Str::random(8);
        $customer->password = Hash::make($password);
        $customer->is_verified = true;
        $customer->save();

        $message = "Assalamu'alaikum, {$customer->name}.\n\nAkun Anda telah diaktifkan.\n\n📧 Email: {$customer->email}\n🔑 Password: {$password}\n\nLogin: " . url('/login');
        WhatsAppHelper::send($customer->phone_number, $message);

        return back()->with('success', 'Customer berhasil diverifikasi dan info login dikirim via WhatsApp.');
    }

    // 🔑 CHANGE PASSWORD
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    // 📋 LIST CUSTOMER + JADWAL
    public function index()
    {
        $schedules = Schedule::all();

        $customers = Customer::with('schedules')->get()->map(function (Customer $c) {
            return [
                'id'        => $c->id,
                'user_id'   => $c->user_id ?? '-',
                'name'      => $c->name,
                'email'     => $c->email,
                'phone_number' => $c->phone_number,
                'program'   => $c->program ?? '-',
                'quota'     => $c->quota ?? 0,
                'membership'=> $c->membership ?? '-',
                'preferred_membership' => $c->preferred_membership ?? 'Not sure',
                'birth_date'=> $c->birth_date ?? '-',
                'age'       => $c->birth_date ? Carbon::parse($c->birth_date)->age : '-',
                'schedules' => $c->schedules->pluck('name')->implode(', ') ?: '-',
                'goals'     => $c->goals ?? '-',
                'kondisi_khusus' => $c->kondisi_khusus ?? '-',
                'referensi' => $c->referensi ?? '-',
                'pengalaman'=> $c->pengalaman ?? '-',
                'is_muslim' => $c->is_muslim ?? '-',
                'voucher_code' => $c->voucher_code ?? '-',
                'verified'  => $c->is_verified ? '✔' : '❌',
                'is_verified' => (bool) $c->is_verified,
                'created_at'=> optional($c->created_at)->toDateTimeString(),
                'updated_at'=> optional($c->updated_at)->toDateTimeString(),
            ];
        });

        return view('admin.customers.index', compact('schedules', 'customers'));
    }

    public function create()
    {
        $schedules = Schedule::all();
        return view('admin.customers.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255|unique:customers',
            'phone_number'         => 'required|string|max:15',
            'preferred_membership' => 'nullable|string',
            'birth_date'           => 'required|date',
            'goals'                => 'nullable|string',
            'kondisi_khusus'       => 'nullable|string',
            'referensi'            => 'nullable|string|max:255',
            'pengalaman'           => 'nullable|string|max:255',
            'is_muslim'            => 'required|in:ya,tidak',
            'voucher_code'         => 'nullable|string|max:100',
            'schedule_ids'         => 'nullable|array',
            'schedule_ids.*'       => 'exists:schedules,id',
        ]);

        if (Schema::hasColumn('customers', 'user_id') && auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        /** @var Customer $customer */
        $customer = Customer::create($validated);

        if (!empty($validated['schedule_ids'])) {
            $customer->schedules()->sync($validated['schedule_ids']);
        }

        return $request->route()->getName() === 'public.customers.store'
            ? back()->with('success', 'Pendaftaran berhasil! Kami akan menghubungi Anda.')
            : redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan!');
    }

    public function checkin($id)
    {
        /** @var Customer $customer */
        $customer = Customer::findOrFail($id);

        if ($customer->quota > 0) {
            $customer->quota = max(0, $customer->quota - 1);
            $customer->save();

            return back()->with('success', 'Check-in berhasil! Sisa kuota: ' . $customer->quota);
        }

        return back()->with('warning', 'Kuota sudah habis, tidak bisa check-in.');
    }

    public function edit($id)
    {
        /** @var Customer $customer */
        $customer = Customer::with('schedules')->findOrFail($id);
        $schedules = Schedule::all();
        $bookings = \App\Models\Booking::where('customer_id', $customer->id)->get();

        return view('admin.customers.edit', compact('customer', 'schedules', 'bookings'));
    }


    public function program()
{
    $customer = auth()->guard('customer')->user();

    // contoh ambil transaksi/order paid milik customer
    $orders = \App\Models\Order::with('package')
        ->where('customer_id', $customer->id)
        ->where('status', 'paid') // hanya yang sudah bayar
        ->latest()
        ->get();

    return view('member.program', compact('orders'));
}



    public function update(Request $request, $id)
    {
        /** @var Customer $customer */
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone_number'         => 'required|string|max:15',
            'program'              => 'required|string|max:255',
            'quota'                => 'required|integer|min:0',
            'membership'           => 'required|string',
            'preferred_membership' => 'nullable|string',
            'schedule_ids'         => 'nullable|array',
            'schedule_ids.*'       => 'exists:schedules,id',
            'schedule_date'        => 'nullable|array',
            'schedule_time'        => 'nullable|array',
        ]);

        if (Schema::hasColumn('customers', 'user_id') && auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        $customer->update($validated);
        $customer->schedules()->sync($request->input('schedule_ids', []));

        return redirect()->route('customers.index')->with('success', 'Customer & jadwal booking berhasil diperbarui!');
    }

    public function destroy($id)
    {
        /** @var Customer $customer */
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus!');
    }

    public function profile()
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('member.login.form')->withErrors([
                'login' => 'Silakan login terlebih dahulu.'
            ]);
        }

        return view('member.profile', compact('customer'));
    }
}
