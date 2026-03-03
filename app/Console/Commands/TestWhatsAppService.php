<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class TestWhatsAppService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test
                            {phone : Phone number to test (e.g., 08123456789)}
                            {--type=payment : Type of notification (payment|booking)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp service dengan nomor telepon real';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $type = $this->option('type');

        $this->info("🧪 Testing WhatsApp Service");
        $this->info("📱 Phone: {$phone}");
        $this->info("📋 Type: {$type}");
        $this->newLine();

        try {
            $whatsapp = new WhatsAppService();
            
            // Check service status
            $status = $whatsapp->getStatus();
            
            if (!$status['enabled']) {
                $this->error("❌ WhatsApp notifications are disabled");
                $this->info("Enable in .env: FONNTE_ENABLE_NOTIFICATIONS=true");
                return Command::FAILURE;
            }

            if (!$status['token_configured']) {
                $this->error("❌ API token not configured");
                $this->info("Set in .env: FONNTE_API_TOKEN=your_token");
                return Command::FAILURE;
            }

            $this->info("✅ Service Status:");
            $this->info("   - Enabled: " . ($status['enabled'] ? 'Yes' : 'No'));
            $this->info("   - Token: " . ($status['token_configured'] ? 'Configured' : 'Not configured'));
            $this->info("   - API URL: {$status['api_url']}");
            $this->newLine();

            // Send test message
            $this->info("📤 Sending test message...");
            $this->info("⏳ This may take a few moments...");
            $this->newLine();

            if ($type === 'payment') {
                $result = $whatsapp->sendPaymentSuccessNotification($phone, [
                    'customer_name' => 'Test Customer',
                    'package_name' => 'Premium Package (TEST)',
                    'amount' => 500000,
                    'order_code' => 'TEST-' . now()->format('YmdHis'),
                    'package_days' => 30,
                ]);
            } else {
                $result = $whatsapp->sendBookingConfirmationNotification($phone, [
                    'customer_name' => 'Test Customer',
                    'class_name' => 'Mat Pilates (TEST)',
                    'class_date' => 'Monday, Feb 16 2026',
                    'class_time' => '09:00',
                    'location' => 'Studio A',
                    'instructor_name' => 'Test Instructor',
                    'schedule_count' => 2,
                ]);
            }

            $this->newLine();

            if ($result['success']) {
                $this->info("✅ WhatsApp message sent successfully!");
                $this->info("   - Phone: {$result['phone']}");
                $this->info("   - Message: {$type} notification");
                $this->info("   - Response: " . json_encode($result['response'], JSON_PRETTY_PRINT));
                return Command::SUCCESS;
            } else {
                $this->error("❌ Failed to send message");
                $this->error("   - Error: {$result['message']}");
                if (isset($result['status'])) {
                    $this->error("   - HTTP Status: {$result['status']}");
                }
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error("❌ Exception occurred");
            $this->error("   - Error: " . $e->getMessage());
            $this->error("   - File: " . $e->getFile());
            $this->error("   - Line: " . $e->getLine());
            return Command::FAILURE;
        }
    }
}
