<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    private ?string $apiToken;
    private ?string $apiUrl;
    private bool $enabled;
    private int $maxRetries = 3;
    private int $retryDelay = 2; // seconds

    public function __construct()
    {
        $this->apiToken = config('fonnte.api_token')
            ?: config('fonthe.api_token')
            ?: null;
        $this->apiUrl = config('fonnte.api_url')
            ?: config('fonthe.api_url')
            ?: 'https://api.fonnte.com/send';
        $this->enabled = (bool) (config('fonnte.enabled') ?? config('fonthe.enabled', false));

        // Log warning if token is missing
        if (!$this->apiToken && $this->enabled) {
            Log::warning('⚠️ FONTTE_API_TOKEN is not configured. WhatsApp notifications will be skipped.');
        }
    }

    /**
     * Kirim pesan WhatsApp
     * 
     * @param string $phoneNumber Nomor telepon tujuan (format: 62XXXXXXXXXX)
     * @param string $message Pesan yang akan dikirim
     * @param array $options Opsi tambahan (retry_count, priority, etc)
     * @return array
     */
    public function send(string $phoneNumber, string $message, array $options = []): array
    {
        if (!$this->enabled || !$this->apiToken) {
            Log::warning('⚠️ WhatsApp notifications disabled or token missing');
            return [
                'success' => false,
                'message' => 'WhatsApp notifications disabled or not configured',
            ];
        }

        try {
            // Normalize phone number
            $phoneNumber = $this->normalizePhoneNumber($phoneNumber);

            if (!$this->isValidPhoneNumber($phoneNumber)) {
                Log::warning('❌ Invalid phone number', ['phone' => $phoneNumber]);
                return [
                    'success' => false,
                    'message' => 'Invalid phone number format',
                    'phone' => $phoneNumber,
                ];
            }

            // Validate message
            if (empty(trim($message)) || strlen($message) > 4096) {
                Log::warning('❌ Invalid message', ['length' => strlen($message)]);
                return [
                    'success' => false,
                    'message' => 'Message must be between 1-4096 characters',
                ];
            }

            $retryCount = $options['retry_count'] ?? 0;

            return $this->sendMessage($phoneNumber, $message, $retryCount);

        } catch (Exception $e) {
            Log::error('❌ WhatsApp Service Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'phone' => $phoneNumber ?? 'N/A',
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send WhatsApp message',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kirim pesan payment successful
     * 
     * @param string $phoneNumber
     * @param array $paymentData
     * @return array
     */
    public function sendPaymentSuccessNotification(string $phoneNumber, array $paymentData): array
    {
        $message = $this->buildPaymentSuccessMessage($paymentData);
        return $this->send($phoneNumber, $message, ['priority' => 'high']);
    }

    /**
     * Kirim reminder booking class
     * 
     * @param string $phoneNumber
     * @param array $bookingData
     * @return array
     */
    public function sendBookingConfirmationNotification(string $phoneNumber, array $bookingData): array
    {
        $message = $this->buildBookingConfirmationMessage($bookingData);
        return $this->send($phoneNumber, $message, ['priority' => 'high']);
    }

    /**
     * Kirim reminder class akan dimulai (24 jam sebelumnya)
     * 
     * @param string $phoneNumber
     * @param array $classData
     * @return array
     */
    public function sendClassReminderNotification(string $phoneNumber, array $classData): array
    {
        $message = $this->buildClassReminderMessage($classData);
        return $this->send($phoneNumber, $message, ['priority' => 'normal']);
    }

    /**
     * Kirim notifikasi check-in berhasil
     * 
     * @param string $phoneNumber
     * @param array $checkInData
     * @return array
     */
    public function sendCheckInNotification(string $phoneNumber, array $checkInData): array
    {
        $message = $this->buildCheckInMessage($checkInData);
        return $this->send($phoneNumber, $message, ['priority' => 'high']);
    }

    /**
     * Kirim notifikasi check-out berhasil
     * 
     * @param string $phoneNumber
     * @param array $checkOutData
     * @return array
     */
    public function sendCheckOutNotification(string $phoneNumber, array $checkOutData): array
    {
        $message = $this->buildCheckOutMessage($checkOutData);
        return $this->send($phoneNumber, $message, ['priority' => 'high']);
    }

    // ==================== PRIVATE METHODS ====================

    /**
     * Actual send message with retry logic
     * 
     * @param string $phoneNumber
     * @param string $message
     * @param int $retryCount
     * @return array
     */
    private function sendMessage(string $phoneNumber, string $message, int $retryCount = 0): array
    {
        try {
            $payload = [
                'target' => $phoneNumber,
                'message' => $message,
            ];

            Log::info('📤 Sending WhatsApp message', [
                'phone' => $phoneNumber,
                'message_length' => strlen($message),
                'attempt' => $retryCount + 1,
            ]);

            $response = Http::withHeaders([
                'Authorization' => $this->apiToken,
            ])
                ->timeout(30)
                ->post($this->apiUrl, $payload);

            if ($response->successful()) {
                Log::info('✅ WhatsApp message sent successfully', [
                    'phone' => $phoneNumber,
                    'response' => $response->json(),
                ]);

                return [
                    'success' => true,
                    'message' => 'WhatsApp message sent successfully',
                    'phone' => $phoneNumber,
                    'response' => $response->json(),
                ];
            }

            // Jika gagal, coba retry
            if ($retryCount < $this->maxRetries) {
                Log::warning('⚠️ WhatsApp send failed, retrying...', [
                    'phone' => $phoneNumber,
                    'attempt' => $retryCount + 1,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                sleep($this->retryDelay);
                return $this->sendMessage($phoneNumber, $message, $retryCount + 1);
            }

            Log::error('❌ WhatsApp message failed after retries', [
                'phone' => $phoneNumber,
                'attempts' => $retryCount + 1,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send WhatsApp message after ' . ($retryCount + 1) . ' attempts',
                'phone' => $phoneNumber,
                'status' => $response->status(),
            ];

        } catch (Exception $e) {
            if ($retryCount < $this->maxRetries) {
                Log::warning('⚠️ WhatsApp send error, retrying...', [
                    'phone' => $phoneNumber,
                    'error' => $e->getMessage(),
                    'attempt' => $retryCount + 1,
                ]);

                sleep($this->retryDelay);
                return $this->sendMessage($phoneNumber, $message, $retryCount + 1);
            }

            throw $e;
        }
    }

    /**
     * Build payment success message
     * 
     * @param array $paymentData
     * @return string
     */
    private function buildPaymentSuccessMessage(array $paymentData): string
    {
        $customerName = $paymentData['customer_name'] ?? 'Valued Customer';
        $packageName = $paymentData['package_name'] ?? 'Package';
        $amount = isset($paymentData['amount']) ? number_format($paymentData['amount'], 0, ',', '.') : '0';
        $orderCode = $paymentData['order_code'] ?? '-';
        $packageDays = $paymentData['package_days'] ?? 'unlimited';

        $daysText = is_numeric($packageDays) && $packageDays > 0 
            ? "selama {$packageDays} hari" 
            : "tanpa batasan waktu";

        return <<<EOT
Halo $customerName,

🎉 *PEMBAYARAN BERHASIL!*

Terima kasih telah melakukan pembayaran untuk paket $packageName.

📋 *Detail Pesanan:*
• Kode Pesanan: $orderCode
• Paket: $packageName
• Total Pembayaran: Rp$amount
• Durasi Akses: $daysText
• Status: ✅ Aktif

📍 *STUDIO FTM SOCIETY*
Anda sekarang dapat menggunakan paket ini untuk mengikuti kelas-kelas di gym kami.

💪 Langkah Selanjutnya:
1. Login ke Website FTM Fitness Society
2. Lakukan booking kelas sesuai jadwal pilihan Anda
3. Hadir 10 menit sebelum kelas dimulai

Jika ada pertanyaan, hubungi kami di support yang tersedia.

Terima kasih,
*FTM Fitness Society*
EOT;
    }

    /**
     * Build booking confirmation message
     * 
     * @param array $bookingData
     * @return string
     */
    private function buildBookingConfirmationMessage(array $bookingData): string
    {
        $customerName = $bookingData['customer_name'] ?? 'Valued Customer';
        $packageName = $bookingData['package_name'] ?? 'Package';
        $totalSchedules = $bookingData['total_schedules'] ?? 0;
        $schedules = $bookingData['schedules'] ?? [];
        
        // Build schedule details
        $scheduleDetails = '';
        if (!empty($schedules)) {
            $scheduleDetails .= "📅 *Jadwal Kelas Anda:*\n\n";
            
            foreach ($schedules as $index => $schedule) {
                $scheduleNum = $index + 1;
                $day = $schedule['day'] ?? 'N/A';
                $time = $schedule['time'] ?? '-';
                $className = $schedule['class_name'] ?? 'Class';
                $level = !empty($schedule['level']) ? " ({$schedule['level']})" : '';
                $instructor = $schedule['instructor'] ?? 'Instructor';
                
                $scheduleDetails .= "$scheduleNum. *{$className}{$level}*\n";
                $scheduleDetails .= "   📆 {$day} · ⏰ {$time}\n";
                $scheduleDetails .= "   👨‍🏫 {$instructor}\n";
                $scheduleDetails .= "   📍 STUDIO FTM SOCIETY\n";
                
                if ($index < count($schedules) - 1) {
                    $scheduleDetails .= "\n";
                }
            }
        }

        return <<<EOT
Halo $customerName,

✅ *BOOKING KELAS BERHASIL!*

Paket: $packageName
Anda telah berhasil melakukan booking untuk $totalSchedules kelas.

$scheduleDetails

🎯 *Reminder Penting:*
✓ Hadir 10 menit sebelum kelas dimulai
✓ Bawa botol minum dan handuk
✓ Gunakan pakaian olahraga yang nyaman
✓ Pastikan membawa ID member

Lihat jadwal lengkap kelas Anda di Website FTM Fitness Society.

Semangat berlatih! 💪

FTM Fitness Society
EOT;
    }

    /**
     * Build class reminder message
     * 
     * @param array $classData
     * @return string
     */
    private function buildClassReminderMessage(array $classData): string
    {
        $customerName = $classData['customer_name'] ?? 'Valued Customer';
        $className = $classData['class_name'] ?? 'Class';
        $time = $classData['class_time'] ?? '-';
        $location = $classData['location'] ?? 'Studio';
        $instructor = $classData['instructor_name'] ?? 'Instructor';

        return <<<EOT
Halo $customerName,

⏰ *REMINDER KELAS BESOK!*

Jangan lupa bahwa Anda memiliki kelas besok:

📋 *Detail Kelas:*
• Nama Kelas: $className
• Jam: $time
• Lokasi: $location
• Instruktur: $instructor

Pastikan untuk:
✓ Hadir 10 menit lebih awal
✓ Bawa botol minum & handuk
✓ Pakaian olahraga yang nyaman

Sampai jumpa besok! 💪

FTM Fitness Society
EOT;
    }

    /**
     * Build check-in notification message
     * 
     * @param array $checkInData
     * @return string
     */
    private function buildCheckInMessage(array $checkInData): string
    {
        $customerName = $checkInData['customer_name'] ?? 'Valued Customer';
        $packageName = $checkInData['package_name'] ?? 'Package';
        $program = $checkInData['program'] ?? 'General';
        $location = $checkInData['location'] ?? 'Studio';
        $checkInTime = $checkInData['check_in_time'] ?? '-';
        $remainingQuota = $checkInData['remaining_quota'] ?? 0;
        $totalQuota = $checkInData['total_quota'] ?? 0;
        $quotaPercentage = $totalQuota > 0 ? ($remainingQuota / $totalQuota * 100) : 0;

        $quotaStatus = '';
        if ($remainingQuota == 0) {
            $quotaStatus = "⚠️ *PERHATIAN:* Quota Anda telah habis!";
        } elseif ($quotaPercentage <= 20) {
            $quotaStatus = "⚠️ *PERINGATAN:* Quota Anda tinggal sedikit!";
        } else {
            $quotaStatus = "✅ Quota masih tersedia";
        }

        return <<<EOT
Halo $customerName,

✅ *CHECK-IN BERHASIL!*

Terima kasih telah hadir di FTM Fitness Society.

📋 *Detail Check-in:*
• Nama Paket: $packageName
• Program: $program
• Lokasi: $location
• Waktu Check-in: $checkInTime

📊 *Status Quota:*
• Quota Tersisa: $remainingQuota / $totalQuota
• Persentase: {$quotaPercentage}%

$quotaStatus

💪 *Motivasi untuk Hari Ini:*
Setiap langkah membawa Anda lebih dekat ke tujuan Anda. Tetap semangat!

Jika memiliki pertanyaan atau butuh bantuan, hubungi staff kami di studio.

Enjoy your workout! 🏋️

*FTM Fitness Society*
EOT;
    }

    /**
     * Build check-out notification message
     * 
     * @param array $checkOutData
     * @return string
     */
    private function buildCheckOutMessage(array $checkOutData): string
    {
        $customerName = $checkOutData['customer_name'] ?? 'Valued Customer';
        $packageName = $checkOutData['package_name'] ?? 'Package';
        $program = $checkOutData['program'] ?? 'General';
        $location = $checkOutData['location'] ?? 'Studio';
        $checkInTime = $checkOutData['check_in_time'] ?? '-';
        $checkOutTime = $checkOutData['check_out_time'] ?? '-';
        $duration = $checkOutData['duration'] ?? '-';
        $remainingQuota = $checkOutData['remaining_quota'] ?? 0;
        $totalQuota = $checkOutData['total_quota'] ?? 0;

        return <<<EOT
Halo $customerName,

✅ *CHECK-OUT BERHASIL!*

Terima kasih telah berlatih di FTM Fitness Society hari ini.

📋 *Detail Sesi Latihan:*
• Nama Paket: $packageName
• Program: $program
• Lokasi: $location
• Check-in: $checkInTime
• Check-out: $checkOutTime

⏱️ *Durasi Latihan:* $duration

📊 *Status Quota:*
• Quota Tersisa: $remainingQuota / $totalQuota

💪 *Pencapaian Hari Ini:*
Bagus sekali! Anda telah menyelesaikan sesi latihan. Terus jaga konsistensi untuk hasil yang lebih baik!

🎯 *Tips untuk Besok:*
✓ Istirahat yang cukup
✓ Minum air yang banyak
✓ Makanan bergizi seimbang
✓ Lihat jadwal kelas berikutnya di website

Sampai jumpa di latihan berikutnya! 💪

*FTM Fitness Society*
EOT;
    }

    /**
     * Normalize phone number to international format
     * 
     * @param string $phoneNumber
     * @return string
     */
    private function normalizePhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        }
        
        // Jika belum ada prefix, tambahkan 62
        if (!str_starts_with($phoneNumber, '62')) {
            $phoneNumber = '62' . $phoneNumber;
        }
        
        return $phoneNumber;
    }

    /**
     * Validate phone number format
     * 
     * @param string $phoneNumber
     * @return bool
     */
    private function isValidPhoneNumber(string $phoneNumber): bool
    {
        // Format harus 62XXXXXXXXXX (minimal 10 digit setelah 62)
        return preg_match('/^62\d{9,}$/', $phoneNumber) === 1;
    }

    /**
     * Get service status
     * 
     * @return array
     */
    public function getStatus(): array
    {
        return [
            'enabled' => $this->enabled,
            'token_configured' => !empty($this->apiToken),
            'api_url' => $this->apiUrl,
            'max_retries' => $this->maxRetries,
        ];
    }
}
