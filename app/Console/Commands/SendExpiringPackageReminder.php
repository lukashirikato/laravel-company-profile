<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendExpiringPackageReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:send-expiring-reminder {--days=3 : Days before expiration to send reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notification for packages that will expire soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysBeforeExpiry = $this->option('days');
        
        $this->info("🔍 Checking for packages expiring in {$daysBeforeExpiry} days...");

        // Cari paket yang akan expired dalam X hari
        $expiringOrders = Order::with(['customer', 'package'])
            ->where('status', 'active')
            ->whereNotNull('expired_at')
            ->whereBetween('expired_at', [
                Carbon::now(),
                Carbon::now()->addDays($daysBeforeExpiry)
            ])
            ->get();

        if ($expiringOrders->isEmpty()) {
            $this->info('✅ No packages expiring soon.');
            return Command::SUCCESS;
        }

        $this->info("Found {$expiringOrders->count()} packages expiring soon.");

        $progressBar = $this->output->createProgressBar($expiringOrders->count());
        $progressBar->start();

        $sentCount = 0;

        foreach ($expiringOrders as $order) {
            try {
                $customer = $order->customer;
                $package = $order->package;
                
                if (!$customer || !$customer->email) {
                    Log::warning('Customer tidak memiliki email', [
                        'order_id' => $order->id,
                        'customer_id' => $order->customer_id,
                    ]);
                    continue;
                }

                $expiryDate = Carbon::parse($order->expired_at);
                $remainingDays = Carbon::now()->diffInDays($expiryDate);

                // TODO: Kirim email/WhatsApp notification
                // Contoh: Send Email
                /*
                Mail::to($customer->email)->send(new ExpiringPackageReminder([
                    'customer_name' => $customer->name,
                    'package_name' => $package->name,
                    'remaining_days' => $remainingDays,
                    'expiry_date' => $expiryDate->format('d F Y'),
                    'order_code' => $order->order_code,
                ]));
                */

                // TODO: Kirim WhatsApp via API (Fonnte/Wablas)
                /*
                $this->sendWhatsAppReminder($customer->phone_number, [
                    'name' => $customer->name,
                    'package' => $package->name,
                    'days' => $remainingDays,
                    'date' => $expiryDate->format('d M Y'),
                ]);
                */

                Log::info('Reminder sent', [
                    'order_id' => $order->id,
                    'customer_id' => $customer->id,
                    'customer_email' => $customer->email,
                    'remaining_days' => $remainingDays,
                ]);

                $sentCount++;
                $progressBar->advance();

            } catch (\Exception $e) {
                Log::error('Error sending expiry reminder', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✅ Successfully sent {$sentCount} reminders.");

        return Command::SUCCESS;
    }

    /**
     * Send WhatsApp reminder via API
     * 
     * @param string $phoneNumber
     * @param array $data
     */
    private function sendWhatsAppReminder($phoneNumber, $data)
    {
        // TODO: Implement WhatsApp API integration
        // Contoh menggunakan Fonnte API
        /*
        $message = "Halo {$data['name']}! 👋\n\n";
        $message .= "Paket *{$data['package']}* Anda akan berakhir dalam *{$data['days']} hari* ({$data['date']}).\n\n";
        $message .= "Perpanjang sekarang untuk tetap menikmati akses ke kelas kami! 💪\n\n";
        $message .= "Terima kasih,\nFTM Society";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array(
                'target' => $phoneNumber,
                'message' => $message,
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . env('FONNTE_API_KEY')
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
        */
    }
}