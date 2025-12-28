<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;
use Filament\Notifications\Notification;

class AdminDashboard extends Component
{
    public function sendPassword($customerId)
    {
        $customer = Customer::findOrFail($customerId);

        // Jika belum verified, beri password default
        if (!$customer->is_verified) {
            $password = $customer->default_password ?? 'Password123';
            $customer->default_password = $password;
            $customer->is_verified = true; // tandai verified
            $customer->save();

            // Buat pesan WhatsApp
            $message = urlencode(
                "Assalamu'alaikum, {$customer->name}.\n\n" .
                "Akun Anda telah diaktifkan.\n" .
                "Email: {$customer->email}\n" .
                "Password: $password\n\n" .
                "Login di sini: " . route('customer.login') // route halaman login member
            );

            $waUrl = "https://wa.me/{$customer->phone_number}?text=$message";

            // Notifikasi admin di dashboard
            Notification::make()
                ->title("Password siap dikirim!")
                ->body("Klik tombol untuk mengirim WhatsApp ke {$customer->name}")
                ->success()
                ->action($waUrl, "Kirim WhatsApp")
                ->send();
        } else {
            Notification::make()
                ->title("Customer sudah verified")
                ->warning()
                ->send();
        }
    }

    public function render()
    {
        $customers = Customer::latest()->take(5)->get();

        return view('livewire.admin-dashboard', compact('customers'));
    }
}
