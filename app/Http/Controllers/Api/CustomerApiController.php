<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerApiController extends Controller
{
    /**
     * Get customer list dengan filtering berdasarkan status
     * 
     * Status filter:
     * - all: semua customer
     * - active: customer dengan is_login_active = 1
     * - inactive: customer dengan is_login_active = 0
     * - no-package: customer tanpa paket aktif (untuk follow-up)
     */
    public function getList(Request $request)
    {
        $status = $request->query('status', 'all');
        
        $query = Customer::select([
            'id',
            'name',
            'email',
            'phone_number',
            'is_login_active',
            'package_id',
            'created_at',
            'updated_at'
        ]);

        // Filter berdasarkan status
        switch ($status) {
            case 'active':
                $query->where('is_login_active', 1);
                break;
            case 'inactive':
                $query->where('is_login_active', 0);
                break;
            case 'no-package':
                // Customer tanpa order aktif - untuk follow-up
                $query->whereDoesntHave('orders', function ($q) {
                    $q->whereIn('status', ['paid', 'active', 'settlement', 'success', 'completed']);
                });
                break;
            case 'all':
            default:
                // Tidak ada filter
                break;
        }

        // Ordering: recent first
        $customers = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone_number' => $customer->phone_number,
                    'phone_wa' => $this->formatPhoneNumber($customer->phone_number),
                    'whatsapp_url' => $this->generateWhatsAppUrl($customer->phone_number, $customer->name),
                    'is_login_active' => $customer->is_login_active,
                    'package_id' => $customer->package_id,
                    'created_at' => $customer->created_at?->format('d M Y'),
                    'last_activity' => $customer->updated_at 
                        ? $this->formatLastActivity($customer->updated_at)
                        : 'Belum ada aktivitas'
                ];
            });

        return response()->json([
            'status' => 'success',
            'filter' => $status,
            'count' => $customers->count(),
            'customers' => $customers
        ]);
    }

    /**
     * Format last activity datetime menjadi format yang lebih readable
     */
    private function formatLastActivity($dateTime)
    {
        $date = Carbon::parse($dateTime);
        $now = Carbon::now();
        $diffInDays = $now->diffInDays($date);
        $diffInHours = $now->diffInHours($date);
        $diffInMinutes = $now->diffInMinutes($date);

        if ($diffInMinutes < 60) {
            return "{$diffInMinutes} menit yang lalu";
        } elseif ($diffInHours < 24) {
            return "{$diffInHours} jam yang lalu";
        } elseif ($diffInDays < 7) {
            return "{$diffInDays} hari yang lalu";
        } elseif ($diffInDays < 30) {
            $weeks = intdiv($diffInDays, 7);
            return "{$weeks} minggu yang lalu";
        } else {
            return $date->format('d M Y');
        }
    }

    /**
     * Format phone number untuk WhatsApp (0xxx -> 62xxx)
     */
    private function formatPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return '';
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Convert 0xxx to 62xxx (Indonesian format)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Generate WhatsApp URL dengan message template
     */
    private function generateWhatsAppUrl($phoneNumber, $customerName)
    {
        $phone = $this->formatPhoneNumber($phoneNumber);

        if (empty($phone)) {
            return '';
        }

        $message = "Halo {$customerName}! 👋\n\n" .
                   "Kami rindu Anda di FTM Society! 💪\n\n" .
                   "Apakah ada yang bisa kami bantu terkait membership Anda? " .
                   "Kami memiliki program spesial untuk member setia seperti Anda.\n\n" .
                   "Jangan ragu untuk menghubungi kami ya! 😊";

        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }
}
