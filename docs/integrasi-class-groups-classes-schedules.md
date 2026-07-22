# Integrasi Class Groups — Classes — Schedules

Dokumen ini menjelaskan arsitektur integrasi antara **Class Groups**, **Classes**, dan **Schedules** di FTM Society Admin Panel, termasuk panduan penggunaan, alur data, serta command yang tersedia.

---

## Daftar Isi

1. [Overview Arsitektur](#1-overview-arsitektur)
2. [Entity Relationship](#2-entity-relationship)
3. [Fitur yang Tersedia](#3-fitur-yang-tersedia)
    - [3.1 Class Groups Resource](#31-class-groups-resource)
    - [3.2 Classes Resource](#32-classes-resource)
    - [3.3 Schedules Resource](#33-schedules-resource)
    - [3.4 Schedule Labels Resource (BARU)](#34-schedule-labels-resource-baru)
4. [Alur Data Lengkap](#4-alur-data-lengkap)
5. [Command Backfill Data](#5-command-backfill-data)
6. [Panduan Penggunaan Admin](#6-panduan-penggunaan-admin)
7. [Troubleshooting](#7-troubleshooting)
8. [Struktur File](#8-struktur-file)

---

## 1. Overview Arsitektur

Integrasi ini menyatukan tiga entitas utama dalam sistem manajemen jadwal FTM Society:

```
Class Group (Mix Class 2)
       │
       ├── hasMany ──► Class (Muaythai Intermediate - MC2)
       │                     │
       │                     └── hasMany ──► Schedule (Senin 08:00)
       │
       └── hasMany ──► Class (Mat Pilates - MC2)
                             │
                             └── hasMany ──► Schedule (Rabu 10:00)
```

**Tujuan integrasi:**

- Admin cukup memilih **Class** saat membuat Schedule → **Class Group** terisi otomatis
- Admin bisa **memfilter Schedule berdasarkan Group** untuk melihat semua jadwal dalam satu group
- Admin bisa melihat **total Class** dan **total Schedule** per Group secara real-time
- Konsistensi data terjamin — tidak ada lagi Schedule yang salah group

---

## 2. Entity Relationship

### Class Groups (`class_groups`)

| Kolom        | Tipe        | Keterangan                       |
| ------------ | ----------- | -------------------------------- |
| `id`         | bigint (PK) | Primary key                      |
| `name`       | string      | Nama group (contoh: Mix Class 2) |
| `level`      | string?     | Level (opsional)                 |
| `created_at` | timestamp   | Otomatis                         |
| `updated_at` | timestamp   | Otomatis                         |

**Relationships:**

- `classes()` — `hasMany(ClassModel::class, 'class_group_id')`
- `schedules()` — `hasMany(Schedule::class, 'class_group_id')`

### Classes (`class_models`)

| Kolom            | Tipe        | Keterangan                                      |
| ---------------- | ----------- | ----------------------------------------------- |
| `id`             | bigint (PK) | Primary key                                     |
| `class_name`     | string      | Nama kelas                                      |
| `level`          | string?     | Beginner / Intermediate / Advanced / All Levels |
| `class_group_id` | bigint (FK) | Foreign key ke `class_groups.id`                |
| `created_at`     | timestamp   | Otomatis                                        |
| `updated_at`     | timestamp   | Otomatis                                        |

**Relationships:**

- `group()` — `belongsTo(ClassGroup::class, 'class_group_id')`
- `schedules()` — `hasMany(Schedule::class, 'class_id')`

### Schedules (`schedules`)

| Kolom             | Tipe        | Keterangan                       |
| ----------------- | ----------- | -------------------------------- |
| `id`              | bigint (PK) | Primary key                      |
| `class_group_id`  | bigint (FK) | Foreign key ke `class_groups.id` |
| `class_id`        | bigint (FK) | Foreign key ke `class_models.id` |
| `schedule_label`  | string      | Label jadwal (untuk display)     |
| `day`             | string      | Hari (Monday, Tuesday, dll)      |
| `schedule_date`   | date?       | Tanggal spesifik                 |
| `class_time`      | time?       | Waktu kelas                      |
| `instructor`      | string?     | Nama instruktur                  |
| `show_on_landing` | boolean     | Tampil di halaman publik         |
| `created_at`      | timestamp   | Otomatis                         |
| `updated_at`      | timestamp   | Otomatis                         |

**Relationships:**

- `classGroup()` — `belongsTo(ClassGroup::class, 'class_group_id')`
- `classModel()` — `belongsTo(ClassModel::class, 'class_id')`
- `packages()` — `belongsToMany(Package::class, 'package_schedules')`

### Diagram Relasi

```
┌──────────────────┐
│   ClassGroup     │
│──────────────────│
│ id               │
│ name             │
│ level            │
└────────┬─────────┘
         │
         │ 1
         │
         ├──────────────────────────────┐
         │ hasMany('class_group_id')    │ hasMany('class_group_id')
         ▼                              ▼
┌──────────────────┐         ┌──────────────────┐
│    ClassModel     │         │    Schedule      │
│──────────────────│         │──────────────────│
│ id               │         │ id               │
│ class_name       │         │ class_group_id───┼──FK
│ class_group_id───┼──FK     │ class_id─────────┼──FK
│ level            │         │ schedule_label   │
└────────┬─────────┘         │ day              │
         │                   │ schedule_date    │
         │ 1                 │ class_time       │
         │                   │ instructor       │
         │                   │ show_on_landing  │
         │                   └──────────────────┘
         │
         └──────────────────────────────┐
         hasMany('class_id')            │
                                        │
                              ┌─────────┴──────────┐
                              │    Package         │
                              │   (via pivot)      │
                              └────────────────────┘
```

---

## 3. Fitur yang Tersedia

### 3.1 Class Groups Resource

**Navigasi:** Management → Class Groups

**Form:**

- `name` — TextInput (required) — Nama group, contoh: Mix Class 1, Mix Class 2
- `level` — TextInput (nullable) — Level group

**Tabel:**
| Kolom | Sumber | Keterangan |
|-------|--------|------------|
| Nama Group | `name` | Searchable, sortable |
| Level | `level` | Searchable |
| Jumlah Class | `classes_count` | Otomatis dari `->withCount('classes')` |
| Jumlah Schedule | `schedules_count` | Otomatis dari `->withCount('schedules')` |
| Dibuat | `created_at` | Toggleable |

**Fitur:**

- ✅ Create / Edit / Delete group
- ✅ Melihat total Class dan Schedule per group
- ✅ Search & sort

### 3.2 Classes Resource

**Navigasi:** Management → Classes

**Form:**
| Field | Tipe | Keterangan |
|-------|------|------------|
| Nama Kelas | TextInput (required) | Nama class |
| Level | Select (required) | Beginner / Intermediate / Advanced / All Levels |
| Group | Select (searchable, nullable) | Pilih Class Group — relasi `group()` |

**Tabel:**
| Kolom | Sumber | Keterangan |
|-------|--------|------------|
| Nama Kelas | `class_name` | Searchable, sortable, bold |
| Level | `level` | Badge dengan warna (success/warning/danger/primary) |
| Group | `group.name` | Nama Class Group |
| Jadwal | `schedules_count` | Jumlah schedule terkait |

**Filter:**

- Level (multiple)
- Group (multiple)

### 3.3 Schedules Resource

**Navigasi:** Management → Schedules

**Form:**

| Field          | Tipe                                    | Keterangan                                              |
| -------------- | --------------------------------------- | ------------------------------------------------------- |
| Packages       | CheckboxList (required)                 | Package yang terhubung                                  |
| Class          | Select (searchable, nullable, reactive) | Pilih class → Group otomatis terisi (fallback)          |
| Schedule Label | Select (required, reactive)             | **Primary** — pilih label → Class Group otomatis terisi |
| Day            | TextInput                               | Hari jadwal                                             |
| Tanggal Jadwal | DatePicker                              | Otomatis dari day                                       |
| Class Time     | Time                                    | Waktu kelas                                             |
| Instructor     | TextInput                               | Nama instruktur                                         |

> **Class Group tidak tampil di form.** Admin cukup pilih **Schedule Label**, dan system otomatis menentukan Class Group berdasarkan mapping internal. Tidak perlu bingung memilih Class Group manual.

**Auto-fill Logic (prioritas):**

```
User pilih Schedule Label "Mix Class (2)"
    ↓
Cek mapping LABEL_TO_CLASS_GROUP['Mix Class (2)'] = 'Mix Class 2'
    ↓
Cari ClassGroup dengan nama "Mix Class 2"
    ↓
Ketemu → set class_group_id = ID Mix Class 2
    ↓
Tersimpan di database

===== FALLBACK =====

User pilih Class "Muaythai Intermediate - MC2"
    ↓
Cari ClassModel → punya class_group_id = 1
    ↓
Set class_group_id = 1
```

**Tabel (新增 kolom):**

| Kolom      | Sumber                  | Keterangan                  |
| ---------- | ----------------------- | --------------------------- |
| ID         | `id`                    | Searchable, sortable        |
| Packages   | `packageSummary`        | Representative label        |
| Label      | `schedule_label`        | Badge hijau/merah           |
| Series     | `series_status`         | Badge series                |
| Class      | `classModel.class_name` | Nama class                  |
| **Group**  | **`classGroup.name`**   | **Nama Class Group — BARU** |
| Day        | `day`                   | Hari                        |
| Date       | `schedule_date`         | Format d/M/Y                |
| Time       | `class_time`            | Format H:i                  |
| Instructor | `instructor`            | Nama instruktur             |
| Visibility | `show_on_landing`       | Badge tampil/tersembunyi    |
| Created    | `created_at`            | Tanggal dibuat              |

**Filter (新增):**

| Filter            | Tipe                  | Keterangan                       |
| ----------------- | --------------------- | -------------------------------- |
| Series            | Select                | Single / Parent / Child          |
| Package           | Select (searchable)   | Filter by package                |
| Class             | Select                | Filter by class                  |
| **Group**         | **Select (multiple)** | **Filter by Class Group — BARU** |
| Instructor        | TextInput             | Partial match                    |
| Day               | Select                | Monday - Sunday                  |
| Tampil di Landing | Ternary               | Ya / Tidak / Semua               |

### 3.4 Schedule Labels Resource (BARU)

**Navigasi:** Management → Schedule Labels

**Deskripsi:**
Resource ini menggantikan array hardcoded `POSTER_EXCLUSIVE_LABELS` yang sebelumnya ada di `ScheduleResource.php`. Admin sekarang bisa **menambah, mengedit, dan menghapus** label langsung dari panel admin tanpa perlu mengubah kode.

**Form:**

| Field       | Tipe                          | Keterangan                                                                       |
| ----------- | ----------------------------- | -------------------------------------------------------------------------------- |
| Label       | TextInput (required, unique)  | Nama label yang tampil di dropdown Schedule dan checkout member                  |
| Class Group | Select (searchable, nullable) | Pilih Class Group yang terkait — otomatis dipakai saat label dipilih di Schedule |

**Tabel:**

| Kolom       | Sumber            | Keterangan                 |
| ----------- | ----------------- | -------------------------- |
| Label       | `label`           | Searchable, sortable, bold |
| Class Group | `classGroup.name` | Nama group terkait         |
| Dibuat      | `created_at`      | Tanggal dibuat             |

**Cara kerja:**

1. Admin buka **Management → Schedule Labels**
2. Klik **Tambah Label** → isi label + pilih Class Group
3. Label langsung muncul di dropdown **Schedule Label** pada form Schedule
4. Saat admin pilih label tersebut → Class Group terisi otomatis

> Data label disimpan di tabel `schedule_label_mappings` dan di-cache 1 jam. Jika ada perubahan, cache akan otomatis ter-refresh setelah 1 jam atau bisa di-clear manual dengan `php artisan cache:forget schedule_label_options`.

---

## 4. Alur Data Lengkap

### Skenario: Admin Menambahkan Jadwal Baru

```
1. Admin buka Management → Schedules → Create
2. Isi Packages (CheckboxList) — pilih package terkait
3. Pilih Class "Muaythai Intermediate - MC2"
4. ⚡ System otomatis mengisi Class Group "Mix Class 2"
5. Isi Schedule Label, Day, Time, Instructor
6. Submit → Data tersimpan dengan:
   - class_id = ID Muaythai Intermediate - MC2
   - class_group_id = ID Mix Class 2
   - packages tersimpan di pivot package_schedules
```

### Skenario: Admin Melihat Semua Jadwal per Group

    ```
    1. Buka Management → Schedules

2. Klik filter "Group" → pilih "Mix Class 2"
3. Tabel menampilkan SEMUA jadwal dari:
    - Muaythai Intermediate - MC2
    - Mat Pilates - MC2
      (asalkan keduanya punya class_group_id = Mix Class 2)

```

### Skenario: Admin Melihat Statistik Group

```

1. Buka Management → Class Groups
2. Lihat kolom "Jumlah Class" = 2 (Muaythai Int & Mat Pilates)
3. Lihat kolom "Jumlah Schedule" = total schedule dari kedua class

```

### Skenario: Data Schedule Lama

```

1. Jalankan command: php artisan schedules:sync-class-group
2. System mengisi class_group_id semua schedule yang masih NULL
   berdasarkan class_id masing-masing
3. Jika ClassModel terhapus atau tidak punya group → class_group_id tetap NULL

````

---

## 5. Command Backfill Data

### Sinkronisasi Class Group untuk Schedule Lama

```bash
php artisan schedules:sync-class-group
````

**Fungsi:**
Mengisi kolom `class_group_id` di tabel `schedules` yang masih `NULL`, berdasarkan nilai `class_group_id` dari `class_models` yang terhubung melalui `class_id`.

**Output contoh:**

```
Mulai sinkronisasi class_group_id ke schedules...
Berhasil memperbarui 15 jadwal.

┌──────────────────────────┬───────┐
│ Metric                   │ Value │
├──────────────────────────┼───────┤
│ Total schedules          │ 42    │
│ Sudah punya class_group_id│ 38   │
│ Belum punya class_group_id│ 4    │
│ Diperbarui kali ini      │ 15    │
└──────────────────────────┴───────┘
```

**Catatan:**

- Schedule yang `class_id`-nya `NULL` tidak akan terisi
- Schedule yang `class_id`-nya merujuk ke ClassModel tanpa `class_group_id` tidak akan terisi
- Command aman dijalankan berulang kali (idempotent)

---

## 6. Panduan Penggunaan Admin

### Langkah 0 (Sekali Saja) — Seed Data Awal

```bash
php artisan db:seed --class=ScheduleLabelMappingSeeder
```

Atau buka **Management → Schedule Labels** → buat satu per satu secara manual.

### Langkah 1 — Buat Class Group

```
Management → Class Groups → Create
├── Nama Group: Mix Class 2
└── Level: Intermediate
```

### Langkah 2 — Buat Classes dalam Group

```
Management → Classes → Create (untuk setiap class)
├── Nama Kelas: Muaythai Intermediate - MC2
├── Level: Intermediate
└── Group: Mix Class 2

Management → Classes → Create
├── Nama Kelas: Mat Pilates - MC2
├── Level: Intermediate
└── Group: Mix Class 2
```

### Langkah 3 — Buat Schedule untuk Masing-masing Class

```
Management → Schedules → Create
├── Packages: [pilih package yang sesuai]
├── Class: Muaythai Intermediate - MC2
├── ⚡ Class Group: Mix Class 2 (otomatis)
├── Schedule Label: Mix Class (2)
├── Day: Monday & Wednesday
├── Class Time: 08:00
└── Instructor: Coach Alex

Management → Schedules → Create
├── Packages: [pilih package yang sesuai]
├── Class: Mat Pilates - MC2
├── ⚡ Class Group: Mix Class 2 (otomatis)
├── Schedule Label: Mix Class (2)
├── Day: Tuesday & Thursday
├── Class Time: 10:00
└── Instructor: Coach Sarah
```

### Langkah 4 — Verifikasi

1. **Buka Class Groups** → klik "Mix Class 2" → lihat **Jumlah Class = 2**, **Jumlah Schedule** sesuai total
2. **Buka Schedules** → filter **Group = Mix Class 2** → lihat semua jadwal terkumpul
3. **Buka Classes** → klik "Muaythai Intermediate - MC2" → lihat **Group = Mix Class 2**

---

## 7. Troubleshooting

### Problem: Class Group tidak terisi otomatis saat pilih Class di Schedule

**Penyebab:** Field `class_id` tidak `reactive()` atau `afterStateUpdated` tidak terpanggil.

**Solusi:**

1. Pastikan `->reactive()` ada di field `class_id`
2. Pastikan `->afterStateUpdated(...)` berisi logic untuk set `class_group_id`
3. Refresh halaman dan coba lagi

### Problem: Kolom "Group" di tabel Schedule kosong

**Penyebab:** Data schedule lama belum di-backfill.

**Solusi:**

```bash
php artisan schedules:sync-class-group
```

### Problem: "Jumlah Class" atau "Jumlah Schedule" di Class Groups tidak akurat

**Penyebab:** Query `withCount` tidak refresh atau ada data orphan.

**Solusi:**

1. Pastikan semua `class_models` punya `class_group_id` yang valid
2. Pastikan semua `schedules` punya `class_group_id` yang valid
3. Jalankan command backfill jika perlu

### Problem: Filter Group di Schedule tidak muncul

**Penyebab:** Import `ClassGroup` model tidak ada di ScheduleResource.

**Solusi:**
Pastikan ada `use App\Models\ClassGroup;` di bagian atas `ScheduleResource.php`.

---

## 8. Struktur File

```
app/
├── Console/
│   └── Commands/
│       └── SyncScheduleClassGroup.php            ← Command backfill data
│
├── Filament/
│   └── Resources/
│       ├── ClassGroupResource.php                ← CRUD + withCount classes & schedules
│       ├── ClassModelResource.php                ← CRUD + select class_group_id
│       ├── ScheduleResource.php                  ← CRUD + auto-fill class_group_id via label/class
│       └── ScheduleLabelMappingResource/         ← CRUD Schedule Labels (BARU)
│           ├── ScheduleLabelMappingResource.php
│           └── Pages/
│               ├── ListScheduleLabelMappings.php
│               ├── CreateScheduleLabelMapping.php
│               └── EditScheduleLabelMapping.php
│
├── Models/
│   ├── ClassGroup.php                            ← Model + relationships
│   ├── ClassModel.php                            ← Model + relationships
│   ├── Schedule.php                              ← Model + classGroup() relationship
│   └── ScheduleLabelMapping.php                  ← Model + belongsTo classGroup (BARU)

database/
├── migrations/
│   ├── 2025_12_08_005557_create_class_groups_table.php
│   ├── 2025_12_08_005655_add_class_group_id_to_classes_table.php
│   ├── 2026_07_08_add_class_group_id_to_schedules.php
│   └── 2026_07_09_000001_create_schedule_label_mappings_table.php  ← (BARU)
│
└── seeders/
    └── ScheduleLabelMappingSeeder.php            ← Seeder data awal (BARU)

docs/
└── integrasi-class-groups-classes-schedules.md   ← Dokumentasi ini
```

---

## 9. Ringkasan Perubahan Kode

### Model Schedule.php

| Item         | Sebelum                    | Sesudah                                            |
| ------------ | -------------------------- | -------------------------------------------------- |
| `$fillable`  | Tidak ada `class_group_id` | ✅ Ditambahkan `'class_group_id'`                  |
| Relationship | Tidak ada                  | ✅ `classGroup()` — `belongsTo(ClassGroup::class)` |

### ScheduleResource.php

| Item                                      | Sebelum                                                  | Sesudah                                                         |
| ----------------------------------------- | -------------------------------------------------------- | --------------------------------------------------------------- |
| Form `class_id`                           | `searchable(), nullable()`                               | ✅ `reactive()` + `afterStateUpdated()` auto-fill (fallback)    |
| Form `schedule_label`                     | `options` dari hardcoded array `POSTER_EXCLUSIVE_LABELS` | ✅ `options` dari DB tabel `schedule_label_mappings` (di-cache) |
| Form `schedule_label` `afterStateUpdated` | Mapping dari hardcoded array `LABEL_TO_CLASS_GROUP`      | ✅ Mapping dari DB `schedule_label_mappings` via relationship   |
| Form `class_group_id`                     | ❌ Tidak ada                                             | ❌ **Tidak ditampilkan** — otomatis terisi dari label/class     |
| Table column                              | ❌ Tidak ada                                             | ✅ `classGroup.name` — sortable                                 |
| Filter                                    | ❌ Tidak ada                                             | ✅ SelectFilter — multiple                                      |
| Eloquent Query                            | Select tanpa `class_group_id`                            | ✅ Select + eager load `classGroup`                             |

### ScheduleLabelMappingResource.php (BARU)

| Item     | Keterangan                                                                    |
| -------- | ----------------------------------------------------------------------------- |
| Model    | `ScheduleLabelMapping` — tabel `schedule_label_mappings`                      |
| Form     | `label` (TextInput, unique) + `class_group_id` (Select, nullable)             |
| Tabel    | `label`, `classGroup.name`, `created_at`                                      |
| Navigasi | Management → Schedule Labels (sort: 8)                                        |
| Fungsi   | Admin bisa nambah/edit/hapus label + mapping ke Class Group tanpa sentuh kode |

### ClassGroupResource.php

| Item            | Sebelum                                    | Sesudah                                            |
| --------------- | ------------------------------------------ | -------------------------------------------------- |
| Total Kelas     | Raw subquery `DISTINCT schedules.class_id` | ✅ `->withCount('classes')` — lebih akurat & cepat |
| Jumlah Schedule | ❌ Tidak ada                               | ✅ `->withCount('schedules')` — baru               |

---

_Dokumen ini diperbarui pada: Juli 2026_
