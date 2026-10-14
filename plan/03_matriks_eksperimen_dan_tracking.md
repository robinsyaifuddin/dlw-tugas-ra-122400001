# 📊 Matriks Eksperimen & Tracking Status

File ini memetakan seluruh run eksperimen (±280 run) yang tercatat di `hasil/ablasi.csv`.
Setiap konfigurasi wajib dieksekusi dengan **3 seed (0, 1, 2)** menggunakan `CEPAT = False`.

---

## 📌 Checklist Status Eksperimen Global

- [ ] **Bagian 0: Baseline & Acuan** (12 run + Naive)
- [ ] **Bagian A1: Window Size $W$** (60 run: 5 nilai × 4 model × 3 seed)
- [ ] **Bagian A2: Hidden Size** (48 run: 4 nilai × 4 model × 3 seed)
- [ ] **Bagian A3: Jumlah Lapisan** (36 run: 3 nilai × 4 model × 3 seed)
- [ ] **Bagian A4: Learning Rate** (36 run: 3 nilai × 4 model × 3 seed)
- [ ] **Bagian A5: Fitur** (48 run: 4 kelompok × 4 model × 3 seed)
- [ ] **Bagian B: Faktor Pilihan (Min 2)** (±72 run)
- [ ] **Bagian C: Kombinasi Terbaik per Model** (12 run di test & Natal)
- [ ] **Bagian D: Generalisasi ke Lokasi Kelompok** (24 run: acuan vs terbaik)

---

## 📋 Tabel Rincian Bagian A (Ablasi Wajib)

| Kode | Faktor | Nilai Variasi | Status Run | Insight Awal |
|---|---|---|:---:|---|
| **A1** | Jendela $W$ | 6, 12, 24, 48, 168 | [ ] Belum | *Apakah W=168 (1 minggu penuh) membantu menangkap periodisitas mingguan atau malah menambah parameter berlebih?* |
| **A2** | Hidden Size | 16, 32, 64, 128 | [ ] Belum | *Amati trade-off jumlah parameter vs MAE validasi pada 672 data train.* |
| **A3** | Jumlah Lapisan | 1, 2, 3 | [ ] Belum | *Periksa apakah model lebih dari 1 lapisan mengalami vanishing gradien atau konvergen lebih cepat.* |
| **A4** | Learning Rate | 0.0001, 0.001, 0.01 | [ ] Belum | *Apakah lr=0.01 memicu instabilitas (loss NaN / fluktuasi liar antar seed)?* |
| **A5** | Kelompok Fitur | `net`, `net+call+sms`, `net+waktu`, `semua` | [ ] Belum | *Apakah fitur call/sms bernilai prediktif tinggi terhadap trafik data internet?* |

---

## 📋 Tabel Rincian Bagian B (Faktor Pilihan: B4 & B6)

| Kode | Faktor Dipilih | Variasi Nilai | Status Run | Konfirmasi Hipotesis |
|---|---|---|:---:|---|
| **B4** | Fungsi Loss (Robustness) | MSE, MAE, Huber | [ ] Belum | `[Terbukti / Ditolak / Parsial]` |
| **B6** | Horizon (Multi-step Foresight) | 1, 6, 24 jam | [ ] Belum | `[Terbukti / Ditolak / Parsial]` |

---

## 🏆 Tabel Seleksi Bagian C (Konfigurasi Terbaik Berdasarkan Val MAE)

> **Catatan:** Nilai di bawah ini dipilih murni dari rata-rata 3 seed pada **Validation MAE**.

| Arsitektur | Nilai Faktor Optimal (A & B) | Val MAE (Acuan) | Val MAE (Terbaik) | Test MAE (Biasa) | Test MAE (Natal) |
|---|---|:---:|:---:|:---:|:---:|
| **ANN** | `W=..., Hidden=..., Lr=..., dll.` | `... ± ...` | `... ± ...` | `... ± ...` | `... ± ...` |
| **RNN** | `W=..., Hidden=..., Lr=..., dll.` | `... ± ...` | `... ± ...` | `... ± ...` | `... ± ...` |
| **LSTM** | `W=..., Hidden=..., Lr=..., dll.` | `... ± ...` | `... ± ...` | `... ± ...` | `... ± ...` |
| **GRU** | `W=..., Hidden=..., Lr=..., dll.` | `... ± ...` | `... ± ...` | `... ± ...` | `... ± ...` |

---

## 🌍 Tabel Bagian D (Uji Generalisasi Lokasi Kelompok)

* **Lokasi Kelompok:** **Kode 1 — Navigli (Darsena)** (Kotak `4555`)
* **Karakteristik Wilayah:** Kawasan kanal & hiburan malam (*nightlife / weekend surge*)

| Arsitektur | MAE Acuan di Duomo | MAE Acuan di Lokasi Baru | MAE Terbaik di Duomo | MAE Terbaik di Lokasi Baru | Konsistensi Pola? |
|---|:---:|:---:|:---:|:---:|:---:|
| **ANN** | `...` | `...` | `...` | `...` | Ya / Tidak |
| **RNN** | `...` | `...` | `...` | `...` | Ya / Tidak |
| **LSTM** | `...` | `...` | `...` | `...` | Ya / Tidak |
| **GRU** | `...` | `...` | `...` | `...` | Ya / Tidak |
