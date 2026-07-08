<?php

namespace App\Helpers;

use App\Models\Customer;

class MessageTemplate
{
    protected static array $templates = [
        'followup_reengagement' => [
            'label' => 'Re-engagement',
            'description' => 'Menghubungi member yang lama tidak aktif',
            'message' => "Assalamu'alaikum {name} 😊\n\nKami dari FTM Society kangen banget sama kamu! Sudah lama ya tidak terlihat di gym.\n\nKami punya beberapa program baru yang mungkin kamu suka:\n💪 Kelas baru: {new_classes}\n🏷️ Promo spesial untuk kamu!\n\nYuk, balik lagi ke FTM Society! Klik link berikut untuk lihat paket terbaru:\n{package_url}\n\nSalam olahraga,\n*FTM Society*",
        ],
        'followup_promotion' => [
            'label' => 'Promo / Diskon',
            'description' => 'Info promo paket terbaru',
            'message' => "Halo {name} 👋\n\nKabar baik! FTM Society sekarang punya promo spesial:\n🔥 {promo_name}\n💰 Harga spesial: {promo_price}\n\nJangan sampai kelewatan! Klik link di bawah untuk detail:\n{package_url}\n\nTerima kasih,\n*FTM Society*",
        ],
        'followup_new_class' => [
            'label' => 'Kelas Baru',
            'description' => 'Info jadwal kelas baru',
            'message' => "Hi {name}!\n\nAda kelas baru nih di FTM Society yang cocok buat kamu:\n✨ {class_name}\n📅 Jadwal: {class_schedule}\n\nCek detail dan booking kelasnya sekarang juga!\n{booking_url}\n\nSalam sehat,\n*FTM Society*",
        ],
        'followup_checkup' => [
            'label' => 'Check Up',
            'description' => 'Menanyakan kabar member',
            'message' => "Assalamu'alaikum {name} 🙌\n\nGimana kabarnya? Semoga selalu sehat ya.\n\nKami cuma mau nanyain, gimana pengalaman kamu selama di FTM Society? Ada masukan atau saran?\n\nJangan ragu untuk reply ya, kami very open untuk feedback dari kamu! 😊\n\nTerima kasih sudah menjadi bagian dari FTM Society! 💪\n\n*FTM Society*",
        ],
        'followup_reminder' => [
            'label' => 'Penginapan Expired',
            'description' => 'Member dengan paket hampir habis',
            'message' => "Halo {name}!\n\nKami ingetin ya, paket FTM Society kamu akan segera berakhir pada {expiry_date}.\n\nJangan sampai kehabisan! Segera perpanjang sebelum masa aktif habis.\n\nKlik link berikut untuk lihat paket perpanjangan:\n{package_url}\n\nTerima kasih,\n*FTM Society*",
        ],
        'followup_birthday' => [
            'label' => 'Ulang Tahun',
            'description' => 'Ucapan ulang tahun untuk member',
            'message' => "🎉🎂 Happy Birthday, {name}! 🎂🎉\n\nSelamat ulang tahun dari keluarga besar FTM Society! 🎈\n\nSebagai hadiah spesial, kami kasih kamu {birthday_offer}!\nCek detailnya di sini:\n{package_url}\n\nSehat selalu dan tetap semangat berolahraga! 💪😊\n\n*FTM Society*",
        ],
        'custom' => [
            'label' => 'Custom',
            'description' => 'Tulis pesan manual',
            'message' => '',
        ],
    ];

    public static function all(): array
    {
        return collect(static::$templates)
            ->map(fn ($t, $key) => ['key' => $key] + $t)
            ->toArray();
    }

    public static function get(string $key): ?array
    {
        return static::$templates[$key] ?? null;
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (static::$templates as $key => $template) {
            $options[$key] = $template['label'] . ' — ' . $template['description'];
        }
        return $options;
    }

    public static function render(string $key, Customer $customer, array $extra = []): string
    {
        $template = static::get($key);
        if (!$template) {
            return '';
        }

        $message = $template['message'];
        if (empty($message) && $key !== 'custom') {
            return '';
        }

        $variables = [
            '{name}'          => $customer->name ?? 'Member',
            '{phone}'         => $customer->phone_number ?? '-',
            '{email}'         => $customer->email ?? '-',
            '{package}'       => $customer->package?->name ?? '-',
            '{quota}'         => (string) ($customer->quota ?? '0'),
            '{membership}'    => $customer->membership ?? '-',
            '{package_url}'   => url('/member/packages'),
            '{booking_url}'   => url('/member/book-class'),
            '{new_classes}'   => 'Zumba, Yoga, Pilates',
            '{promo_name}'    => $extra['promo_name'] ?? 'Paket Spesial',
            '{promo_price}'   => $extra['promo_price'] ?? 'Rp 0',
            '{class_name}'    => $extra['class_name'] ?? 'Kelas Baru',
            '{class_schedule}'=> $extra['class_schedule'] ?? 'Senin & Rabu, 16:00',
            '{expiry_date}'   => $extra['expiry_date'] ?? '-',
            '{birthday_offer}'=> $extra['birthday_offer'] ?? 'diskon spesial 20%',
        ];

        return str_replace(array_keys($variables), array_values($variables), $message);
    }
}
