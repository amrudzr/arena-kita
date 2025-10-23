# ArenaKita - Backend (Laravel API)

Ini adalah repositori resmi untuk layanan backend Proyek ArenaKita. Proyek ini berfungsi sebagai API (headless) yang akan dikonsumsi oleh frontend Next.js.

## Visi Proyek

Menjadi platform web andalan untuk memudahkan pengguna menemukan dan memesan venue olahraga, sekaligus membantu pemilik venue mengelola bisnis mereka secara digital dan efisien.

## Daftar Isi

* [🚩 Prasyarat (Wajib Terinstal)](#-prasyarat-wajib-terinstal)
* [🚀 Panduan Instalasi Lokal](#-panduan-instalasi-lokal)
  * [1. Clone Repositori](#1-clone-repositori)
  * [2. Instal Dependensi PHP](#2-instal-dependensi-php)
  * [3. Konfigurasi Environment (.env)](#3-konfigurasi-environment-env)
  * [4. Setup Database Lokal](#4-setup-database-lokal)
  * [5. Edit File .env](#5-edit-file-env)
  * [6. Migrasi & Seed Database](#6-migrasi--seed-database)
  * [7. Jalankan Server](#7-jalankan-server)
* [🔑 Akun Login (Data Dummy)](#-akun-login-data-dummy)
* [📦 Alur Kerja Git & Kontribusi](#-alur-kerja-git--kontribusi)
  * [1. Branch Utama:](#1-branch-utama)
  * [2. Membuat Fitur Baru:](#2-membuat-fitur-baru)
  * [3. Pull update terbaru dari 'develop':](#3-pull-update-terbaru-dari-develop)
  * [4. Buat branch baru:](#4-buat-branch-baru)
  * [5. Selesai Mengerjakan Fitur:](#5-selesai-mengerjakan-fitur)
* [🧱 Alur Kerja Fork (Untuk Kontributor Tim)](#-alur-kerja-fork-untuk-kontributor-tim)
  * [1. Fork & Clone (Hanya sekali)](#1-fork--clone-hanya-sekali)
  * [2. Tambahkan 'Upstream' (Hanya sekali)](#2-tambahkan-upstream-hanya-sekali)
  * [3. Alur Kerja Harian (Setiap akan memulai fitur baru)](#3-alur-kerja-harian-setiap-akan-memulai-fitur-baru)
  * [4. Mengajukan Pull Request (PR)](#4-mengajukan-pull-request-pr)
* [Format Nama Branch](#format-nama-branch)
* [Format Pesan Commit (Conventional Commits)](#format-pesan-commit-conventional-commits)
* [Format Template Pull Request (PR)](#format-template-pull-request-pr)

## 🚩 Prasyarat (Wajib Terinstal)

Pastikan perangkat Anda memiliki *software* berikut sebelum instalasi:

* **PHP:** `^8.2`
* **Composer:** `^2.0`
* **Database:** MySQL `^5.7` (atau MariaDB yang kompatibel)
* **Git**

---

## 🚀 Panduan Instalasi Lokal

Langkah-langkah untuk menjalankan proyek ini di komputer lokal Anda.

### 1. Clone Repositori

```bash
git clone https://github.com/amrudzr/arena-kita.git
cd arena-kita
```
### 2. Instal Dependensi PHP
```bash
composer install
```
### 3. Konfigurasi Environment (.env)
Salin file contoh dan buat kunci aplikasi.
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Setup Database Lokal
Buka tool database Anda (phpMyAdmin, TablePlus, DBeaver, dll).

Buat database MySQL baru, misalnya: `arena_kita`

### 5. Edit File .env
Buka file .env dan sesuaikan pengaturan database Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=arena_kita
DB_USERNAME=[MASUKKAN_USERNAME_DB_ANDA]
DB_PASSWORD=[MASUKKAN_PASSWORD_DB_ANDA]
```
### 6. Migrasi & Seed Database
Perintah ini akan membuat semua tabel (termasuk users, owners, venues, bookings, dll) dan mengisinya dengan data dummy (akun admin, owner, venue, dll).

```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server
```bash
php artisan serve
```
🎉 Backend Anda sekarang berjalan! (Biasanya di http://127.0.0.1:8000)

### 🔑 Akun Login (Data Dummy)
Setelah Anda menjalankan `migrate:fresh --seed`, Anda bisa menggunakan akun-akun berikut untuk pengujian API:
```Ini, TOML
Admin / User Biasa:
Email: admin@arenakita.com
Password: password

Pemilik Venue (Owner):
Email: owner@arenakita.com
Password: password
```

### 📦 Alur Kerja Git & Kontribusi
Untuk menjaga repositori tetap bersih, ikuti alur ini:

#### 1. Branch Utama:

`main`: Hanya untuk kode produksi (rilis stabil). **DILARANG PUSH LANGSUNG.**

`develop`: Branch integrasi. Semua fitur di-merge ke sini terlebih dahulu.

#### 2. Membuat Fitur Baru:

Selalu buat branch baru dari develop.

Gunakan format nama: `feature/[NAMA_FITUR]`

(misal: `feature/F-2.1-daftar-venue`)

#### 3. Pull update terbaru dari 'develop':
``` bash
git checkout develop
git pull
```

#### 4. Buat branch baru:
```bash
git checkout -b feature/NAMA_FITUR
```

#### 5. Selesai Mengerjakan Fitur:

- Commit pekerjaan Anda dengan pesan yang jelas.
- Push branch Anda ke GitHub.
- Buat Pull Request (PR) dari branch Anda ke develop.
- Tunggu review dan merge dari anggota tim lain.

### 🧱 Alur Kerja Fork (Untuk Kontributor Tim)
Jika Anda lebih memilih untuk fork repositori utama (bukan push ke branch di repo utama), alur kerjanya sedikit berbeda dan membutuhkan sinkronisasi.

#### 1. Fork & Clone (Hanya sekali)

Klik tombol "Fork" di halaman GitHub [amrudzr/arena-kita](https://github.com/amrudzr/arena-kita.git) untuk membuat salinan di akun Anda.

Clone fork Anda (bukan repo utama milik [amrudzr](https://github.com/amrudzr)) ke lokal:

```bash
git clone https://github.com/[USERNAME_ANDA]/arena-kita.git
cd arena-kita
```

#### 2. Tambahkan 'Upstream' (Hanya sekali)

Tambahkan repositori utama (`amrudzr/arena-kita`) sebagai remote bernama upstream. Ini adalah "induk" yang akan kita ikuti.

```bash
git remote add upstream https://github.com/amrudzr/arena-kita.git
```
#### 3. Alur Kerja Harian (Setiap akan memulai fitur baru)

Selalu sinkronkan develop Anda dengan upstream sebelum membuat branch baru.

```bash
# Pindah ke branch develop lokal Anda
git checkout develop

# Tarik semua perubahan terbaru dari 'upstream' (induk)
git fetch upstream

# Samakan branch develop lokal Anda dengan develop induk
git rebase upstream/develop

# (Opsional) Update fork Anda di GitHub
git push origin develop
```

Sekarang, buat branch fitur Anda seperti biasa dari develop yang sudah sinkron:

```bash
git checkout -b feature/NAMA_FITUR
```

#### 4. Mengajukan Pull Request (PR)

Saat selesai, push branch fitur Anda ke fork Anda (origin):

```bash
git push -u origin feature/NAMA_FITUR
```

Buka GitHub dan buat Pull Request dari `[USERNAME_ANDA]:feature/NAMA_FITUR` ke `amrudzr:develop`.

### Format Nama Branch
Menggunakan format yang konsisten membantu melacak perubahan. Gunakan prefix berikut berdasarkan jenis pekerjaan:

- `feat/`: Untuk fitur baru (misal: `feat/F-2.1-daftar-venue`)

- `fix/`: Untuk memperbaiki bug (misal: `fix/login-error-500`)

- `docs/`: Untuk menambah atau mengubah dokumentasi (misal: `docs/update-readme-instalasi`)

- `refactor/`: Untuk mengubah kode tanpa mengubah fungsionalitas (misal: `refactor/venue-controller-query`)

- `test/`: Untuk menambah atau memperbaiki test case (misal: `test/venue-api-test`)

- `chore/`: Untuk tugas-tugas pemeliharaan (misal: `chore/update-laravel-pint`)

Contoh: `feat/F-3.1-realtime-schedule`

### Format Pesan Commit (Conventional Commits)

Direkomendasikan penggunaan [Conventional Commits](https://www.conventionalcommits.org/). Ini membuat *history* Git dapat dibaca mesin dan memudahkan pelacakan perubahan.

**Format:**
```
<type>(<scope>): <subject>

<body>

<footer>
<type> (Wajib): Jenis commit (gunakan prefix yang sama dengan nama branch).
```

* **`<type>` (Wajib):** Jenis commit (gunakan *prefix* yang sama dengan nama *branch*).
      * `feat`: Fitur baru.
      * `fix`: Perbaikan *bug*.
      * `docs`: Perubahan dokumentasi.
      * `refactor`: *Refactoring* kode.
      * `test`: Menambah/mengubah tes.
      * `chore`: Pemeliharaan (update *dependency*, dll).
  * **`<scope>` (Opsional):** Bagian kode yang diubah (misal: `auth`, `venue`, `booking`, `migration`).
  * **`<subject>` (Wajib):** Deskripsi singkat, dalam kalimat perintah (imperatif). **Diawali huruf kecil, tanpa titik di akhir.**
      * ✅ **Gunakan:** `feat(auth): add owner login controller`
      * ❌ **Jangan:** `Added owner login controller`

**Contoh Pesan Commit:**

  * `feat(venue): add get venue list endpoint`
  * `fix(booking): resolve overlapping time validation`
  * `docs(readme): update setup instructions`
  * `refactor(VenueController): simplify query logic using eloquent scopes`

    ```
    Memindahkan query kompleks ke dalam Venue model scope agar controller lebih bersih dan reusable.
    ```

  * `fix(auth): prevent login for soft-deleted users`

    ```
    Closes #42
    ```
### Format Template Pull Request (PR)

Pull Request adalah gerbang utama *review* kode. Pastikan PR Anda jelas dan informatif.

Anda bisa membuat file `PULL_REQUEST_TEMPLATE.md` di folder `.github/` proyek Anda agar template ini muncul otomatis.

**Template:**

```markdown
## 🎟️ Tautkan Isu/Tiket

Closes #[NOMOR_ISU]

## ✏️ Deskripsi Perubahan

Perubahan ini menambahkan endpoint API baru untuk `GET /api/venues` sesuai dengan fitur [F-2.1]. Endpoint ini juga sudah mencakup fungsionalitas filter untuk [F-2.2] (nama/kota) dan [F-2.3] (jenis olahraga) melalui *query parameters*.

## 🧪 Cara Pengujian

1.  Jalankan `php artisan migrate:fresh --seed` (jika ada migrasi baru).
2.  Jalankan server `php artisan serve`.
3.  Buka Postman/Insomnia.
4.  Lakukan `GET` ke `http://127.0.0.1:8000/api/venues`
    * **Harapan:** Mendapat daftar semua venue dengan relasi `photos` dan `fields`.
5.  Lakukan `GET` ke `http://127.0.0.1:8000/api/venues?city=Jakarta`
    * **Harapan:** Mendapat daftar venue yang hanya ada di Jakarta.
6.  Lakukan `GET` ke `http://127.0.0.1:8000/api/venues?sport=Futsal`
    * **Harapan:** Mendapat daftar venue yang memiliki lapangan futsal.

## 📸 Bukti (Screenshot/Video)

[...masukkan screenshot di sini...]
*Hasil request GET /api/venues*

[...masukkan screenshot di sini...]
*Hasil request dengan filter sport=Futsal*

## ✅ Checklist

-   [x] Kode saya mengikuti standar *coding* proyek ini.
-   [x] Saya telah memperbarui dokumentasi (jika diperlukan).
-   [ ] Saya telah menambahkan *test case* (jika diperlukan).
-   [ ] Semua tes *existing* lolos.