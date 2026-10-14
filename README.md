# Studi Ablasi RNN, LSTM, GRU vs ANN untuk Prediksi Trafik Seluler Milan

Tugas Workshop Deep Learning — Kecerdasan Buatan RA · ITERA.

## Kelompok

| Peran | Nama | NIM |
|---|---|---|
| Ketua | Robin Syaifuddin | 122400001 |
| Anggota | Muhammad Hamdan Al Ghifari | 121400075 |
| Anggota | Nathanael Hasiholan Turnip | 122400053 |

- **Kelas:** RA
- **Lokasi Kelompok:** Kode 1 — Navigli (Darsena) (Kotak 4555) — digit terakhir NIM terkecil (Robin: `122400001` -> `1`)
- **Laporan:** `laporan-typst/main.pdf` (Format IEEE 2-Kolom)
- **Live Progress Dashboard:** Dapat diakses online di Vercel atau lokal melalui `php -S localhost:8000` / `jalankan_dashboard.bat`.

## Cara Menjalankan

```bash
uv sync
uv run jupyter lab
```

Buka `tugas_ablasi.ipynb`, pastikan variabel `LOKASI_KELOMPOK = 1`, lalu jalankan sel eksperimen. Semua run otomatis tercatat ke `hasil/ablasi.csv`.

## Struktur Repositori

```
dlw-tugas-ra-122400001/
├── tugas_ablasi.ipynb          # Notebook eksperimen (di-commit beserta seluruh output sel)
├── data/                       # Dataset trafik per jam (Duomo + 10 lokasi tugas)
├── hasil/                      # Database ablasi.csv, tabel komparasi, dan visualisasi grafik
├── laporan-typst/              # Naskah Typst IEEE conference (main.typ + PDF)
├── plan/                       # Master planning, hipotesis B4 & B6, matriks eksperimen, panduan Git/Typst/Vercel
├── index.php                   # Live Mission Control & Status Tracker Dashboard
├── vercel.json                 # Konfigurasi deployment serverless Vercel
├── pyproject.toml              # Konfigurasi dependensi uv
└── README.md                   # Dokumentasi resmi repositori
```

## Data

Telecom Italia Big Data Challenge (Barlacchi dkk., 2015, *Scientific Data*, lisensi ODbL), versi per jam dari Zhang dkk. (INFOCOM 2021). Disiapkan oleh pengajar di repo [mctosima/dl-workshop](https://github.com/mctosima/dl-workshop).
