<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'key' => 'payment_success',
                'name' => 'Pembayaran Berhasil',
                'description' => 'Dikirim otomatis saat member berhasil melakukan pembayaran paket',
                'placeholders' => [
                    'customer_name' => 'Nama member',
                    'package_name' => 'Nama paket yang dibeli',
                    'amount' => 'Total pembayaran (contoh: 500.000)',
                    'order_code' => 'Kode pesanan',
                    'package_days' => 'Durasi paket dalam hari, atau "unlimited"',
                ],
                'message' => "Halo {customer_name},\n\n🎉 *PEMBAYARAN BERHASIL!*\n\nTerima kasih telah melakukan pembayaran untuk paket {package_name}.\n\n📋 *Detail Pesanan:*\n• Kode Pesanan: {order_code}\n• Paket: {package_name}\n• Total Pembayaran: Rp{amount}\n• Durasi Akses: {package_days} hari\n• Status: ✅ Aktif\n\n📍 *STUDIO FTM SOCIETY*\nAnda sekarang dapat menggunakan paket ini untuk mengikuti kelas-kelas di gym kami.\n\n💪 Langkah Selanjutnya:\n1. Login ke Website FTM Fitness Society\n2. Lakukan booking kelas sesuai jadwal pilihan Anda\n3. Hadir 10 menit sebelum kelas dimulai\n\nJika ada pertanyaan, hubungi kami di support yang tersedia.\n\nTerima kasih,\n*FTM Fitness Society*",
            ],
            [
                'key' => 'booking_confirmation',
                'name' => 'Booking Kelas Berhasil',
                'description' => 'Dikirim otomatis saat member berhasil booking kelas',
                'placeholders' => [
                    'customer_name' => 'Nama member',
                    'package_name' => 'Nama paket',
                    'total_schedules' => 'Jumlah kelas yang dibooking',
                    'schedule_details' => 'Daftar jadwal kelas (dibuat otomatis oleh sistem)',
                ],
                'message' => "Halo {customer_name},\n\n✅ *BOOKING KELAS BERHASIL!*\n\nPaket: {package_name}\nAnda telah berhasil melakukan booking untuk {total_schedules} kelas.\n\n{schedule_details}\n\n🎯 *Reminder Penting:*\n✓ Hadir 10 menit sebelum kelas dimulai\n✓ Bawa botol minum dan handuk\n✓ Gunakan pakaian olahraga yang nyaman\n✓ Pastikan membawa ID member\n\nLihat jadwal lengkap kelas Anda di Website FTM Fitness Society.\n\nSemangat berlatih! 💪\n\nFTM Fitness Society",
            ],
            [
                'key' => 'class_reminder',
                'name' => 'Reminder Kelas',
                'description' => 'Dikirim otomatis H-1 sebagai pengingat jadwal kelas',
                'placeholders' => [
                    'customer_name' => 'Nama member',
                    'class_name' => 'Nama kelas',
                    'class_time' => 'Waktu kelas',
                    'location' => 'Lokasi studio',
                    'instructor_name' => 'Nama instruktur',
                ],
                'message' => "Halo {customer_name},\n\n⏰ *REMINDER KELAS BESOK!*\n\nJangan lupa bahwa Anda memiliki kelas besok:\n\n📋 *Detail Kelas:*\n• Nama Kelas: {class_name}\n• Jam: {class_time}\n• Lokasi: {location}\n• Instruktur: {instructor_name}\n\nPastikan untuk:\n✓ Hadir 10 menit lebih awal\n✓ Bawa botol minum & handuk\n✓ Pakaian olahraga yang nyaman\n\nSampai jumpa besok! 💪\n\nFTM Fitness Society",
            ],
            [
                'key' => 'check_in_success',
                'name' => 'Check-in Berhasil',
                'description' => 'Dikirim otomatis saat member melakukan check-in di studio',
                'placeholders' => [
                    'customer_name' => 'Nama member',
                    'package_name' => 'Nama paket',
                    'program' => 'Program kelas',
                    'location' => 'Lokasi studio',
                    'check_in_time' => 'Waktu check-in',
                    'remaining_quota' => 'Sisa quota',
                    'total_quota' => 'Total quota',
                ],
                'message' => "Halo {customer_name},\n\n✅ *CHECK-IN BERHASIL!*\n\nTerima kasih telah hadir di FTM Fitness Society.\n\n📋 *Detail Check-in:*\n• Nama Paket: {package_name}\n• Program: {program}\n• Lokasi: {location}\n• Waktu Check-in: {check_in_time}\n\n📊 *Status Quota:*\n• Quota Tersisa: {remaining_quota} / {total_quota}\n\n💪 *Motivasi untuk Hari Ini:*\nSetiap langkah membawa Anda lebih dekat ke tujuan Anda. Tetap semangat!\n\nJika memiliki pertanyaan atau butuh bantuan, hubungi staff kami di studio.\n\nEnjoy your workout! 🏋️\n\n*FTM Fitness Society*",
            ],
            [
                'key' => 'check_out_success',
                'name' => 'Check-out Berhasil',
                'description' => 'Dikirim otomatis saat member melakukan check-out setelah selesai latihan',
                'placeholders' => [
                    'customer_name' => 'Nama member',
                    'package_name' => 'Nama paket',
                    'program' => 'Program kelas',
                    'location' => 'Lokasi studio',
                    'check_in_time' => 'Waktu check-in',
                    'check_out_time' => 'Waktu check-out',
                    'duration' => 'Durasi latihan',
                    'remaining_quota' => 'Sisa quota',
                    'total_quota' => 'Total quota',
                ],
                'message' => "Halo {customer_name},\n\n✅ *CHECK-OUT BERHASIL!*\n\nTerima kasih telah berlatih di FTM Fitness Society hari ini.\n\n📋 *Detail Sesi Latihan:*\n• Nama Paket: {package_name}\n• Program: {program}\n• Lokasi: {location}\n• Check-in: {check_in_time}\n• Check-out: {check_out_time}\n\n⏱️ *Durasi Latihan:* {duration}\n\n📊 *Status Quota:*\n• Quota Tersisa: {remaining_quota} / {total_quota}\n\n💪 *Pencapaian Hari Ini:*\nBagus sekali! Anda telah menyelesaikan sesi latihan. Terus jaga konsistensi untuk hasil yang lebih baik!\n\n🎯 *Tips untuk Besok:*\n✓ Istirahat yang cukup\n✓ Minum air yang banyak\n✓ Makanan bergizi seimbang\n✓ Lihat jadwal kelas berikutnya di website\n\nSampai jumpa di latihan berikutnya! 💪\n\n*FTM Fitness Society*",
            ],
        ];

        foreach ($templates as $template) {
            WhatsAppTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}
