# 📋 Dokumentasi CRUD Event Admin - AmikomEventHub

## ✅ Setup Selesai!

Database telah dikonfigurasi dengan:
- ✓ Migrations (Users, Categories, Events, Transactions)
- ✓ Seeding (Admin User + 3 Sample Events)
- ✓ Storage Link untuk file upload

---

## 🚀 Cara Mengakses Admin Dashboard

### Login Admin
- **URL:** http://localhost/admin
- **Email:** `admin@amikom.ac.id`
- **Password:** `password`

### Struktur Routes
```
GET    /admin                          # Dashboard
GET    /admin/events                   # Index (Read)
GET    /admin/events/create            # Create Form
POST   /admin/events                   # Store (Create)
GET    /admin/events/{id}/edit         # Edit Form
PUT    /admin/events/{id}              # Update
DELETE /admin/events/{id}              # Delete
GET    /admin/transactions             # Transactions
```

---

## 📝 Skenario Testing CRUD Lengkap

### 1️⃣ **READ - Akses Halaman Index Events**

**Langkah:**
1. Login ke admin: `http://localhost/admin`
2. Klik menu "Kelola Event" di sidebar
3. Lihat tabel dengan data yang sudah ada

**Verifikasi:**
- ✓ Tabel menampilkan 3 event sample
- ✓ Kolom: No, Poster, Event, Harga/Stok, Aksi
- ✓ Pagination aktif (jika > 10 data)
- ✓ Tombol "Tambah Event Baru" tersedia

**Data Sample:**
```
1. Jazz Night 2025 (Entertaiment) - Rp 50.000 - Stok: 100
2. Hackaton - Unleash Your Inner Developer (Seminar IT) - Rp 50.000 - Stok: 100
3. AI & FUTURE TECH SUMMIT 2026 (Seminar IT) - Rp 50.000 - Stok: 100
```

---

### 2️⃣ **CREATE - Tambah Event Baru**

**Langkah:**
1. Klik tombol "+ Tambah Event Baru"
2. Isi form dengan data berikut:

```
Judul Event:           "Workshop Web Development 2026"
Kategori:              "Seminar IT"
Deskripsi:            "Belajar membuat website modern dengan Laravel dan React"
Tanggal & Waktu:      2026-05-15 14:00
Lokasi:               "Lab Komputer Gedung A"
Harga (Rp):          75000
Kapasitas (Stok):     50
Poster Event:         [Upload gambar JPG/PNG, max 2MB] (Opsional)
```

3. Klik tombol "Simpan Event"

**Verifikasi:**
- ✓ Muncul notifikasi hijau "Data Event berhasil ditambahkan."
- ✓ Redirect ke halaman index
- ✓ Event baru tampil di tabel (paling atas karena latest())
- ✓ Validation error jika ada field yang kosong/tidak valid

**Test Validasi:**
- Coba submit tanpa judul → Error "Judul Event tidak boleh kosong"
- Coba submit dengan harga negatif → Error "Harga harus >= 0"
- Coba submit stok dengan nilai 0 → Error "Stok minimal 1"
- Coba upload file >2MB → Error "File terlalu besar"
- Coba upload file non-image → Error "File harus berupa gambar"

---

### 3️⃣ **UPDATE - Edit Event Existing**

**Langkah:**
1. Di halaman index, klik tombol edit (ikon pensil) pada event manapun
2. Edit field berikut:
```
Harga (Rp):           150000  (dari 50000)
Kapasitas (Stok):     75      (dari 100)
Deskripsi:            Tambahkan teks penjelasan lebih detail
```
3. Klik "Simpan Perubahan"

**Verifikasi:**
- ✓ Muncul notifikasi "Data event berhasil diperbarui."
- ✓ Data di tabel langsung terupdate
- ✓ Harga baru terlihat dengan format Rupiah (Rp 150.000)
- ✓ Stok terupdate menjadi 75
- ✓ Validasi sama seperti Create (required fields, type check, dll)

**Test Validasi Update:**
- Coba ubah judul dengan karakter spesial → Harus valid
- Coba hapus deskripsi → Error (required)
- Coba upload poster baru → File lama terhapus, file baru tersimpan
- Edit tanpa perubahan → Tetap bisa simpan (no error)

---

### 4️⃣ **DELETE - Hapus Event**

**Langkah:**
1. Di halaman index, klik tombol hapus (ikon trash) pada event
2. Muncul confirm dialog: "Apakah Anda yakin ingin menghapus acara ini?"
3. Klik "OK" untuk confirm

**Verifikasi:**
- ✓ Event hilang dari tabel
- ✓ Muncul notifikasi "Data event berhasil dihapus secara permanen."
- ✓ File poster (jika ada) juga terhapus dari storage
- ✓ Data di database benar-benar dihapus

**Test Delete Edge Cases:**
- Hapus event dengan poster → File di storage/events/ terhapus
- Hapus event tanpa poster → Tetap bisa dihapus tanpa error
- Refresh halaman setelah delete → Event tidak muncul lagi

---

## 🛡️ Validasi yang Diimplementasikan

### Server-Side Validation (EventController)
```php
[
    'category_id'   => 'required|exists:categories,id',
    'title'         => 'required|string|max:255|unique:events,title',
    'description'   => 'required|string|min:10',
    'date'          => 'required|date|after:today',
    'location'      => 'required|string|max:255',
    'price'         => 'required|numeric|min:0',
    'stock'         => 'required|integer|min:1',
    'poster'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
]
```

### Penjelasan Rule:
- `category_id`: Harus dipilih & ada di tabel categories
- `title`: Tidak boleh kosong, max 255 char, unik di database
- `description`: Min 10 karakter
- `date`: Format date, tidak boleh tanggal lampau
- `price`: Angka, minimal 0
- `stock`: Integer, minimal 1
- `poster`: Opsional, harus image, max 2MB

---

## 📁 File Structure

```
app/Http/Controllers/Admin/EventController.php    # Controller dengan CRUD lengkap
app/Models/Event.php                              # Model dengan relasi
app/Models/Category.php                           # Model kategori
resources/views/admin/events/index.blade.php      # Tabel data
resources/views/admin/events/create.blade.php     # Form tambah
resources/views/admin/events/edit.blade.php       # Form edit
database/migrations/2026_04_22_064220_*           # Table events
database/migrations/2026_04_22_064211_*           # Table categories
database/seeders/DatabaseSeeder.php               # Seed data
```

---

## 🔄 Flow Diagram CRUD

```
┌─────────────────────────────────────┐
│    Admin Event Management Flow       │
└─────────────────────────────────────┘
                    │
        ┌───────────┼───────────┐
        ▼           ▼           ▼
    [READ]      [CREATE]     [DELETE]
    Index    Form + Store    Confirm
      │          │              │
      │          │ Validate     │
      │          │              │
      │          ▼              │
      └─────► [UPDATE] ◄────────┘
             Form + Update
                │
                └──► Success Message
                     Redirect to Index
```

---

## ✨ Fitur Tambahan yang Diimplementasikan

### 1. File Upload Management
- Validasi ukuran file (max 2MB)
- Validasi tipe file (JPEG, PNG, JPG, GIF)
- Otomatis hapus file lama saat update
- Storage link untuk akses public

### 2. Error Handling
- Validasi form dengan pesan error yang jelas
- Success messages setelah operasi CRUD
- Graceful delete dengan confirm dialog

### 3. Database Relasi
- Event belongs to Category (one-to-many)
- Cascade delete (hapus event = hapus relasi)
- Load data dengan eager loading

### 4. UI/UX
- Responsive design dengan Tailwind CSS
- Icon buttons untuk edit/delete
- Pagination untuk data besar
- Empty state message

---

## 🧪 Testing Checklist

- [ ] **READ**: Akses index, lihat semua data tertampil
- [ ] **CREATE**: Tambah event baru berhasil
- [ ] **Validasi CREATE**: Test error handling
- [ ] **UPDATE**: Edit event dan verifikasi perubahan
- [ ] **Validasi UPDATE**: Test error pada edit form
- [ ] **DELETE**: Hapus event dengan confirm
- [ ] **File Upload**: Upload poster dan verifikasi tampil
- [ ] **Pagination**: Data >10 item bisa pagination
- [ ] **Session Message**: Success/Error messages muncul
- [ ] **Redirect**: After CRUD redirect ke index

---

## 🚨 Troubleshooting

### Masalah: "Data tidak muncul di index"
**Solusi:**
- Pastikan sudah run migration: `php artisan migrate`
- Pastikan sudah run seeder: `php artisan db:seed`
- Check database connection di .env

### Masalah: "Poster tidak muncul saat upload"
**Solusi:**
- Pastikan storage link sudah dibuat: `php artisan storage:link`
- Check folder public/storage ada
- Check permissions folder storage/app/public

### Masalah: "Validasi error tidak tampil"
**Solusi:**
- Pastikan error directive di blade: `@error('field_name')`
- Check bahwa `old()` helper berfungsi
- Lihat di console browser ada response error

---

## 📞 Support

Jika ada error atau issue, check:
1. Laravel log: `storage/logs/laravel.log`
2. Database: `php artisan tinker` → `Event::all()`
3. Routes: `php artisan route:list | grep admin.events`

---

**Status:** ✅ Ready for Testing
**Last Updated:** 2026-05-03
**Version:** 1.0
