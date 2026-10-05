<?php

namespace App\Support;

final class UserPermission
{
    // Customers
    public const CUSTOMERS_CREATE = 'customers.create';
    public const CUSTOMERS_DELETE = 'customers.delete';

    // Orders & Transactions
    public const ORDERS_CREATE = 'orders.create';
    public const ORDERS_DELETE = 'orders.delete';
    public const TRANSACTIONS_CREATE = 'transactions.create';
    public const TRANSACTIONS_DELETE = 'transactions.delete';

    // Packages & Vouchers
    public const PACKAGES_CREATE = 'packages.create';
    public const PACKAGES_DELETE = 'packages.delete';
    public const VOUCHERS_CREATE = 'vouchers.create';
    public const VOUCHERS_DELETE = 'vouchers.delete';

    // Schedules & Attendance
    public const SCHEDULES_CREATE = 'schedules.create';
    public const SCHEDULES_DELETE = 'schedules.delete';
    public const ATTENDANCE_CREATE = 'attendance.create';
    public const ATTENDANCE_DELETE = 'attendance.delete';

    // Settings
    public const WA_TEMPLATES_MANAGE = 'wa_templates.manage';

    public static function all(): array
    {
        return [
            // Create
            self::CUSTOMERS_CREATE,
            self::ORDERS_CREATE,
            self::TRANSACTIONS_CREATE,
            self::PACKAGES_CREATE,
            self::VOUCHERS_CREATE,
            self::SCHEDULES_CREATE,
            self::ATTENDANCE_CREATE,

            // Delete
            self::CUSTOMERS_DELETE,
            self::ORDERS_DELETE,
            self::TRANSACTIONS_DELETE,
            self::PACKAGES_DELETE,
            self::VOUCHERS_DELETE,
            self::SCHEDULES_DELETE,
            self::ATTENDANCE_DELETE,

            // Settings
            self::WA_TEMPLATES_MANAGE,
        ];
    }

    public static function options(): array
    {
        return [
            // Hak Akses Tambah (Create)
            self::CUSTOMERS_CREATE => '[TAMBAH] Data Member & Follow Up',
            self::ORDERS_CREATE => '[TAMBAH] Pesanan / Invoice (Orders)',
            self::TRANSACTIONS_CREATE => '[TAMBAH] Riwayat Transaksi',
            self::PACKAGES_CREATE => '[TAMBAH] Paket & Membership Program',
            self::VOUCHERS_CREATE => '[TAMBAH] Voucher Promo & Diskon',
            self::SCHEDULES_CREATE => '[TAMBAH] Jadwal Kelas, Group & Label',
            self::ATTENDANCE_CREATE => '[TAMBAH] Catatan Presensi / Check-in',

            // Hak Akses Hapus (Delete)
            self::CUSTOMERS_DELETE => '[HAPUS] Data Member (Customer)',
            self::ORDERS_DELETE => '[HAPUS] Pesanan / Invoice (Orders)',
            self::TRANSACTIONS_DELETE => '[HAPUS] Riwayat Transaksi (Transactions)',
            self::PACKAGES_DELETE => '[HAPUS] Paket Program Membership',
            self::VOUCHERS_DELETE => '[HAPUS] Voucher Promo & Diskon',
            self::SCHEDULES_DELETE => '[HAPUS] Jadwal Kelas',
            self::ATTENDANCE_DELETE => '[HAPUS] Catatan Presensi / Check-in',

            // Settings & Tools
            self::WA_TEMPLATES_MANAGE => '[KELOLA] Template Pesan WhatsApp',
        ];
    }
}
