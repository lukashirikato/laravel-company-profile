# 🔄 REVISI: Time Window & Auto-Checkout Logic

**Tanggal Revisi**: 3 Maret 2026  
**Reason**: Ubah logika time window dari ±30 menit menjadi class_time + 30 menit, dan auto-checkout 60 menit SETELAH kelas selesai (bukan 60 menit dari check-in)

---

## 📋 Perubahan Logika

### ❌ LAMA (Sebelum Revisi)

**Time Window Check-in:**
```
Window: ±30 menit dari jam kelas
Class 10:00 → Window 09:30 - 10:30
Member check-in 09:30 ✓
Member check-in 10:45 ✗ ERROR
```

**Auto-Checkout:**
```
Mulai: check_in_at
Auto-checkout: check_in_at + 60 menit
Contoh: Check-in 09:35 → Auto-checkout 10:35
```

---

### ✅ BARU (Setelah Revisi)

**Time Window Check-in:**
```
Window: class_time sampai +30 menit (member boleh check-in mulai kelas dimulai)
Logika: "Member boleh check-in jika KELAS SUDAH DIMULAI sampai 30 MENIT SETELAH"

Class 10:00 → Window 10:00 - 10:30 ✓
Member check-in 10:15 ✓ (dalam window)
Member check-in 10:45 ✗ ERROR (sudah di luar 30 menit)
```

**Auto-Checkout Timing:**
```
Asumsi: Setiap kelas durasi 60 menit
Kalkulasi: 
  - Class start: 10:00
  - Class end: 11:00 (start + 60 menit)
  - Auto-checkout: 12:00 (end + 60 menit)

Logika: "Member HARUS checkout dalam 60 MENIT SETELAH KELAS SELESAI"
  - Jika belum checkout sampai 12:00, auto-checkout otomatis terjadi
```

---

## 🔧 File yang Diubah

### 1. **app/Models/Schedule.php**

**Methods Baru:**

```php
/**
 * Get class start time (from class_time column)
 */
public function getClassStartTime(): ?Carbon
{
    // Returns: Carbon instance dari schedule_date + class_time
}

/**
 * Get class end time (assume 60 minutes duration)
 */
public function getClassEndTime(): ?Carbon
{
    // Returns: class_start_time + 60 menit
}

/**
 * Check apakah jam sekarang dalam time window check-in
 * Window: class_time sampai +30 menit
 */
public function isWithinTimeWindow(): bool
{
    // Logic: now() between class_start dan class_start + 30 menit
}

/**
 * Get formatted time window
 */
public function getTimeWindowFormatted(): string
{
    // Returns: "10:00 - 10:30"
}
```

### 2. **app/Models/Attendance.php**

**Method Update:**

```php
/**
 * Check apakah member check-in dalam time window
 */
public function isWithinTimeWindow(): bool
{
    // Delegate ke: $this->schedule->isWithinTimeWindow()
}
```

### 3. **app/Http/Controllers/QRScan/QRCheckInController.php**

**Logic Update di `scanCheckIn()` - STEP 8:**

```php
// LAMA:
$autoCheckoutAt = now()->addMinutes(60);

// BARU:
$classStart = $schedule->getClassStartTime();
$classEnd = $classStart ? $classStart->copy()->addMinutes(60) : now()->addMinutes(60);
$autoCheckoutAt = $classEnd->copy()->addMinutes(60);
```

---

## 📊 Perbandingan Skenario

### Skenario 1: Member Check-in On Time

```
Class: Reformer Pilates, 10:00 - 11:00
Member check-in: 10:15

✓ VALID (dalam window 10:00 - 10:30)

Database record:
- check_in_at: 10:15
- auto_checkout_at: 12:00 (11:00 + 60 min)
- status: ACTIVE

Timeline:
- 10:15: Check-in
- 10:15 - 12:00: Member bisa manual checkout
- 12:00: Auto-checkout jika belum manual checkout
```

### Skenario 2: Member Check-in Terlambat

```
Class: Yoga, 14:00 - 15:00
Member check-in: 14:45

✗ INVALID (di luar window 14:00 - 14:30)
Error: "Di luar time window check-in. Window: 14:00 - 14:30"

Staff bisa manual check-in jika diperlukan (setelah +30 menit)
```

### Skenario 3: Member Not Checkout

```
Class: Boxing, 16:00 - 17:00
Member check-in: 16:10
Member tidak manual checkout

Timeline:
- 16:10: Check-in
- 17:00: Class berakhir
- 17:00 - 18:00: Window untuk manual checkout
- 18:00: Auto-checkout otomatis terjadi
  - check_out_at = 18:00
  - duration = 60 menit (default dari system, bukan real 110 menit)
  - checkout_type = 'auto'
  
Status di DB:
- check_in_at: 16:10
- auto_checkout_at: 18:00
- check_out_at: 18:00 (set oleh scheduler)
- duration_minutes: 60
- checkout_type: 'auto'
```

### Skenario 4: Member Manual Checkout Sebelum 60 Menit

```
Class: Zumba, 18:00 - 19:00
Member check-in: 18:20
Member manual checkout: 18:55 (35 menit latihan)

Timeline:
- 18:20: Check-in (auto_checkout_at = 20:00)
- 18:55: Manual checkout oleh staff
  - check_out_at = 18:55
  - duration_minutes = 35 (18:55 - 18:20)
  - checkout_type = 'manual'
  
Status di DB:
- check_in_at: 18:20
- auto_checkout_at: 20:00 (tidak digunakan)
- check_out_at: 18:55
- duration_minutes: 35
- checkout_type: 'manual'
```

---

## ⏱️ Timing Calculation Detail

Untuk setiap class dengan waktu mulai `class_time`:

```
假设 Class Duration: 60 menit (hardcoded)

Class Start (dari schedule_date + class_time):
  Contoh: 2026-03-03 10:00

Class End (start + 60 min):
  2026-03-03 11:00

Check-In Window (start sampai +30 min):
  2026-03-03 10:00 - 10:30

Auto-Checkout Time (class_end + 60 min):
  2026-03-03 12:00

Manual Checkout Window (anytime):
  2026-03-03 10:00 - 23:59 (tetapi ideal sebelum auto_checkout_at)
```

---

## 🧪 Testing Checklist (Updated)

### Basic Tests

```bash
# Test 1: Check-in dalam window ✓
Class: 10:00
Scan at: 10:15
Expected: SUCCESS
Window: 10:00 - 10:30

# Test 2: Check-in di luar window ✗
Class: 10:00
Scan at: 10:45
Expected: ERROR "Di luar time window check-in. Window: 10:00 - 10:30"

# Test 3: Auto-checkout setelah 60 min class berakhir
Class: 10:00 - 11:00
Check-in: 10:10
Scan lagi at: 12:05 (>> auto_checkout_at)
Expected: AUTO-CHECKOUT TRIGGERED
Duration: 60 menit

# Test 4: Manual checkout sebelum auto-checkout
Class: 14:00 - 15:00
Check-in: 14:20
Manual checkout at: 14:50 (30 menit latihan)
Expected: SUCCESS, duration = 30 menit
```

### Database Verification

```sql
-- Check auto_checkout_at untuk attendances hari ini
SELECT 
  id, customer_id, 
  check_in_at, 
  auto_checkout_at, 
  check_out_at, 
  duration_minutes,
  checkout_type
FROM attendances
WHERE DATE(check_in_at) = CURDATE()
ORDER BY check_in_at DESC;

-- Verifi: auto_checkout_at = (class_time + 60 min) + 60 min
-- Contoh: Class 10:00 → auto_checkout_at 12:00
```

---

## 📝 Log Format (Updated)

```
✅ QR Check-in successful
{
    "customer_id": 34,
    "class_start_time": "2026-03-03 10:00:00",
    "class_end_time": "2026-03-03 11:00:00",
    "check_in_at": "2026-03-03 10:15:00",
    "auto_checkout_at": "2026-03-03 12:00:00",
    "remaining_quota": 8
}

✅ Auto-checkout processed by scheduler
{
    "attendance_id": 156,
    "class_time": "10:00",
    "check_in_time": "10:15",
    "auto_checkout_time": "12:00",
    "duration_minutes": 60,
    "checkout_type": "auto"
}
```

---

## 🔐 Validation Rules (Updated)

### Check-In Validation

✅ Member ID valid
✅ Package active & not expired  
✅ Remaining quota > 0
✅ Booking exists untuk hari ini
✅ **Dalam time window (class_time sampai +30 menit)** ← CHANGED
✅ Belum ada active attendance (sama hari, sama schedule)

### Auto-Checkout Validation

✅ attendance.check_out_at IS NULL
✅ attendance.auto_checkout_at <= now()
✅ Set checkout_type = 'auto'
✅ Set duration_minutes = 60 (default)

---

## 📚 Implementation Notes

1. **Class Duration Assumption**: Semua class diasumsikan durasi **60 menit**
   - Jika ada class dengan durasi berbeda, perlu extend DB schema

2. **Schedule Date**: Harus ada kolom `schedule_date` di tabel schedules
   - Berisi tanggal spesifik kelas

3. **Time Zone**: All timestamps in "Asia/Jakarta" timezone

4. **Scheduler Frequency**: Every 30 minutes, 06:00 - 22:00 WIB
   - Edit di `app/Console/Kernel.php` jika perlu ubah

---

**Last Updated**: 3 Maret 2026 (Post-Revision)  
**Status**: READY FOR RE-TESTING ✅
