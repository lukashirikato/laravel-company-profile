<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->onQueue('whatsapp');
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * Send WhatsApp notification
     */
    public function toWhatsApp(object $notifiable): void
    {
        if (!$notifiable->phone_number) {
            Log::warning('❌ Customer has no phone number', [
                'customer_id' => $notifiable->id,
            ]);
            return;
        }

        try {
            $whatsapp = new WhatsAppService();

            $result = $whatsapp->sendPaymentSuccessNotification(
                $notifiable->phone_number,
                [
                    'customer_name' => $notifiable->name,
                    'package_name' => $this->order->package->name,
                    'amount' => $this->order->amount,
                    'order_code' => $this->order->order_code,
                    'package_days' => $this->order->package->duration_days ?? 'unlimited',
                ]
            );

            if ($result['success']) {
                Log::info('✅ Payment success notification sent', [
                    'customer_id' => $notifiable->id,
                    'order_id' => $this->order->id,
                    'phone' => $notifiable->phone_number,
                ]);
            } else {
                Log::error('❌ Failed to send payment notification', [
                    'customer_id' => $notifiable->id,
                    'order_id' => $this->order->id,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Payment notification error: ' . $e->getMessage(), [
                'customer_id' => $notifiable->id,
                'order_id' => $this->order->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
