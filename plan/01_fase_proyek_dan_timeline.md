# 📅 Fase Proyek, Timeline & Roadmap Eksekusi

> **Tenggat Akhir:** Rabu, 28 Oktober 2026, 23.59 WIB  
> **Total Beban Komputasi:** ±280 run (3 seed × kombinasi hyperparameter)  
> **Estimasi Waktu CPU:** 1–2 jam total (bisa di Google Colab / GPU untuk percepatan)

---

## 🗺️ Roadmap 5 Fase

```
[ Fase 1: Setup & Sanitasi ] ──► [ Fase 2: Eksperimen A & B ] ──► [ Fase 3: Evaluasi & Seleksi C ]
                                                                             │
[ Fase 5: Finalisasi & Pengumpulan ] ◄── [ Fase 4: Uji Lokasi D & Draft Typst ]
```

---

### 🟢 Fase 1: Inisialisasi, Setup Lingkungan & Publikasi Live Tracker (Hari 1–2)
**Tujuan:** Memastikan infrastruktur kode, data, repositori, dan monitoring live publik siap pakai tanpa kendala teknis.

- [x] **1.1. Penentuan Lokasi Kelompok & Hipotesis:**
  - Urutkan NIM anggota kelompok $\rightarrow$ NIM terkecil `122400001` (Robin) $\rightarrow$ Kode Lokasi **1: Navigli (Darsena - Kotak 4555)**.
  - Rumuskan hipotesis ilmiah Bagian B (B4 Fungsi Loss: Huber vs MSE/MAE & B6 Horizon: 1, 6, 24 jam) di [02_brainstorming_dan_hipotesis.md](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/02_brainstorming_dan_hipotesis.md).
- [ ] **1.2. Setup Struktur Repositori & Kloning Starter:**
  - Kloning / salin struktur starter baku: `tugas_ablasi.ipynb`, folder `data/` (`milano_duomo_5060.csv` & `milano_tugas_10_lokasi.csv`), folder `laporan-typst/`, `pyproject.toml`, dan `README.md`.
  - Pastikan repositori GitHub publik dibuat dengan format nama resmi: `dlw-tugas-<kelas>-122400001`.
- [ ] **1.3. Integrasi & Deployment Live Dashboard ke Vercel:**
  - Hubungkan repositori GitHub ke akun Vercel (`vercel.json`).
  - Deploy otomatis ke cloud serverless sehingga tracker dapat diakses publik online (misal: `https://dlw-tugas-ra-122400001.vercel.app`) untuk transparansi tim dan dosen.
- [ ] **1.4. Setup Dependensi & Smoke Test Notebook:**
  - Eksekusi `uv sync` (atau `pip install torch pandas numpy matplotlib jupyterlab`).
  - Set `LOKASI_KELOMPOK = 1` di `tugas_ablasi.ipynb`, uji coba `CEPAT = True` untuk memvalidasi pencatatan ke `hasil/ablasi.csv`.

---

### 🟡 Fase 2: Eksperimen Utama (Bagian 0, Bagian A, dan Bagian B) (Hari 3–6)
**Tujuan:** Menjalankan seluruh variasi hyperparameter secara jujur dan merekam log ke CSV.

- [ ] **2.1. Bagian 0 — Acuan & Baseline Naif:**
  - Hitung 3 baseline naif di Duomo: Nilai terakhir ($t-1$), Kemarin ($t-24$), Minggu lalu ($t-168$).
  - Jalankan konfigurasi acuan (ANN, RNN, LSTM, GRU) dengan 3 seed (0, 1, 2).
- [ ] **2.2. Bagian A — 5 Faktor Ablasi Wajib:**
  - **A1:** Window size $W \in \{6, 12, 24, 48, 168\}$.
  - **A2:** Ukuran hidden $\in \{16, 32, 64, 128\}$.
  - **A3:** Jumlah lapisan $\in \{1, 2, 3\}$.
  - **A4:** Learning rate $\in \{0.0001, 0.001, 0.01\}$.
  - **A5:** Kelompok fitur $\in \{\text{net}, \text{net+call+sms}, \text{net+waktu}, \text{semua}\}$.
- [ ] **2.3. Bagian B — Minimal 2 Faktor Pilihan (Disertai Hipotesis!):**
  - **Wajib:** Formulasikan hipotesis tertulis di `plan/02_brainstorming_dan_hipotesis.md` *sebelum* tombol run ditekan.
  - Pilih minimal 2 dari: B1 (Batch Size), B2 (Dropout), B3 (Optimizer), B4 (Loss), B5 (Early Stopping Patience), B6 (Horizon), B7 (Gradient Clipping).
  - Eksekusi eksperimen Bagian B dengan `CEPAT = False` (3 seed).

---

### 🟠 Fase 3: Analisis Data & Seleksi Konfigurasi Terbaik (Bagian C) (Hari 7–8)
**Tujuan:** Memilih model terbaik secara metodologis dan bebas dari *data leakage*.

- [ ] **3.1. Pemilihan Berbasis Validasi MAE Rata-rata:**
  - ⚠️ **Kaidah Kritis:** Pilih nilai faktor terbaik untuk setiap model HANYA berdasarkan **Validation MAE** (bukan Test MAE!).
  - Kombinasikan parameter terbaik dari A dan B untuk masing-masing model (ANN, RNN, LSTM, GRU).
- [ ] **3.2. Evaluasi Final di Test Set:**
  - Latih konfigurasi gabungan terbaik dengan 3 seed.
  - Evaluasi performa pada:
    1. Minggu Test Biasa (16–22 Des 2013).
    2. Minggu Libur Natal (23–29 Des 2013) — amati anomali trafik liburan!
  - Bandingkan dengan performa acuan & baseline naif. Catat jika kombinasi terbaik justru mengalami degradasi (temuan negatif tetap sah dan ilmiah).

---

### 🔵 Fase 4: Uji Generalisasi ke Lokasi Kelompok (Bagian D) (Hari 9–10)
**Tujuan:** Menguji apakah temuan arsitektur model di Duomo dapat ditransfer ke pola spasial lain.

- [ ] **4.1. Eksekusi di Lokasi Kelompok:**
  - Latih konfigurasi **Acuan** dan **Terbaik (Hasil C)** pada dataset lokasi kelompok (`data/milano_tugas_10_lokasi.csv`).
- [ ] **4.2. Analisis Spasial & Domain Knowledge:**
  - Amati apakah ranking model berubah (misal: apakah LSTM tetap unggul di lokasi kampus vs stadion?).
  - Hubungkan hasil dengan karakteristik trafik lokasi (misal: fluktuasi tajam di San Siro vs siklus reguler di kampus/stasiun).

---

### 🟣 Fase 5: Penulisan Laporan Typst, Review & Submission (Hari 11–Tenggat)
**Tujuan:** Menghasilkan paper IEEE 6 halaman berkualitas tinggi dan repositori publik yang rapi.

- [ ] **5.1. Penyusunan Naskah Typst:**
  - Susun teks dalam bahasa Indonesia ilmiah yang lugas.
  - Masukkan semua angka dalam format `rata-rata ± std` (contoh: $0.142 \pm 0.005$).
  - Sisipkan grafik dari folder `hasil/` dan beri caption informatif yang dirujuk pada badan teks.
- [ ] **5.2. Audit Kepatuhan Format & Integritas:**
  - Pastikan halaman tidak melebihi 6 halaman (IEEE two-column).
  - Lengkapi bagian: *Kontribusi Anggota* dan *Pernyataan Penggunaan AI*.
  - Verifikasi bahwa seluruh angka di paper ada di `hasil/ablasi.csv`.
- [ ] **5.3. Finalisasi Repo & Pengumpulan:**
  - Commit notebook `tugas_ablasi.ipynb` dengan seluruh output sel ter-render.
  - Pastikan visibilitas repo di GitHub adalah **PUBLIK**.
  - Submit link repo & file PDF laporan melalui form Tally sebelum 28 Oktober 23.59 WIB.
