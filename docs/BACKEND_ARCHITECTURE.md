# Dokumentasi Arsitektur Backend (Laravel) - Arena Kita

## 1\. Pendahuluan

Dokumen ini menguraikan arsitektur, pola standar, dan struktur folder yang diterapkan dalam pengembangan backend proyek "Arena Kita". Tujuan dari dokumentasi ini adalah untuk memastikan setiap developer mengikuti pedoman yang sama, sehingga menghasilkan kode yang konsisten, mudah dipelihara (*maintainable*), dan skalabel.

-----

## 2\. Arsitektur Folder Standar

Struktur folder ini dirancang untuk skalabilitas, dengan mengelompokkan *request* berdasarkan fitur dan memisahkan *logic* inti.

```ascii
/app
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── V1/
│   │           ├── Auth/  <-- (Folder dari Sanctum/Auth Bawaan)
│   │           │   ├── AuthenticatedSessionController.php
│   │           │   └── ...
│   │           ├── VenueController.php  <-- (Controller Fitur)
│   │           ├── BookingController.php
│   │           └── (Controller fitur lainnya...)
│   │
│   └── Requests/
│       └── Api/
│           └── V1/
│               ├── Venue/  <-- (1) FOLDER PER FITUR
│               │   ├── StoreRequest.php
│               │   └── UpdateRequest.php
│               └── Booking/
│                   ├── StoreRequest.php
│                   └── ...
│
├── Models/
│   ├── User.php
│   └── Venue.php
│
├── Providers/
│
├── Services/           <-- (2) SERVICE LAYER
│   ├── AuthService.php
│   ├── VenueService.php
│   └── BookingService.php
│
└── Traits/             <-- (3) LOKASI UNTUK RESPONSE TRAIT
    └── ApiResponseTrait.php
```

### Penjelasan Struktur:

1.  **Form Requests (`app/Http/Requests/Api/V1/...`)**:

      * Setiap fitur (misal: `Venue`, `Booking`) memiliki folder sendiri.
      * Di dalamnya terdapat *Request* spesifik untuk aksi *controller* (misal: `StoreRequest.php`, `UpdateRequest.php`).

2.  **Services (`app/Services/`)**:

      * Menampung semua logika bisnis (*business logic*). *Controller* akan memanggil *class* ini.

3.  **Traits (`app/Traits/`)**:

      * Lokasi untuk fungsionalitas *reusable* (bisa dipakai ulang) seperti `ApiResponseTrait.php`, yang bukan merupakan *Controller* itu sendiri.

-----

## 3\. Standarisasi Respon API (`ApiResponseTrait`)

Untuk menjamin semua respon API memiliki struktur JSON yang seragam, proyek ini mengadopsi sebuah **Trait**.

### A. Tujuan

Menyeragamkan semua format *output* JSON, baik untuk respon sukses maupun error, sehingga memudahkan tim frontend.

### B. Implementasi

Setiap *controller* di dalam `app/Http/Controllers/Api/V1/` (kecuali mungkin bawaan Sanctum) **wajib** untuk meng-`use` *trait* `ApiResponseTrait`.

**Contoh di `VenueController.php`:**

```php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller; // Controller Laravel dasar
use App\Traits\ApiResponseTrait; // <-- 1. Impor Trait
use App\Services\VenueService;
use App\Http\Requests\Api\V1\Venue\StoreRequest;

class VenueController extends Controller
{
    use ApiResponseTrait; // <-- 2. Gunakan Trait

    protected $venueService;

    public function __construct(VenueService $venueService)
    {
        $this->venueService = $venueService;
    }

    public function store(StoreRequest $request)
    {
        try {
            $venue = $this->venueService->createVenue($request->validated());
            // 3. Panggil method dari Trait
            return $this->sendSuccessWithData($venue, 'Venue berhasil dibuat', 201); 
        } catch (\Exception $e) {
            // 3. Panggil method dari Trait
            return $this->sendInternalError($e); 
        }
    }
}
```

> **Catatan untuk Controller Auth Bawaan Sanctum:**
> File *controller* yang di-*generate* oleh Sanctum (seperti `AuthenticatedSessionController.php`) mungkin **tidak** mengikuti standar respon ini. Jika keseragaman format JSON (terutama untuk error 401/422) diperlukan, Anda mungkin perlu meng-*override* atau mengubah *method* di dalamnya agar menggunakan `ApiResponseTrait` ini.

### C. Metode Utama (Tersedia via Trait)

  * `sendSuccess($message, $code = 200)`: Respon sukses tanpa data.
  * `sendSuccessWithData($data, $message = 'Success', $code = 200)`: Respon sukses dengan data.
  * `sendError($errorMessage, $errors = [], $code = 400)`: Respon error terkelola (misal: validasi).
  * `sendNotFound($errorMessage = 'Data not found', $code = 404)`: Respon data tidak ditemukan.
  * `sendInternalError(\Exception $exception, $code = 500)`: Respon *unhandled exception*.

### D. Contoh Struktur Output JSON

**Respon Sukses (dengan Data):**

```json
{
  "status": "success",
  "message": "Data venue berhasil diambil",
  "data": {
    "id": 1,
    "name": "Arena Futsal Jaya"
  }
}
```

**Respon Error (Validasi Gagal):**

```json
{
  "status": "error",
  "message": "Validasi gagal",
  "errors": {
    "email": ["Format email tidak valid."]
  }
}
```

-----

## 4\. Pola Model (Eloquent)

Model adalah representasi entitas bisnis dan data.

1.  **Relationship (Relasi):**

      * Semua relasi antar tabel (`hasMany`, `belongsTo`, dll) *harus* didefinisikan secara eksplisit sebagai *method* di dalam model.
      * Gunakan *eager loading* (`with()`) di *service* untuk menghindari masalah N+1 Query.

2.  **Formatter (Accessors & Mutators):**

      * Gunakan **Accessors** (`getNamaAttribute`) untuk memformat data *sebelum* dikirim ke respon API (misal: `getLogoUrlAttribute` untuk mengubah *path* gambar menjadi URL lengkap).
      * Gunakan **Mutators** (`setNamaAttribute`) untuk mengubah data *sebelum* disimpan ke database (misal: *hashing password*).

3.  **Authenticable:**

      * Model `User` mengimplementasikan *contract* `Authenticable` dan menggunakan *trait* `HasApiTokens` (Sanctum).

-----

## 5\. Arsitektur Controller & Service Layer

Proyek ini menerapkan pemisahan tanggung jawab (*Separation of Concerns*) untuk menjaga agar *Controller* tetap "ramping" (*thin*).

### A. Alur Kerja (Request Flow)

`Route` $\rightarrow$ `Controller` $\rightarrow$ `FormRequest (Validation)` $\rightarrow$ `Service (Business Logic)` $\rightarrow$ `Model (Database)` $\rightarrow$ `Controller (Response)`

### B. Controller (General Controller)

  * **Lokasi:** `app/Http/Controllers/Api/V1/`
  * **Tanggung Jawab:** Hanyalah "jembatan".
    1.  Menerima `Request` dari *client*.
    2.  Memvalidasi input menggunakan `FormRequest`.
    3.  Memanggil *method* yang sesuai di dalam `Service Class`.
    4.  Menangkap `Exception` jika terjadi.
    5.  Mengembalikan respon JSON standar (menggunakan `ApiResponseTrait`).
  * **Controller DILARANG berisi logika bisnis** (kalkulasi, pemrosesan data, dll).

### C. Service (Business Logic)

  * **Lokasi:** `app/Services/`
  * **Tanggung Jawab:** Tempat semua logika bisnis.
    1.  Menerima data yang sudah tervalidasi dari *Controller*.
    2.  Melakukan semua proses bisnis (kalkulasi harga, cek ketersediaan slot, interaksi dengan API eksternal seperti Midtrans).
    3.  Berinteraksi dengan *Model* untuk operasi database (CRUD).
    4.  Mengembalikan data hasil pemrosesan atau *melempar exception* jika gagal.

### D. Form Request

  * **Lokasi:** `app/Http/Requests/Api/V1/[NamaFitur]/`
  * **Tanggung Jawab:** Validasi *input* **wajib** menggunakan *class* ini. Jangan pernah melakukan validasi manual di dalam *Controller*. Ini membuat validasi terpusat, *reusable*, dan *Controller* tetap bersih.

### E. Error Handling & Transaksi

1.  **Try-Catch:**
      * Gunakan blok `try-catch` di dalam *method Controller* untuk menangkap *exception* dari *Service* dan mengembalikan respon error yang "cantik".
2.  **DB Transaction:**
      * Setiap operasi yang melibatkan **lebih dari satu kali *write*** ke database (misal: membuat *booking* sekaligus mengurangi stok atau mencatat pembayaran) **wajib** dibungkus dalam `DB::transaction()`. Ini biasanya dilakukan di dalam *Service*.

### F. Logging

Logging krusial untuk *debugging* di lingkungan produksi.

1.  **Log Error (Wajib):**

      * Di dalam blok `catch`, setiap *exception* yang terjadi (terutama `QueryException`, `ValidationException`, atau `\Exception` umum) **harus** dicatat ke *log* sebelum dikirim sebagai respon.
      * Gunakan `Log::error($exception->getMessage(), ['context' => $exception]);`.

2.  **Log Warning (Slow Query):**

      * Untuk memantau performa, *query* yang berjalan lambat (misal: \> 5 detik) akan dicatat sebagai *warning*. Ini dikonfigurasi di `AppServiceProvider.php` pada *method* `boot()`:

    <!-- end list -->

    ```php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    public function boot()
    {
        if (!app()->isProduction()) { // Hanya aktifkan di non-produksi jika perlu
            DB::listen(function ($query) {
                if ($query->time > 5000) { // 5000ms = 5 detik
                    Log::warning(
                        "Query berjalan lambat: {$query->sql}",
                        ['time' => "{$query->time}ms", 'bindings' => $query->bindings]
                    );
                }
            });
        }
    }
    ```

-----

## 6\. Organisasi Route (Routing)

Untuk menjaga agar file `routes/api.php` tetap rapi dan mudah dibaca, diterapkan aturan berikut:

1.  **Versioning (Versi):**
      * Semua *route* API dibungkus dalam *prefix* versi (misal: `v1`).
2.  **Grouping (Grup) & Prefix:**
      * *Route* dikelompokkan berdasarkan fitur utama menggunakan `Route::prefix()`.
3.  **Controller Grouping:**
      * Gunakan `Route::controller()` untuk menyederhanakan definisi *route* yang merujuk ke *controller* yang sama.
4.  **Middleware:**
      * *Middleware* diterapkan pada level grup untuk menjaga konsistensi.
      * `auth:sanctum`: Diterapkan pada semua grup *route* yang membutuhkan autentikasi.

### Contoh `routes/api.php`

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\VenueController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\Auth; // <-- Namespace Auth bawaan

// --- API Versi 1 ---
Route::prefix('v1')->group(function () {

    // Rute Publik (Autentikasi - Bawaan Sanctum)
    Route::post('/register', [Auth\RegisteredUserController::class, 'store'])->name('register');
    Route::post('/login', [Auth\AuthenticatedSessionController::class, 'store'])->name('login');

    // Rute Publik (Venue)
    Route::prefix('venues')->controller(VenueController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });

    // Rute Terproteksi (Membutuhkan Login)
    Route::middleware('auth:sanctum')->group(function () {
    
        Route::post('/logout', [Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Rute Booking
        Route::prefix('bookings')->controller(BookingController::class)->group(function () {
            Route::get('/', 'index');        // Lihat booking saya
            Route::post('/', 'store');       // Buat booking baru
        });

        // Rute Manajemen Venue (Contoh untuk Mitra)
        Route::prefix('my-venues')
            ->middleware('role:mitra') // Middleware kustom untuk cek role
            ->controller(VenueController::class) // Bisa pakai controller lain
            ->group(function () {
                Route::post('/', 'store'); // 'store' di sini beda dgn di VenueController biasa
                Route::put('/{id}', 'update');
        });
    });
});
```