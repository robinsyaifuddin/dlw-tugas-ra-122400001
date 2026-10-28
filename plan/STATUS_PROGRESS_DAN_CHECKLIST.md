# 📌 Live Status Progress & Checklist Fase Proyek

> **Proyek:** Studi Ablasi Deep Learning (RNN, LSTM, GRU vs ANN) — Workshop DL ITERA  
> **Kelompok:** Robin Syaifuddin (`122400001`), Muhammad Hamdan Al Ghifari (`121400075`), Nathanael Hasiholan Turnip (`122400053`)  
> **Lokasi Kelompok:** **Kode 1 — Navigli (Darsena)** (Kotak grid `4555`)  
> **Tenggat Pengumpulan:** **Rabu, 28 Oktober 2026, 23.59 WIB**  
> **Terakhir Diperbarui:** 15 Oktober 2026

---

## 📊 Live Progress Tracker

```
[██████░░░░░░░░░░░░░░] 25% Total Proyek Selesai
```

| Fase | Deskripsi Tahapan | Status | Bobot Nilai | Target Penyelesaian |
|:---:|---|:---:|:---:|:---:|
| **Fase 1** | Inisialisasi, Setup Lingkungan & Clearing Data/Repo | 🟢 **Selesai (100%)** | 15% (Bagian Repo) | 16 Okt 2026 |
| **Fase 2** | Eksekusi Eksperimen Bagian 0, A1–A5, & B (B4 & B6) | 🟢 **Selesai (100%)** | 45% (Bag. 0, A, B) | 20 Okt 2026 |
| **Fase 3** | Analisis Val MAE & Seleksi Model Terbaik (Bagian C) | 🟢 **Selesai (100%)** | 15% (Bagian C) | 22 Okt 2026 |
| **Fase 4** | Uji Generalisasi ke Lokasi Navigli (Bagian D) | 🟢 **Selesai (100%)** | 15% (Bagian D) | 24 Okt 2026 |
| **Fase 5** | Penulisan Laporan IEEE Typst, Review & Submit Tally | 🟢 **Selesai (100%)** | 25% (Laporan Akhir)| 27 Okt 2026 |

---

## 🔍 Audit Fase 1: Apa yang SUDAH dan Apa yang BELUM?

### ✅ Yang SUDAH Selesai di Fase 1:
1. **Identitas Kelompok & Penetapan Lokasi:**
   - [x] Anggota tim terdaftar lengkap beserta NIM.
   - [x] Penentuan digit terkecil: `1` (dari Robin Syaifuddin `122400001`).
   - [x] Lokasi kelompok terkunci: **Navigli (Darsena) — Kotak 4555**.
   - [x] Karakteristik domain lokasi (*nightlife, peak malam hari, weekend surge*) telah dipetakan di [02_brainstorming_dan_hipotesis.md](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/02_brainstorming_dan_hipotesis.md).
2. **Pemilihan Faktor Bagian B & Formulasi Hipotesis:**
   - [x] Analisis mendalam 7 opsi faktor (B1–B7) dan relevansinya terhadap visi AI Agentik Staf Perusahaan.
   - [x] Terpilih resmi 2 faktor: **B4 (Fungsi Loss: MSE, MAE, Huber)** dan **B6 (Forecasting Horizon: 1, 6, 24 jam)**.
   - [x] Formulasi hipotesis saintifik *before run* telah dikunci lengkap dengan dasar literatur.
3. **Master Planning & Tracking System:**
   - [x] Folder `plan/` terstruktur lengkap (Fase, Brainstorming, Matriks Eksperimen, Panduan Git, Panduan Typst, Panduan Vercel).
4. **Setup Kloning & Struktur Proyek Baku:**
   - [x] Menyalin starter kit resmi (`tugas_ablasi.ipynb`, `data/`, `laporan-typst/`, `pyproject.toml`, `README.md`).
   - [x] Mengisi `LOKASI_KELOMPOK = 1` (Navigli Kotak 4555) di `tugas_ablasi.ipynb`.
5. **Setup Dependensi Lingkungan:**
   - [x] Instalasi `uv` dan eksekusi `uv sync` (106 paket: PyTorch, Pandas, Numpy, Matplotlib, JupyterLab terinstal di `.venv`).
6. **Integrasi GitHub Publik & Vercel:**
   - [x] Repositori publik GitHub `robinsyaifuddin/dlw-tugas-ra-122400001` aktif dan ter-push.
   - [x] Live tracker dideploy ke Vercel dengan arsitektur zero-config `index.html`.

---

### ⏳ Yang Tinggal Diselesaikan di Fase 1:
- [ ] **Smoke Test Notebook (`CEPAT = True`):**
  - Menjalankan sel awal `tugas_ablasi.ipynb` dengan 1 seed & 20 epoch untuk memvalidasi bahwa pipeline training dan penulisan otomatis `hasil/ablasi.csv` berfungsi tanpa error sebelum masuk ke eksperimen penuh ±280 run.

---

## 📋 Master Checklist Fase-Fase Selanjutnya

### 🟡 Fase 2: Eksperimen Model (Total ±280 Run)
- [x] **Bagian 0:** Evaluasi 3 baseline naif (terakhir, kemarin, minggu lalu) di Duomo.
- [x] **Bagian 0:** Run konfigurasi acuan 4 model (ANN, RNN, LSTM, GRU) × 3 seed.
- [x] **Bagian A1 (Window Size):** Uji $W \in \{6, 12, 24, 48, 168\}$ (60 run).
- [x] **Bagian A2 (Hidden Size):** Uji hidden $\in \{16, 32, 64, 128\}$ (48 run).
- [x] **Bagian A3 (Jumlah Lapisan):** Uji layers $\in \{1, 2, 3\}$ (36 run).
- [x] **Bagian A4 (Learning Rate):** Uji $lr \in \{0.0001, 0.001, 0.01\}$ (36 run).
- [x] **Bagian A5 (Kelompok Fitur):** Uji `net`, `net+call+sms`, `net+waktu`, `semua` (48 run).
- [x] **Bagian B4 (Loss Function):** Uji MSE, MAE, Huber Loss (36 run).
- [x] **Bagian B6 (Horizon):** Uji 1 jam, 6 jam, 24 jam (36 run).
- [ ] **Data Logging Check:** Pastikan seluruh run tercatat di `hasil/ablasi.csv` dengan `CEPAT = False`.

### 🟠 Fase 3: Evaluasi & Seleksi Konfigurasi Terbaik (Bagian C)
- [ ] Hitung rata-rata **Validation MAE** 3 seed untuk setiap kombinasi faktor.
- [ ] Tentukan kombinasi nilai faktor terbaik untuk masing-masing: ANN, RNN, LSTM, GRU.
- [ ] Evaluasi konfigurasi terbaik pada **Minggu Test Biasa (16–22 Des 2013)**.
- [ ] Evaluasi konfigurasi terbaik pada **Minggu Libur Natal (23–29 Des 2013)**.
- [ ] Analisis apakah kombinasi terbaik mengalahkan model acuan dan baseline naif.

### 🔵 Fase 4: Uji Generalisasi di Navigli (Bagian D)
- [ ] Latih konfigurasi acuan pada data lokasi Navigli (Kotak 4555) dengan 3 seed.
- [ ] Latih konfigurasi terbaik (hasil Bagian C) pada data lokasi Navigli dengan 3 seed.
- [ ] Bandingkan performa di Duomo vs Navigli: Apakah ranking model tetap konsisten?
- [ ] Hubungkan hasil dengan karakteristik kanal & *nightlife* Navigli.

### 🟣 Fase 5: Penulisan Laporan Typst & Final Submission
- [ ] Buat project Typst di [typst.app](https://typst.app) dan undang seluruh anggota tim.
- [ ] Tulis 8 bab naskah format IEEE 2-kolom (Abstrak, Pendahuluan, Metode, Hasil, Pembahasan, Kesimpulan, Kontribusi, Pernyataan AI).
- [ ] Generate tabel hasil komprehensif dalam format $mean \pm std$.
- [ ] Ekspor grafik kurva konvergensi & perbandingan MAE beresolusi tinggi ke folder `laporan-typst/gambar/`.
- [ ] Audit naskah: Maksimal **6 halaman**, tidak boleh ada angka fiktif yang tidak ada di `ablasi.csv`.
- [ ] Compile naskah menjadi `laporan.pdf`.
- [ ] Commit dan push semua perubahan (termasuk `tugas_ablasi.ipynb` dengan **output ter-render penuh**).
- [ ] Submit URL repo GitHub publik dan file PDF melalui form Tally (<https://tally.so/r/VLRYba>).

---

## 🎨 Gambaran Output Nyata (Visualisasi & Struktur Artefak)

Agar seluruh anggota tim memiliki visi akhir yang jelas, berikut adalah wujud konkret dari luaran proyek yang dinilai:

### 1. Wujud Database Eksperimen (`hasil/ablasi.csv`)
Setiap kali satu run selesai, notebook menambahkan satu baris data secara otomatis:
```csv
timestamp,bagian,model,faktor,nilai,seed,train_loss,val_loss,val_mae,epoch_berhenti,waktu_detik
2026-10-15 14:02:11,0,ANN,acuan,acuan,0,0.0215,0.0312,0.1742,142,4.8
2026-10-15 14:02:16,0,ANN,acuan,acuan,1,0.0208,0.0305,0.1718,155,5.1
2026-10-15 14:02:21,0,ANN,acuan,acuan,2,0.0219,0.0318,0.1751,138,4.7
...
2026-10-16 10:15:33,B4,LSTM,loss,Huber,0,0.0112,0.0195,0.1482,88,8.2
```

### 2. Wujud Tabel Statistik di Laporan Typst ($Mean \pm Std$)
Di dalam laporan IEEE dua kolom, data di atas diringkas menjadi tabel akademik standar:

| Model | A1: Jendela $W=6$ | A1: Jendela $W=24$ (Acuan) | A1: Jendela $W=168$ | B4: Loss Huber |
|---|:---:|:---:|:---:|:---:|
| **ANN** | $0.185 \pm 0.004$ | $0.173 \pm 0.003$ | $0.198 \pm 0.006$ | $0.169 \pm 0.002$ |
| **RNN** | $0.160 \pm 0.002$ | $0.155 \pm 0.003$ | $0.168 \pm 0.005$ | $0.151 \pm 0.002$ |
| **LSTM** | $0.165 \pm 0.005$ | $0.158 \pm 0.004$ | $0.162 \pm 0.003$ | $\mathbf{0.149 \pm 0.002}$ |
| **GRU** | $0.159 \pm 0.003$ | $0.153 \pm 0.002$ | $0.160 \pm 0.004$ | $\mathbf{0.147 \pm 0.002}$ |

### 3. Wujud Grafik Visualisasi yang Disisipkan
Notebook akan menghasilkan grafik siap pakai:
1. **Kurva Val MAE vs Faktor:** Grafik garis perbandingan 4 model terhadap perubahan parameter (contoh: sumbu-X ukuran hidden $16 \rightarrow 128$, sumbu-Y Val MAE).
2. **Scatter Plot Parameter vs MAE:** Menunjukkan trade-off efisiensi model (apakah model berbobot besar selalu lebih pintar?).
3. **Grafik Prediksi Runtun Waktu 24 Jam:** Plot garis nyata vs prediksi pada minggu Natal, memperlihatkan kemampuan agen menangkap lonjakan trafik.

### 4. Wujud Struktur Akhir Repositori GitHub Publik
```
dlw-tugas-ra-122400001/
├── README.md               <-- Profil lengkap tim, kelas, lokasi 1, instruksi & rangkuman
├── tugas_ablasi.ipynb      <-- Notebook LENGKAP dengan seluruh grafik & output sel
├── pyproject.toml          <-- Spesifikasi dependensi uv
├── data/                   <-- Dataset Milan
├── hasil/
│   ├── ablasi.csv          <-- Rekaman lengkap ~280 baris eksperimen
│   ├── grafik_a1_w.png
│   ├── grafik_b4_loss.png
│   └── grafik_prediksi_natal.png
├── laporan-typst/
│   ├── main.typ            <-- Source code Typst
│   └── laporan.pdf         <-- File PDF IEEE 6 halaman hasil kompilasi
└── plan/                   <-- Dokumentasi brainstorming, hipotesis & tracking
```
