<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\CustomerFollowUp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckCustomerFollowUp extends Command
{
    protected $signature = 'customer:check-followup
                            {--dry-run : Only show results without creating records}
                            {--customer= : Check specific customer by ID}';

    protected $description = 'Detect customers who need follow-up (no package, expired package, or inactive) and create Follow Up records';

    public function handle(): int
    {
        $query = Customer::query();

        if ($customerId = $this->option('customer')) {
            $query->where('id', $customerId);
        }

        $customers = $query->get();
        $created = 0;
        $skipped = 0;

        $this->info("Scanning {$customers->count()} customers for follow-up needs...");

        foreach ($customers as $customer) {
            $reason = $this->needsFollowUp($customer);

            if (!$reason) {
                continue;
            }

            // Skip if already has a pending follow-up
            $existing = CustomerFollowUp::where('customer_id', $customer->id)
                ->where('result', 'pending')
                ->exists();

            if ($existing) {
                $skipped++;
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("  [DRY-RUN] Customer #{$customer->id} {$customer->name} — {$reason}");
                continue;
            }

            CustomerFollowUp::create([
                'customer_id'   => $customer->id,
                'follow_up_type' => 'whatsapp',
                'template_used' => 'default',
                'message_sent'  => null,
                'notes'         => 'Auto-detect: ' . $reason,
                'followed_up_by' => null,
                'result'        => 'pending',
            ]);

            $created++;
            $this->line("  ✓ Created follow-up for {$customer->name} — {$reason}");
        }

        $this->newLine();
        $this->info("Done. Created: {$created}, Skipped (already pending): {$skipped}");

        Log::info("[FollowUp] Auto-scan complete", [
            'total_customers' => $customers->count(),
            'created' => $created,
            'skipped_pending' => $skipped,
        ]);

        return Command::SUCCESS;
    }

    private function needsFollowUp(Customer $customer): ?string
    {
        // 1. No package assigned at all
        if (is_null($customer->package_id)) {
            $hasAnyOrder = $customer->orders()->exists();
            if (!$hasAnyOrder) {
                return 'Belum pernah membeli paket sama sekali';
            }
            $hasActiveOrder = $customer->orders()
                ->whereIn('status', ['pending', 'processing', 'completed', 'paid'])
                ->where(function ($q) {
                    $q->whereNull('expired_at')
                      ->orWhere('expired_at', '>', now());
                })
                ->exists();
            if (!$hasActiveOrder) {
                return 'Paket sudah habis/tidak aktif';
            }
        }

        // 2. Has package but check if all orders are expired
        if (!is_null($customer->package_id)) {
            $activeOrders = $customer->orders()
                ->whereIn('status', ['pending', 'processing', 'completed', 'paid'])
                ->where(function ($q) {
                    $q->whereNull('expired_at')
                      ->orWhere('expired_at', '>', now());
                })
                ->exists();

            if (!$activeOrders) {
                // Check if they ever had an order
                $everHadOrder = $customer->orders()->exists();
                if ($everHadOrder) {
                    return 'Semua paket sudah expired';
                }
                // Has package_id set but no orders — might be inconsistent data
                return 'Punya paket tapi tidak ada order aktif';
            }
        }

        // 3. Has active orders but quota is 0 or negative
        $hasQuota = (int) $customer->quota > 0;
        if (!$hasQuota && !is_null($customer->package_id)) {
            $activeOrdersWithQuota = $customer->orders()
                ->whereIn('status', ['pending', 'processing', 'completed', 'paid'])
                ->where(function ($q) {
                    $q->where('remaining_quota', '>', 0)
                      ->orWhere('remaining_classes', '>', 0);
                })
                ->exists();

            if (!$activeOrdersWithQuota) {
                return 'Quota habis';
            }
        }

        return null;
    }
}
