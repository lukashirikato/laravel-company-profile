# 🎯 Sistem QR Code Check-In/Check-Out Terintegrasi

**Status**: ✅ IMPLEMENTASI SELESAI + REVISI TIME WINDOW  
**Tanggal Update**: 3 Maret 2026  
**Tujuan**: Sistem check-in & check-out member berbasis QR code dengan auto-checkout 60 menit SETELAH KELAS SELESAI

---

## 📋 Daftar Fitur yang Diimplementasikan

### ✅ Core Features
- [x] **QR Code Check-In**: Scan QR (member_id) untuk check-in member
- [x] **Time Window Validation**: Check-in dari jam kelas sampai +30 menit setelahnya
- [x] **Auto-Detect Booking**: Sistem otomatis mendeteksi booking berdasarkan time window
- [x] **Quota Management**: Automatic quota deduction saat check-in
- [x] **Manual Check-Out**: Staff bisa manual checkout sebelum 60 menit
- [x] **Auto-Checkout System**: Checkout otomatis setelah 60 menit
- [x] **Double Check-In Prevention**: Mencegah double check-in per schedule per hari
- [x] **Database Transaction**: Race condition prevention dengan DB transaction

### ✅ Advanced Features
- [x] **Already Active Handling**: Menampilkan info jika member sudah check-in (dengan countdown auto-checkout)
- [x] **Auto-Checkout Scheduler**: Scheduler Laravel setiap 30 menit untuk auto-checkout
- [x] **Enhanced Logging**: Detailed logging untuk audit trail
- [x] **WhatsApp Integration**: Notifikasi check-in/check-out via WhatsApp
- [x] **Real-time Statistics**: Stats today check-in success/error
- [x] **Recent Scans Display**: Tampilkan 20 scan terbaru dengan detail

### ✅ UI/UX Improvements
- [x] **Responsive Design**: Work on desktop & mobile devices
- [x] **Status Indicators**: Visual indicators untuk setiap status (success, error, already_active, auto_checkout)
- [x] **Auto-Checkout Countdown**: Display countdown untuk auto-checkout
- [x] **Quota Progress**: Visualisasi quota dengan progress bar
- [x] **Error Messages**: Pesan error yang jelas dan actionable

---

## 🗂️ File-file yang dibuat/diupdate

### 📦 Database
```
database/migrations/2026_03_03_000001_enhance_attendances_table_qr_checkout.php
  → Tambah kolom: auto_checkout_at, duration_minutes, checkout_type
```

### 🎛️ Models
```
app/Models/Attendance.php
  ✅ Tambah casting: auto_checkout_at
  ✅ Tambah methods: 
     - getIsActiveAttribute()
     - getIsAutoCheckoutDueAttribute()
     - getElapsedMinutesAttribute()
     - isWithinTimeWindow()
     - performAutoCheckout()
     - performManualCheckout()
     - hasCheckedInToday()

app/Models/Schedule.php
  ✅ Tambah methods:
     - isWithinTimeWindow() → validasi class_time sampai +30 menit
     - getTimeWindowFormatted() → format display jam window
```

### 🔧 Controllers
```
app/Http/Controllers/QRScan/QRCheckInController.php (NEW)
  ✅ scanCheckIn(int $customerId): array
     - Validasi member & order
     - Check paket aktif & quota
     - Auto-detect booking by time window
     - Prevent double check-in
     - Create attendance & deduct quota
     - Handle already checked in cases
     - Auto-checkout jika > 60 menit
  
  ✅ scanCheckOut(int $attendanceId): array
     - Manual checkout dengan durasi real
  
  ✅ getActiveAttendance(int $customerId): array
     - Get info attendance active hari ini
```

### 📱 Filament Pages
```
app/Filament/Pages/QrScanner.php
  ✅ Update submitScan() → gunakan QRCheckInController
  ✅ Update submitCheckOut() → manual checkout
  ✅ Update sendCheckOutNotification() → WA notification
  ✅ Enhanced recordScan() → better null handling
  ✅ Tambah reactive properties:
     - elapsedSeconds
     - autoCheckoutInMinutes
     - autoCheckoutMessage
```

### 🔄 Scheduler
```
app/Console/Commands/ProcessAutoCheckout.php (NEW)
  ✅ Signature: attendance:process-auto-checkout
  ✅ Options: --dry-run untuk test tanpa action
  ✅ Find attendance dengan auto_checkout_at <= now()
  ✅ Auto-checkout dengan logging detail
  ✅ Summary output

app/Console/Kernel.php
  ✅ Register scheduler: attendance:process-auto-checkout
  ✅ Frequency: everyThirtyMinutes()
  ✅ Time window: between('06:00', '22:00')
  ✅ Timezone: Asia/Jakarta
```

### 🎨 Blade Templates
```
resources/views/filament/pages/qr-scanner.blade.php
  ✅ Tambah section: "ALREADY ACTIVE" status
  ✅ Tambah section: "AUTO-CHECKOUT PERFORMED" status
  ✅ Countdown display: "Auto-Checkout Dalam X menit"
  ✅ Better error handling & null coalescing
```

---

## 🧪 Testing Checklist

### Unit Tests
- [ ] `Attendance::isWithinTimeWindow()` → test class_time to +30 min window
- [ ] `Attendance::performAutoCheckout()` → test auto-checkout logic
- [ ] `Attendance::hasCheckedInToday()` → test duplicate prevention
- [ ] `Schedule::isWithinTimeWindow()` → test schedule time window
- [ ] `QRCheckInController::scanCheckIn()` → test check-in flow

### Integration Tests
- [ ] Full flow: scan QR → check-in → check on DB
- [ ] Full flow: scan QR → check-in → manual checkout before 60 min
- [ ] Full flow: scan QR → check-in → auto-checkout after 60 min
- [ ] Test quota deduction: 1 check-in = 1 quota
- [ ] Test double check-in prevention
- [ ] Test quota exhausted error
- [ ] Test package expired error
- [ ] Test no booking today error
- [ ] Test time window outside range error

### Manual Tests (Admin Panel)
- [ ] Buka admin panel → QR Scanner
- [ ] Scan valid QR code → check-in success
- [ ] Lihat database → attendance record terbuat dengan auto_checkout_at
- [ ] Lihat database → remaining_quota berkurang
- [ ] Lihat recent scans → entry terbaru ditampilkan
- [ ] Lihat stats → success count bertambah
- [ ] Scan lagi member yang sama < 60 min → tampilkan "Already Active"
- [ ] Lihat countdown "Auto-Checkout dalam X menit"
- [ ] Click manual checkout button → checkout success
- [ ] Lihat duration dihitung dengan benar
- [ ] Scan lagi member yang sama menjelang 60 min → auto-checkout triggered
- [ ] Check scheduler 30 menit kemudian → verifify auto-checkout executed

### Manual Tests (Database)
```sql
-- Check attendance dengan auto_checkout_at
SELECT id, customer_id, check_in_at, auto_checkout_at, check_out_at, duration_minutes, checkout_type
FROM attendances
WHERE DATE(check_in_at) = CURDATE()
ORDER BY check_in_at DESC;

-- Check order remaining_quota
SELECT id, customer_id, remaining_quota, quota, status, expired_at
FROM orders
WHERE customer_id = ?
ORDER BY created_at DESC;

-- Check scheduler run (from logs)
tail -f storage/logs/laravel-*.log | grep "Auto-checkout"
```

### Manual Tests (WhatsApp Integration)
- [ ] Member menerima WA notification saat check-in
- [ ] WA message berisi: nama, kelas, quota, jam auto-checkout
- [ ] Member menerima WA notification saat manual checkout
- [ ] WA message berisi: jam masuk, jam keluar, durasi

### Edge Cases Testing
- [ ] Member dengan multiple booking hari ini → auto-detect yang sesuai time window
- [ ] Member dengan expired package → error message
- [ ] Member dengan 0 quota → error message (bukan exclusive)
- [ ] Member exclusive package → quota tidak dikurangi
- [ ] Test dengan clock tidak akurat (timezone issue)
- [ ] Test dengan jaringan lambat (transaction timeout)

### Scheduler Testing
```bash
# Test command dengan dry-run
php artisan attendance:process-auto-checkout --dry-run

# Jalankan command manual
php artisan attendance:process-auto-checkout

# Check output
php artisan schedule:work  # Run scheduler dalam foreground
```

---

## 🔐 Security Considerations

- ✅ **DB Transaction**: Semua operasi check-in/check-out dalam transaksi
- ✅ **Double Check-in Prevention**: Validasi di model level
- ✅ **Input Validation**: Customer ID & Attendance ID harus valid
- ✅ **Authorization**: Hanya admin/staff yang bisa akses QR scanner
- ✅ **Logging**: Semua operasi di-log untuk audit trail
- ✅ **Rate Limiting**: (Optional) Add rate limiting untuk prevent brute force

---

## 📊 Database Schema

```sql
ALTER TABLE attendances ADD COLUMN auto_checkout_at TIMESTAMP NULL AFTER check_out_at;
ALTER TABLE attendances ADD COLUMN duration_minutes INT NULL AFTER auto_checkout_at;
ALTER TABLE attendances ADD COLUMN checkout_type ENUM('manual', 'auto', 'system') NULL AFTER duration_minutes;
```

**Penjelasan kolom baru:**
- `auto_checkout_at`: Waktu ketika auto-checkout akan terjadi (= check_in_at + 60 menit)
- `duration_minutes`: Durasi latihan dalam menit (check_out - check_in)
- `checkout_type`: Tipe checkout (`manual` = staff checkout, `auto` = sistem auto 60 menit, `system` = force admin)

---

## 🚀 How to Use

### API Endpoints (Internal - untuk QrScanner Filament Page)

```php
// Check-in
$controller = new QRCheckInController();
$result = $controller->scanCheckIn($customerId);
// Returns: ['success' => bool, 'type' => string, 'message' => string, 'data' => array]

// Manual Check-out
$result = $controller->scanCheckOut($attendanceId);
// Returns: ['success' => bool, 'message' => string, 'data' => array]

// Get active attendance
$result = $controller->getActiveAttendance($customerId);
// Returns: ['success' => bool, 'data' => array]
```

### Scheduler Commands

```bash
# Run auto-checkout scheduler
php artisan attendance:process-auto-checkout

# Test without actual changes
php artisan attendance:process-auto-checkout --dry-run

# Schedule automatically (every 30 minutes, 06:00-22:00)
# Already registered in app/Console/Kernel.php
```

### Blade Template Variables (QrScanner Page)

```php
// Check-in Success
$scanResults = [
    'success' => true,
    'status' => 'success', // or 'already_active' or 'auto_checkout'
    'member_name' => 'John Doe',
    'member_id' => '1234',
    'program' => 'Reformer Pilates',
    'class_name' => 'Morning Class',
    'package_name' => 'Premium Package',
    'is_exclusive' => false,
    'total_quota' => 12,
    'remaining_quota' => 8,
    'check_in_time' => '09:30',
    'check_in_date' => '03/03/2026',
    'schedule_time' => '09:00',
    'auto_checkout_time' => '10:30', // only for check-out sections
    'elapsed_minutes' => 15, // only for already_active
    'auto_checkout_in' => 45, // only for already_active
];

// Check-out Success
$checkOutResults = [
    'success' => true,
    'member_name' => 'John Doe',
    'member_id' => '1234',
    'class_name' => 'Morning Class',
    'package_name' => 'Premium Package',
    'program' => 'Reformer Pilates',
    'check_in_time' => '09:30',
    'check_out_time' => '10:35',
    'duration' => '1 jam 5 menit',
    'duration_minutes' => 65,
    'remaining_quota' => 7,
    'total_quota' => 12,
];
```

---

## 📝 Log Format

```
✅ QR Check-in successful
{
    "customer_id": 34,
    "customer_name": "John Doe",
    "attendance_id": 156,
    "schedule_id": 42,
    "remaining_quota": 8,
    "timestamp": "2026-03-03T09:30:45+07:00"
}

✅ Auto-checkout processed by scheduler
{
    "attendance_id": 156,
    "customer_id": 34,
    "customer_name": "John Doe",
    "elapsed_minutes": 60,
    "check_in_time": "2026-03-03 09:30:00",
    "auto_checkout_time": "2026-03-03 10:30:00"
}

❌ Error: Quota exhausted
{
    "customer_id": 34,
    "reason": "quota_exhausted",
    "remaining_quota": 0,
    "timestamp": "2026-03-03T09:35:12+07:00"
}
```

---

## ⚠️ Known Limitations & Future Improvements

### Current Limitations
1. **No real-time countdown**: Auto-checkout countdown tidak real-time (requires Livewire polling)
2. **Single location only**: Currently hardcoded 'FTM SOCIETY'
3. **No conflict resolution**: Jika scheduler & manual scan terjadi bersamaan pada member yang sama
4. **No backup QR**: Hanya single QR code per member

### Future Improvements
1. **Real-time countdown**: Implementasi Livewire polling untuk countdown
2. **Multiple locations**: Extend untuk multi-location gym chains
3. **Flexible auto-checkout time**: Configurable timeout per class/package
4. **Mobile app integration**: API endpoint untuk member app check-in
5. **Advanced analytics**: Dashboard untuk member attendance trends
6. **Backup QR codes**: Multiple QR codes per member
7. **API rate limiting**: Prevent brute force attacks
8. **Geolocation validation**: Verify member di lokasi gym

---

## 📚 References

### Related Files
- [QRCheckInController](app/Http/Controllers/QRScan/QRCheckInController.php)
- [ProcessAutoCheckout Command](app/Console/Commands/ProcessAutoCheckout.php)
- [Attendance Model](app/Models/Attendance.php)
- [Schedule Model](app/Models/Schedule.php)
- [QrScanner Filament Page](app/Filament/Pages/QrScanner.php)
- [Blade Template](resources/views/filament/pages/qr-scanner.blade.php)

### API Documentation
- Time Window: class_time sampai +30 menit (member boleh check-in 30 menit setelah kelas mulai)
- Auto-Checkout: 60 menit setelah check_in_at
- Quota Deduction: 1 per check-in (all packages, including exclusive)
- Scheduler: Every 30 minutes, 06:00-22:00 WIB

---

## ✅ Implementation Complete Checklist

- [x] Create migration untuk enhanced attendances table
- [x] Update Attendance model dengan methods & accessors
- [x] Add time window validation ke Schedule model
- [x] Create QRCheckInController dengan enhanced logic
- [x] Update Filament QrScanner page
- [x] Create ProcessAutoCheckout console command
- [x] Register scheduler di Kernel.php
- [x] Update Blade template dengan new UI sections
- [x] Run database migration
- [x] Verify code syntax & no errors
- [x] Clear cache & rebuild configuration
- [x] Create comprehensive documentation

---

**Last Updated**: 3 Maret 2026  
**Status**: READY FOR TESTING ✅
