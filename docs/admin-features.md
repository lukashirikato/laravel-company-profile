# FTM Society — Admin Panel Features

Dokumen ini menjelaskan seluruh fitur admin panel FTM Society berbasis Filament v2.

---

## Daftar Isi

1. [Struktur Navigasi](#1-struktur-navigasi)
2. [Management](#2-management)
   - [Customers](#21-customers)
   - [Attendance](#22-attendance)
   - [Follow Up](#23-follow-up)
   - [Feedback](#24-feedback)
   - [Member Classes](#25-member-classes)
   - [Packages](#26-packages)
   - [Schedules](#27-schedules)
   - [Classes](#28-classes)
   - [Class Groups](#29-class-groups)
   - [Memberships](#210-memberships)
3. [Sales](#3-sales)
   - [Orders](#31-orders)
   - [Transactions](#32-transactions)
4. [Promotions](#4-promotions)
   - [Vouchers](#41-vouchers)
5. [Operations](#5-operations)
   - [QR Scanner](#51-qr-scanner)
6. [Export Data](#6-export-data)
7. [WhatsApp Integration](#7-whatsapp-integration)
8. [Tips Penggunaan](#8-tips-penggunaan)
9. [Pengembangan Selanjutnya](#9-pengembangan-selanjutnya)

---

## 1. Struktur Navigasi

Sidebar admin terdiri dari grup berikut:

```
FTMAdmin
├── Dashboard
├── Management
│   ├── Customers        → Manajemen member
│   ├── Attendance       → ✅ Baru: Riwayat absensi check-in/out
│   ├── Follow Up        → ✅ Baru: Follow-up member tidak aktif
│   ├── Feedback         → ✅ Baru: Feedback dari pengunjung/member
│   ├── Member Classes   → Relasi member ke jadwal kelas
│   ├── Packages         → Daftar paket/produk gym
│   ├── Schedules        → Jadwal kelas
│   ├── Classes          → ✅ Baru: Master data kelas (Zumba, Yoga, dll)
│   ├── Class Groups     → ✅ Baru: Kategori/group kelas
│   └── Memberships      → ✅ Baru: Paket membership berlangganan
├── Sales
│   ├── Orders           → Pesanan member
│   └── Transactions     → Riwayat transaksi pembayaran
├── Promotions
│   └── Vouchers         → Kode diskon/voucher
└── Operations
    └── QR Scanner       → Scanner check-in/out QR
```

---

## 2. Management

### 2.1 Customers

Manajemen data member secara lengkap.

**Fitur:**
- Tambah, edit, hapus member
- Data profil: nama, email, telepon, tanggal lahir, goals, kondisi khusus, referensi, pengalaman
- Assignment paket dengan quota otomatis
- Status membership (Basic/Premium/Trial)
- Status verifikasi akun
- Filter: membership, muslim, paket, quota habis, belum verifikasi

**Actions per baris:**
- **Adjust Classes** — Menyesuaikan sisa kelas/quota (dengan alasan)
- **Check In** — Check-in cepat dari halaman member (decrement quota)
- **Send Login** — Kirim kredensial login via WhatsApp (untuk member baru)
- **Export CSV** — Export data member terpilih ke file CSV

**Relasi:** Orders (lihat pesanan member langsung dari halaman edit customer)

---

### 2.2 Attendance ✅ (BARU)

Riwayat absensi check-in/check-out member.

**Fitur:**
- Lihat semua riwayat kehadiran member
- Filter: status (active/completed/cancelled), member, rentang tanggal
- Manual check-out untuk member yang lupa check-out
- Export CSV data kehadiran

**Kolom:** Member, Email, Kelas, Status, Check In, Check Out, Durasi, Lokasi, Tipe, Quota

**Export:** Bisa export per data terpilih ke CSV.

---

### 2.3 Follow Up ✅ (BARU)

Manajemen follow-up untuk member tidak aktif atau perlu perhatian.

**Fitur:**
- Catat follow-up per member (WhatsApp, Telepon, Email, Kunjungan)
- Pilih template pesan (Default, Promotion, New Class, Check Up)
- Catat hasil follow-up (Sukses, Tidak Direspon, Re-opened, Pending)
- Tampilkan badge jumlah follow-up pending di sidebar
- Filter: tipe, hasil, member, periode
- Export CSV

**Akses cepat:** Klik nama member langsung menuju halaman edit customer.

---

### 2.4 Feedback ✅ (BARU)

Menampilkan feedback/pesan dari pengunjung website atau member.

**Fitur:**
- Lihat daftar feedback (nama, email, subjek, pesan)
- View modal detail feedback
- Filter: periode
- Export CSV
- Hapus feedback yang tidak relevan

---

### 2.5 Member Classes

Relasi antara member dengan jadwal kelas (booking kelas).

**Fitur:**
- Daftar member yang booking kelas tertentu
- Status booking (pending/confirmed/attended/cancelled)
- Filter: status, member, jadwal
- Lihat detail order terkait

---

### 2.6 Packages

Manajemen paket/produk gym.

**Fitur:**
- Nama, harga, quota, durasi
- Tipe paket (membership/session/contact)
- Variant label, group, participant count
- Exclusive package toggle
- Schedule mode (locked/booking)
- Auto-apply toggle
- Assign ke class tertentu
- Badge jumlah order per paket

---

### 2.7 Schedules

Jadwal kelas reguler.

**Fitur:**
- Atur jadwal per hari, jam, instructor
- Assign ke multiple packages
- Show/hide di landing page
- Auto-expand 1 bulan (parent/child series)
- Bulk edit, bulk show/hide

---

### 2.8 Classes ✅ (BARU)

Master data untuk jenis-jenis kelas.

**Fitur:**
- Nama kelas (contoh: Zumba, Yoga, Pilates, Boxing)
- Level (Beginner, Intermediate, Advanced, All Levels)
- Assign ke Class Group
- Lihat jumlah jadwal per kelas

**Kegunaan:** Data ini dipakai oleh Schedule, Package, dan sebagai referensi untuk pengaturan kelas secara terpusat.

---

### 2.9 Class Groups ✅ (BARU)

Kategori/group untuk mengelompokkan kelas.

**Fitur:**
- Nama group (contoh: Cardio, Strength, Flexibility)
- Level
- Lihat jumlah kelas dalam group

**Kegunaan:** Memudahkan pengelompokan kelas di laporan dan filter.

---

### 2.10 Memberships ✅ (BARU)

Paket membership berlangganan (berulang).

**Fitur:**
- Nama, harga, durasi (hari), deskripsi
- Status aktif/non-aktif
- Lihat jumlah member yang mengambil membership ini

**Note:** Resource ini melengkapi model `Membership` yang sudah ada sebelumnya tapi belum memiliki admin panel.

---

## 3. Sales

### 3.1 Orders

Pesanan yang dibuat oleh member.

**Fitur:**
- Data lengkap: kode order, customer, paket, amount, diskon
- Status workflow: pending → processing → completed/paid/cancelled
- Quota & sisa kelas per order
- Filter: status, payment type, quota applied
- Action Adjust Classes untuk menyesuaikan sisa

### 3.2 Transactions

Riwayat transaksi pembayaran (termasuk integrasi Midtrans).

**Fitur:**
- Detail transaksi: order code, customer, amount, status
- Status pembayaran (pending/paid/settlement/success/failed/expired/cancelled)
- Tracking payment type, transaction ID, fraud status

---

## 4. Promotions

### 4.1 Vouchers

Kode diskon dan voucher.

**Fitur:**
- Kode unik, nama, deskripsi
- Tipe diskon: persen atau nominal
- Maksimal diskon, limit pemakaian
- Pembatasan ke paket tertentu (all/specific packages)
- Periode berlaku
- Status aktif/non-aktif

---

## 5. Operations

### 5.1 QR Scanner

Check-in/check-out member via scan QR.

**Fitur:**
- Scan QR member untuk check-in
- Multi-booking resolver (jika member punya banyak kelas di hari sama)
- Time window validation
- Auto-checkout
- WhatsApp notification
- Today's stats & recent scans feed

---

## 6. Export Data

Beberapa resource sudah mendukung export CSV via bulk action:

| Resource | Cara Export |
|----------|-------------|
| Customers | Pilih member → klik "Export CSV" di bulk actions |
| Attendance | Pilih data → klik "Export CSV" |
| Follow Up | Pilih data → klik "Export CSV" |
| Feedback | Pilih data → klik "Export CSV" |

**Cara:** Centang checkbox pada baris yang ingin di-export, lalu klik tombol "Export CSV" di bagian bawah tabel.

---

## 7. WhatsApp Integration

Admin bisa mengirim pesan WhatsApp langsung ke customer dengan template terintegrasi.

### Cara Kirim (2 tempat)

| Halaman | Tombol | Lokasi |
|---------|--------|--------|
| **Follow Up** | Kirim WhatsApp (icon chat, hijau) | Per baris data |
| **Customers** | Kirim WA (icon chat, oranye) | Per baris data |

### Langkah
1. Klik tombol kirim WA pada baris customer
2. Muncul modal dengan:
   - **Nama & nomor** customer (read-only)
   - **Template pesan** — pilih dari 6 template siap pakai
   - **Pesan** — otomatis terisi sesuai template, bisa diedit manual
3. Klik **Send** → otomatis redirect ke WhatsApp Web/Desktop dengan pesan terisi

### Template Tersedia

| Template | Kegunaan | Variable Kunci |
|----------|----------|----------------|
| **Re-engagement** | Member lama tidak aktif | `{name}`, `{new_classes}` |
| **Promo / Diskon** | Info promo paket | `{promo_name}`, `{promo_price}` |
| **Kelas Baru** | Info jadwal kelas baru | `{class_name}`, `{class_schedule}` |
| **Check Up** | Tanya kabar member | `{name}` |
| **Pengingat Expired** | Paket hampir habis | `{expiry_date}`, `{package_url}` |
| **Ulang Tahun** | Ucapan birthday + hadiah | `{birthday_offer}` |
| **Custom** | Tulis bebas | - |

### Variable yang Tersedia
Semua template otomatis mengganti variable berikut dengan data asli customer:

```
{name}        → Nama member
{phone}       → Nomor telepon  
{email}       → Email
{package}     → Nama paket
{quota}       → Sisa quota
{package_url} → Link halaman paket
{booking_url} → Link booking kelas
```

### Logging
Setiap WA yang dikirim otomatis tercatat di:
- **Follow Up** — sebagai record baru (result: success) dengan isi pesan
- **Customers** — sebagai record Follow Up baru (jika dikirim dari halaman Customer)

---

## 8. Tips Penggunaan

### Dashboard
- Dashboard custom menampilkan KPI utama: Total Member, Member Aktif, Member Tidak Aktif, Pendapatan
- Klik card statistik untuk membuka modal daftar member (all/active/inactive)
- Grafik pertumbuhan member (Chart.js)

### Follow Up
- Gunakan fitur **Follow Up** untuk menindaklanjuti member yang tidak aktif
- Dashboard menampilkan reminder "X member tidak aktif — Perlu follow-up" yang bisa langsung diklik
- Atur hasil follow-up untuk tracking efektivitas

### Feedback
- Cek halaman **Feedback** secara rutin untuk melihat pesan dari pengunjung
- Export ke CSV jika perlu rekap bulanan

### Attendance
- Gunakan filter tanggal untuk melihat kehadiran periode tertentu
- Fitur "Check Out Manual" berguna jika member lupa check-out
- Export CSV untuk laporan bulanan

---

## 9. Pengembangan Selanjutnya

Fitur-fitur yang direkomendasikan untuk pengembangan ke depan:

- [ ] **Roles & Permissions** — Install Spatie Laravel Permission untuk membedakan akses Owner, Manager, Staff, Front Desk
- [ ] **Laporan Keuangan** — Halaman khusus laporan revenue, top packages, churn analysis
- [ ] **Broadcast WhatsApp** — Kirim pesan massal ke member terpilih
- [ ] **Reminder Otomatis** — Notifikasi quota habis, paket expired, member tidak aktif
- [ ] **Activity Log** — Audit trail untuk semua perubahan data oleh admin
- [ ] **Import Excel** — Import member dari spreadsheet
- [ ] **Grafik Interaktif** — Dashboard dengan grafik revenue, member growth, class occupancy
