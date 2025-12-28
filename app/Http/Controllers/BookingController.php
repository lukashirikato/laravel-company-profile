<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Form booking untuk customer tertentu
    public function create($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        return view('bookings.create', compact('customer'));
    }

    // Form booking umum tanpa customer
    public function createGeneral()
    {
        return view('bookings.create');
    }

    // Simpan booking
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'schedule_date'    => 'required|array|min:1',
            'schedule_time'    => 'required|array|min:1',
            'schedule_program' => 'required|array|min:1',
        ]);

        $saved = 0;
        foreach ($request->schedule_date as $i => $date) {
            if (
                !empty($date) &&
                !empty($request->schedule_time[$i]) &&
                !empty($request->schedule_program[$i])
            ) {
                Booking::create([
                    'customer_id'   => $request->customer_id,
                    'schedule_date' => $date,
                    'schedule_time' => $request->schedule_time[$i],
                    'program'       => $request->schedule_program[$i],
                ]);
                $saved++;
            }
        }

        if ($saved > 0) {
            return redirect()->route('customers.index')->with('success', 'Booking jadwal berhasil!');
        } else {
            return back()->with('error', 'Minimal isi satu jadwal!');
        }
    }

    // List booking
    public function index(Request $request)
    {
        $date = $request->input('date');
        $bookings = Booking::with('customer')
            ->when($date, fn($query) => $query->where('schedule_date', $date))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings.index', compact('bookings', 'date'));
    }

    // Hapus booking massal
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if ($ids && is_array($ids)) {
            Booking::whereIn('id', $ids)->delete();
            return redirect()->route('bookings.index')->with('success', 'Booking terpilih berhasil dihapus!');
        }

        return back()->with('error', 'Tidak ada booking yang dipilih.');
    }

    // Hapus satu booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
