<?php

namespace App\Notifications;

use App\Models\CustomerSchedule;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private CustomerSchedule $customerSchedule;
    private int $totalSchedules;

    /**
     * Create a new notification instance.
     */
    public function __construct(CustomerSchedule $customerSchedule, int $totalSchedules = 1)
    {
        $this->customerSchedule = $customerSchedule;
        $this->totalSchedules = $totalSchedules;
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
            $schedule = $this->customerSchedule->schedule;
            $class = $schedule->class;

            // Format class date and time
            $classDate = $this->formatClassDate($schedule->day);
            $classTime = $schedule->class_time ? Carbon::parse($schedule->class_time)->format('H:i') : '-';

            $whatsapp = new WhatsAppService();

            $result = $whatsapp->sendBookingConfirmationNotification(
                $notifiable->phone_number,
                [
                    'customer_name' => $notifiable->name,
                    'class_name' => $class->name ?? 'Class',
                    'class_date' => $classDate,
                    'class_time' => $classTime,
                    'location' => $class->location ?? 'Studio',
                    'instructor_name' => $class->instructor_name ?? 'Instructor',
                    'schedule_count' => $this->totalSchedules,
                ]
            );

            if ($result['success']) {
                Log::info('✅ Booking confirmation notification sent', [
                    'customer_id' => $notifiable->id,
                    'schedule_id' => $this->customerSchedule->schedule_id,
                    'phone' => $notifiable->phone_number,
                ]);
            } else {
                Log::error('❌ Failed to send booking notification', [
                    'customer_id' => $notifiable->id,
                    'schedule_id' => $this->customerSchedule->schedule_id,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Booking notification error: ' . $e->getMessage(), [
                'customer_id' => $notifiable->id,
                'schedule_id' => $this->customerSchedule->schedule_id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Format day name to readable date
     * 
     * @param string $dayName
     * @return string
     */
    private function formatClassDate(string $dayName): string
    {
        $today = Carbon::now()->startOfDay();
        $nextOccurrence = $today;

        // Map day names to number (1 = Monday, 7 = Sunday)
        $dayMap = [
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7,
        ];

        $targetDay = $dayMap[$dayName] ?? null;

        if ($targetDay === null) {
            return $dayName;
        }

        // Find next occurrence of the day
        $currentDay = $today->copy()->dayOfWeek;
        $daysToAdd = $targetDay - $currentDay;

        if ($daysToAdd <= 0) {
            $daysToAdd += 7;
        }

        $nextOccurrence = $today->copy()->addDays($daysToAdd);

        return $nextOccurrence->format('d M Y');
    }
}
