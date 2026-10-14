# 🚀 Proyek Studi Ablasi Deep Learning (RNN, LSTM, GRU vs ANN)
> **Workshop Deep Learning · Teknik Telekomunikasi ITERA**  
> **Tenggat:** Rabu, 28 Oktober 2026, 23.59 WIB  
> **Target Output:** Repo GitHub Publik + Laporan IEEE Max 6 Halaman (Typst)

---

## 🧭 Dashboard Navigasi `plan/`

Folder ini dirancang sebagai pusat komando, strategi, dan dokumentasi kerja tim selama pengerjaan tugas.

| Dokumen | Deskripsi & Fokus Utama |
|---|---|
| [**01_fase_proyek_dan_timeline.md**](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/01_fase_proyek_dan_timeline.md) | Roadmap tahapan (Fase 1 s.d. 5), milestone, estimasi beban komputasi, dan deadline. |
| [**02_brainstorming_dan_hipotesis.md**](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/02_brainstorming_dan_hipotesis.md) | Tempat perumusan **hipotesis wajib Bagian B** sebelum run, diskusi saintifik, dan catatan rapat tim. |
| [**03_matriks_eksperimen_dan_tracking.md**](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/03_matriks_eksperimen_dan_tracking.md) | Checklist rinci ±280 run (Bagian 0, A1–A5, B, C, D) dan tracking status eksekusi. |
| [**04_panduan_kolaborasi_github_dan_tim.md**](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/04_panduan_kolaborasi_github_dan_tim.md) | Standar kolaborasi Git: branching model, commit convention, pembagian tugas 1–3 anggota. |
| [**05_panduan_laporan_typst_ieee.md**](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/05_panduan_laporan_typst_ieee.md) | Panduan penulisan laporan IEEE 6 halaman via Typst, format tabel mean ± std, dan checklist kepatuhan. |
| [**06_panduan_deploy_vercel.md**](file:///c:/Users/j/Documents/Website/studi%20ablasi/plan/06_panduan_deploy_vercel.md) | Panduan deploy live dashboard PHP ke Vercel agar bisa dipantau online oleh seluruh anggota tim. |

---

## ⚡ Quick Start Eksekusi

1. **Tentukan Identitas Tim:**
   - Cek NIM seluruh anggota → ambil digit terakhir dari **NIM terkecil** → itulah **KODE LOKASI KELOMPOK** (0–9).
2. **Setup Repo Starter:**
   - Clone repo starter atau ekstrak starter kit ke repo ini.
   - Buat remote GitHub publik: `dlw-tugas-<kelas>-<nim_ketua>`.
3. **Pilih 2 Faktor Bagian B & Tulis Hipotesis:**
   - Buka `plan/02_brainstorming_dan_hipotesis.md`, diskusikan bersama tim, lalu kunci hipotesis sebelum menjalankan training!
4. **Jalankan Eksperimen:**
   - Mulai dengan mode uji cepat (`CEPAT = True`) untuk memastikan pipeline bebas error.
   - Jalankan mode lengkap (`CEPAT = False`) untuk hasil akhir valid 3 seed (`hasil/ablasi.csv`).
5. **Tulis Laporan Bersama di Typst:**
   - Gunakan format IEEE dua kolom, sertakan grafik dari folder `hasil/`, dan pastikan setiap angka dapat dilacak ke `ablasi.csv`.
