# 🚛 LogiTrack: Smart Distribution Monitoring System

**LogiTrack** adalah platform manajemen logistik dan pemantauan distribusi unit kendaraan tingkat lanjut yang dirancang untuk memberikan transparansi penuh, efisiensi operasional, dan deteksi dini masalah (_bottleneck_) dalam rantai pasokan.

---

## 📸 Solusi Visual (Project Previews)

Bayangkan memiliki kendali penuh atas setiap unit kendaraan yang keluar dari gudang hingga sampai ke tangan pelanggan. Berikut adalah tampilan bagaimana LogiTrack membantu bisnis Anda:

|              ![Dashboard Utama](public/screenshots/dashboard.png)               |           ![Daftar Pengiriman](public/screenshots/shipment.png)            |                   ![Manajemen Stok](public/screenshots/unit.png)                    |
| :-----------------------------------------------------------------------------: | :------------------------------------------------------------------------: | :---------------------------------------------------------------------------------: |
| **Control Center**: Pantau semua KPI (Lead Time, Stok, Delay) dalam satu layar. | **Real-time Tracking**: Status pengiriman unit yang akurat dan transparan. | **Inventory Control**: Pantau setiap unit kendaraan berdasarkan nomor rangka (VIN). |

|     ![Rencana Distribusi](public/screenshots/distribution-plan.png)      |            ![Gudang Lokasi](public/screenshots/warehouse.png)            |               ![Manajemen User](public/screenshots/users.png)               |
| :----------------------------------------------------------------------: | :----------------------------------------------------------------------: | :-------------------------------------------------------------------------: |
| **Smart Planning**: Susun rencana pengiriman bulanan ke berbagai dealer. | **Multi-Warehouse**: Kelola berbagai lokasi penyimpanan secara terpusat. | **Secure Access**: Kontrol ketat siapa yang bisa melihat dan mengubah data. |

---

## 🚀 Manfaat Utama untuk Bisnis Anda

### 📊 1. Pengambilan Keputusan Berbasis Data (Command Center)

Tidak ada lagi tebak-tebakan. Dashboard eksekutif kami menyajikan kesehatan distribusi secara real-time.

- **Efisiensi Waktu**: Pantau kecepatan pengiriman (_Lead Time_) untuk memastikan unit sampai tepat waktu.
- **Tren Bisnis**: Analisis perbandingan rencana vs realisasi penjualan di setiap wilayah/dealer.

### 🚨 2. Deteksi Dini Masalah (Predictive Bottleneck)

Sistem secara cerdas memberi tahu Anda jika ada unit yang "macet" atau berhenti bergerak terlalu lama di perjalanan.

- **Auto-Alert**: Notifikasi visual untuk unit yang tidak bergerak lebih dari 3 hari.
- **Intervensi Cepat**: Cegah komplain pelanggan dengan menangani masalah sebelum mereka menyadarinya.

### 🔍 3. Pencarian Instant & Riwayat Lengkap

Cari data ribuan unit semudah mencari di Google.

- **Lacak via VIN**: Cukup ketik nomor rangka, dan sistem akan menampilkan seluruh riwayat perjalanan unit tersebut.
- **Transparansi Penuh**: Lihat tepatnya kapan unit masuk gudang, kapan dikirim, dan siapa pengemudinya.

### 🏗️ 4. Kontrol Stok Tanpa Celah

Hindari stok lama mengendap (_Dead Stock_) yang membebani biaya gudang.

- **Stock Aging**: Sistem menghitung otomatis umur unit di gudang agar Anda bisa memprioritaskan unit lama untuk segera dikirim.
- **Optimasi Ruang**: Pastikan setiap sudut gudang terutilisasi dengan maksimal.

### 🔐 5. Keamanan & Kepercayaan Data

Setiap staf memiliki akses yang sesuai dengan tanggung jawabnya.

- **Privasi Terjamin**: Staf gudang tidak akan bisa melihat data sensitif keuangan, begitu juga sebaliknya.
- **Audit Trail**: Semua aktivitas terekam, sehingga memudahkan audit jika terjadi selisih data.

---

## ⚙️ Cara Menjalankan Project (Untuk Tim IT)

<details>
<summary>Klik untuk melihat instruksi teknis</summary>

1. Clone repository:
    ```bash
    git clone https://github.com/kangjessy/logitrack.git
    ```
2. Install dependencies:
    ```bash
    composer install && npm install && npm run build
    ```
3. Setup Environment:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4. Migrasi & Seed Data:
    ```bash
    php artisan migrate --seed
    ```
5. Jalankan server:
`bash
    php artisan serve
    `
 </details>

---

dikembangkan dengan ❤️ untuk Solusi Logistik Modern.
