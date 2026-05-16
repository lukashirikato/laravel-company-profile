---
inclusion: always
---

# FTM Society — Brand & Design Guidelines

> **Sumber acuan resmi** desain visual, tutur, dan identitas FTM Society (Fathima Society) 2025.
> Dokumen ini wajib dirujuk setiap kali membuat atau mengubah elemen visual, copy, atau komponen UI dalam project.

---

## 1. About Us

### Our Story

> Mengajak Muslimah untuk mengenal tubuhnya, menghargai prosesnya, dan memaknai niatnya.

Kami percaya bahwa setiap Muslimah berhak memiliki ruang bergerak yang tetap terjaga dan tanpa kompromi terhadap nilai yang diyakini. FTM Society hadir sebagai teman bagi mereka yang ingin berproses.

**Visi:** Membantu Muslimah lebih dekat dengan tubuhnya, mendukung tiap prosesnya, sebagai ikhtiar untuk mensyukuri pemberian-Nya.

### Our Goal

> Karena menjaga raga juga merupakan amanah dari Rabb-nya.

**Misi:** Menyediakan wadah yang aman dan terjaga bagi Muslimah untuk bergerak, menikmati prosesnya, memperkuat raganya, sebagai bentuk syukur atas nikmat sehat.

FTM Society mengupayakan pengalaman olahraga yang sesuai dengan kebutuhan Muslimah:

- Privasi yang dijaga
- Aurat yang terlindungi
- Suasana yang nyaman
- Komunitas yang tidak menghakimi

> Kami hadir tanpa kamera yang menyorot dan tanpa musik yang terpasang.

### Tagline

> **"the space muslimah deserves."**

Setiap Muslimah berhak atas ruang bergerak yang aman, terjaga, dan selaras dengan nilai yang digenggam. Di FTM Society, kami berniat membawa aktivitas berolahraga sebagai bentuk penjagaan amanah.

> Sisters beside sisters — steady movements, clear intentions, and a circle that protects its own.

### Tone of Voice

FTM Society berkomunikasi dengan **sederhana namun terarah**: tutur yang ramah, mengalir seperti percakapan antar sesama teman, namun tetap mengajak kesungguhan. Kami berbicara dengan **menuntun, bukan menekan**; **menguatkan, bukan menghakimi**.

| Karakter        | Deskripsi                                       |
| --------------- | ----------------------------------------------- |
| Modest          | Tutur yang sopan dan terjaga                    |
| Guiding         | Menuntun, bukan menggurui                       |
| Passionate      | Penuh semangat dan keyakinan                    |
| Conversational  | Hangat seperti percakapan antar teman           |

---

## 2. The Logo

### Logogram Construction

Logo FTM Society terdiri dari tiga elemen utama yang masing-masing membawa makna mendalam.

#### 🌸 The Four Petals

Mewakili empat pilar holistic wellness: **body, mind, faith, and community**.

Setiap kelopak bergerak seirama, menunjukkan harmoni antara kekuatan fisik dan kesehatan batin. Ini mengingatkan bahwa kesehatan bukan hanya soal gerakan, tetapi juga niat dan kebersamaan.

#### ⚪ The Center Point

Melambangkan **niat** sebagai pusat dari segala aktivitas.

Semua kelopak bertemu di titik ini, menunjukkan bahwa setiap pertumbuhan bermula dari niat. Sebuah pengingat bahwa tujuan dan aktivitas yang ada di FTM Society berawal dari niat yang baik.

#### 〇 The Rounded Edges

Mewakili **kelembutan, keamanan, dan inklusivitas**.

Tidak ada sudut tajam, hanya aliran yang menyatu — sebagaimana ruang FTM Society yang menyambut setiap Muslimah di setiap tahap perjalanannya.

### Logomark vs Logotype

**Logomark** adalah versi ringkas dari logo FTM Society, mencakup simbolnya saja.

Gunakan logomark saat:

- Dibutuhkan tampilan yang lebih sederhana dan iconic
- Skala kecil: favicon, stempel, merchandise, profil media sosial

**Aturan penggunaan:**

- ✅ Logomark **boleh** berdiri sendiri tanpa logotype
- ❌ Logotype **tidak boleh** digunakan tanpa logomark

**Variasi warna logomark yang tersedia:**

- Power Pink di atas background pink (light on dark)
- Power Pink di atas background cream / Rising
- Patina Green di atas background Soft Petals
- Power Pink di atas background Patina Green

---

## 3. Color Selection

Palet warna FTM Society bukan hanya soal estetika, tetapi tentang pesan yang ingin dijaga: **ketenangan yang tetap berpendirian, semangat dalam bergerak, dan feminitas yang tetap terarah**.

Setiap warna dipilih untuk membantu FTM Society tampil konsisten — baik di studio, digital, maupun dalam percakapan dengan komunitas.

### Primary Colors

| Nama Warna     | Peran                                       | Hex       |
| -------------- | ------------------------------------------- | --------- |
| Power Pink     | Warna utama brand, energi & feminitas       | `#EE4E8B` |
| Burnt Cherry   | Teks utama, kedalaman, keanggunan           | `#7A2B4A` |
| Soft Petals    | Background alternatif, kelembutan           | `#F4C9DF` |

### Secondary Colors

| Nama Warna       | Peran                                          | Hex       |
| ---------------- | ---------------------------------------------- | --------- |
| Patina Green     | Keseimbangan, alam, ketenangan                 | `#1A7A5E` |
| Springs Ivy      | Aksen hijau lebih gelap, ketegasan             | `#1D5A4B` |
| Grounded Green   | Sage muda, natural & segar                     | `#C5D79B` |
| Layl             | Hampir hitam, untuk teks & kontras kuat        | `#1C1C1C` |
| Rising           | Krem hangat, background utama                  | `#FCF9F2` |

### Color Interactions

Warna-warna FTM Society bekerja seperti sebuah kesatuan yang saling memantulkan karakter satu sama lain. Ketika digabungkan, mereka menghasilkan kesan yang **rapi, vibrant, namun nyaman di mata**. Interaksinya membantu setiap desain tetap terjaga, modern, dan clean.

**Kombinasi yang direkomendasikan:**

- Power Pink + Power Pink (light element on dark bg) → energetik
- Soft Petals + Power Pink → feminin & lembut
- Burnt Cherry + Rising → elegan & warm
- Patina Green + Grounded Green → natural & seimbang
- Patina Green + Grounded Green (elemen) → fresh contrast
- Grounded Green + Power Pink → vibrant, muda & berani

### Tailwind Token Mapping (untuk acuan di kode)

Token Tailwind config yang ada di project (`welcome.blade.php`, `member.css`):

```js
colors: {
  primary:        "#EE4E8B",   // Power Pink
  secondary:      "#7A2B4A",   // Burnt Cherry
  accent:         "#1A7A5E",   // Patina Green
  "light-pink":   "#F4C9DF",   // Soft Petals
  cream:          "#FCF9F2",   // Rising
  dark:           "#1C1C1C",   // Layl
  "springs-ivy":  "#1D5A4B",
  "grounded-green":"#C5D79B",

  // Aliases bermakna brand
  "power-pink":   "#EE4E8B",
  "burnt-cherry": "#7A2B4A",
  "soft-petals":  "#F4C9DF",
  "patina-green": "#1A7A5E",
  "layl":         "#1C1C1C",
  "rising":       "#FCF9F2"
}
```

> ⚠️ **Catatan migrasi nilai warna:** versi awal project sempat memakai approximation seperti `#E8618C`, `#6B2D4E`, `#1A7A6E`, dll. Untuk konsistensi brand 2025, gunakan **nilai resmi di tabel di atas** (`#EE4E8B`, `#7A2B4A`, `#1A7A5E`, dst.). Saat memperbarui komponen lama, ganti ke nilai resmi.

---

## 4. Supergraphic

FTM Society menggunakan elemen grafis yang dirancang khusus, dengan akar pada nilai **modesty** dan **kekuatan feminin**. Elemen-elemen ini menjaga konsistensi visual dan menciptakan estetika yang mudah dikenali di semua medium.

### Graphic Elements

| Nama Elemen        | Bentuk                                | Makna                       |
| ------------------ | ------------------------------------- | --------------------------- |
| Two Half-Moons     | Dua bulan setengah berhadapan         | **BALANCE** — Keseimbangan  |
| Circles Cluster    | Tiga lingkaran saling bertumpuk       | **SISTERHOOD** — Persaudaraan |
| Four-Part Fold     | Empat bagian geometris                | **INTENTION** — Niat & Tujuan |
| Five Petal Bloom   | Bunga dengan lima kelopak bulat       | **GROWTH** — Pertumbuhan    |
| Radiant Asterisk   | Bintang/asterisk bersudut rounded     | **STRENGTH** — Kekuatan     |
| Four Leaf Form     | Empat daun/kelopak simetris           | **SAFETY** — Keamanan       |

**Supergraphic dapat diaplikasikan sebagai:**

- Pola latar belakang pada materi promosi
- Elemen dekoratif di merchandise
- Icon atau stamp pada konten digital
- Overlay pada foto dan visual media sosial

> Di kode, elemen ini diimplementasikan sebagai inline SVG / `data:image/svg+xml` dengan helper class seperti `.brand-flower`, `.brand-asterisk`, `.brand-cmark`, `.brand-trio` (lihat `welcome.blade.php`).

---

## 5. Typography

### Font Utama — NORD

```
Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Pp Qq Rr Ss Tt Uu Vv Ww Xx Yy Zz
```

**Gaya tersedia:** Book · Italic · Bold

NORD membawa karakter tegas yang tetap clean dan stabil. Digunakan sebagai **headline** — cocok untuk judul besar, tagline, dan heading utama.

**Karakter:** Modern, tegas, bersih, architectural

### Font Pendukung — Instrument Serif

```
Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Pp Qq Rr Ss Tt Uu Vv Ww Xx Yy Zz
```

**Gaya tersedia:** Regular · Italic

Instrument Serif hadir sebagai pendamping yang lebih **feminim dan airy**. Ia memberi ruang bagi kepribadian komunitas FTM: ramah dan approachable. Dipakai untuk **body text** — membuat keseluruhan identitas terasa lebih hangat dan manusiawi.

**Karakter:** Feminim, editorial, elegan, approachable

### Hierarki Tipografi

| Level                | Font              | Gaya     | Penggunaan                     |
| -------------------- | ----------------- | -------- | ------------------------------ |
| Display / Hero       | NORD              | Bold     | Headline utama, tagline besar  |
| Heading              | NORD              | Book     | Judul section, sub-headline    |
| Subheading           | Instrument Serif  | Italic   | Pull quote, caption judul      |
| Body                 | Instrument Serif  | Regular  | Paragraf, deskripsi panjang    |
| Caption              | NORD              | Book     | Label, keterangan gambar       |

### Implementasi di Project

Font di-host secara lokal (bukan Google Fonts CDN) di:

```
public/fonts/Nord-*.woff2  (Thin, Light, Book, Regular, Medium, Bold, Black + italics)
public/fonts/InstrumentSerif-*.ttf
```

Deklarasi `@font-face` ada di `resources/css/ftm-typography.css` (di-mirror ke `public/css/ftm-typography.css`).

**Cara pakai di Blade:**

```html
<link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
```

**Tailwind utility classes:**

```html
<span class="font-nord font-black">FTM</span>            <!-- Wordmark -->
<span class="font-instrument italic">Society</span>       <!-- Aksen feminin -->
<p class="font-poppins">Body fallback</p>                 <!-- Untuk web body  -->
```

> **Aturan penting:** JANGAN inline `@font-face` di Blade — itu menyebabkan PHP timeout di Blade compiler. Selalu pakai link reference ke `ftm-typography.css`.

---

## 6. Brand in Action

Brand FTM Society diaplikasikan secara konsisten di berbagai medium.

### Digital

- **Media Sosial** — Konten Instagram dengan visual yang bersih menggunakan kombinasi warna primary dan supergraphic sebagai elemen dekoratif
- **Profil Picture** — Menggunakan logomark tanpa logotype
- **Story / Reels** — Background Rising atau Power Pink, dengan tipografi NORD Bold

### Physical / Studio

- **Signage** — NORD Bold pada Power Pink atau Rising background
- **Merchandise** — Supergraphic diterapkan pada kaos, tote bag, dan peralatan gym
- **Stempel** — Logomark hitam pada permukaan netral

### Komunitas

- **Caption tone:** Hangat, mengajak, tidak menghakimi — sesuai tone of voice **conversational & guiding**
- **Hashtag:** Konsisten menggunakan identitas brand

---

## Panduan Ringkas

| Elemen           | Ketentuan                                                             |
| ---------------- | --------------------------------------------------------------------- |
| Logo             | Logomark bisa standalone; logotype wajib didampingi logomark          |
| Warna primer     | Power Pink, Burnt Cherry, Soft Petals                                 |
| Warna sekunder   | Patina Green, Springs Ivy, Grounded Green, Layl, Rising               |
| Font headline    | NORD (Bold/Book)                                                      |
| Font body        | Instrument Serif (Regular/Italic)                                     |
| Tone             | Modest · Guiding · Passionate · Conversational                        |
| Tagline          | "the space muslimah deserves."                                        |

---

## Checklist Sebelum Menambah / Mengubah Komponen

Setiap kali menambah halaman, section, atau komponen baru:

1. **Warna** — pakai token Tailwind brand (`primary`, `secondary`, `accent`, `cream`, `dark`, `light-pink`, dll.) atau hex resmi di tabel di atas. Hindari `#FFF` polos kalau bisa pakai `Rising` / `cream`.
2. **Tipografi** — headline pakai `font-nord`, aksen feminin pakai `font-instrument italic`, body pakai `font-poppins` (fallback).
3. **Supergraphic** — pertimbangkan menambahkan ornamen brand (`brand-flower`, `brand-asterisk`, dll.) sebagai elemen dekoratif kecil dengan opacity rendah, bukan elemen utama.
4. **Tone copy** — tulis dalam bahasa Indonesia hangat, tidak menggurui, tidak menghakimi. Hindari bahasa yang terlalu formal/korporat.
5. **Konsistensi navbar** — navbar pakai grid 3 kolom: logo (kiri) · menu (tengah) · CTA (kanan). Logo wordmark: `FTM` (Nord Black, Power Pink) + `Society` (Instrument Serif italic, Burnt Cherry).
6. **Aksesibilitas** — kontras teks vs background harus memenuhi WCAG AA. Jangan letakkan teks Power Pink di atas Soft Petals tanpa kontras tambahan.

---

## Referensi File di Project

| Aspek               | Lokasi                                                              |
| ------------------- | ------------------------------------------------------------------- |
| Tailwind config     | inline di `resources/views/welcome.blade.php` (head)                |
| Brand CSS variables | `resources/css/member.css`                                          |
| Typography          | `resources/css/ftm-typography.css`, `public/css/ftm-typography.css` |
| Font files          | `public/fonts/Nord-*.woff2`, `public/fonts/InstrumentSerif-*.ttf`   |
| Logo image          | `public/icons/logo-ftm.jpg`, `public/images/logo ftm (1).jpg`       |
| Supergraphic helper | inline SVG di `welcome.blade.php` (`.brand-flower`, dll.)           |

---

> Brand Guidelines ini disusun agar setiap elemen visual, tutur, dan karakter FTM Society tetap terjaga, **tanpa membatasi ruang eksplorasi**.
>
> © FTM Society — Fathima Society 2025
