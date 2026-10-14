# 📑 Panduan Penulisan Laporan IEEE Menggunakan Typst

Laporan wajib disusun menggunakan **Typst** (atau LaTeX) dengan format **IEEE Conference dua kolom**, maksimal **6 halaman** (termasuk referensi), berbahasa Indonesia. Laporan dari Microsoft Word **tidak diterima**.

---

## 🚀 1. Cara Kolaborasi Naskah Typst

Ada dua cara mudah berkolaborasi dengan Typst:
1. **Opsi A: Cloud Typst (Direkomendasikan untuk kolaborasi real-time):**
   - Buka [typst.app](https://typst.app), buat project baru.
   - Upload seluruh isi folder `starter/laporan-typst/`.
   - Undang anggota kelompok via email untuk mengedit bersama secara real-time seperti Google Docs.
   - Setelah selesai, export file PDF dan download source `.typ`-nya untuk di-commit ke repositori GitHub.
2. **Opsi B: Lokal via VS Code / Typst CLI:**
   - Install ekstensi `Tinymist Typst` di VS Code.
   - Compile otomatis dengan perintah: `typst watch main.typ output.pdf`.
   - Kolaborasi melalui git branch & pull request.

---

## 🏛️ 2. Struktur Wajib Naskah Laporan (Maks. 6 Halaman)

| Bagian | Target Halaman | Konten Kunci & Tips Penulisan |
|---|:---:|---|
| **Judul & Abstrak** | 0.5 Kolom | Rangkuman padat: latar belakang studi ablasi, model yang diuji (ANN, RNN, LSTM, GRU), faktor kunci, dan temuan utama. |
| **I. Pendahuluan** | 0.5–1 Hal | Pentingnya prediksi trafik seluler/internet, trade-off arsitektur rekuren vs feed-forward, serta tujuan eksplorasi ablasi satu-faktor-per-waktu. |
| **II. Data dan Metodologi** | 1 Hal | Deskripsi dataset Telecom Italia Milano (Duomo + Lokasi Kelompok), window sliding $W$, pembagian train/val/test/Natal, dan baseline naif. |
| **III. Hasil Eksperimen** | 2–2.5 Hal | **Bagian 0, A1–A5, B, C, D.** Setiap tabel/gambar wajib menyajikan $mean \pm std$ dari 3 seed. Wajib merujuk dan membahas setiap gambar/tabel. |
| **IV. Pembahasan** | 1 Hal | Analisis mendalam: *mengapa* faktor tertentu berpengaruh (misal: vanishing gradient, overfitting pada sampel terbatas 672, atau kapasitas gating). |
| **V. Kesimpulan** | 0.5 Hal | Jawaban atas pertanyaan utama: model mana yang paling robust, efisien komputasi, dan generalisasinya. |
| **Kontribusi Anggota** | 0.2 Kolom | Rincian porsi kerja masing-masing anggota secara transparan. |
| **Pernyataan AI** | 0.2 Kolom | Transparansi penggunaan alat AI (prompting, perbaikan kode/bahasa). |
| **Referensi** | 0.5 Hal | Format IEEE standar (minimal Barlacchi 2015, Zhang 2021, Hochreiter 1997, Cho 2014, dll.). |

---

## 💡 3. Tips Format Typst untuk Kepatuhan Penilaian

### A. Format Angka Statistik (Mean ± Std)
Jangan hanya menulis satu angka mutlak. Wajib menyertakan deviasi 3 seed:
```typst
#table(
  columns: (auto, auto, auto, auto),
  align: center + horizon,
  [*Model*], [*A1: W=6*], [*A1: W=24 (Acuan)*], [*A1: W=168*],
  [ANN],  [$0.185 ± 0.004$], [$0.172 ± 0.003$], [$0.198 ± 0.006$],
  [RNN],  [$0.160 ± 0.002$], [$0.155 ± 0.003$], [$0.168 ± 0.005$],
  [LSTM], [$0.165 ± 0.005$], [$0.158 ± 0.004$], [$0.162 ± 0.003$],
  [GRU],  [$0.159 ± 0.003$], [$0.153 ± 0.002$], [$0.160 ± 0.004$],
)
```

### B. Penyisipan Gambar Hasil
Pastikan grafik diekspor dari matplotlib dengan DPI tinggi ($\ge 300$):
```typst
#figure(
  image("gambar/ablasi_w.png", width: 90%),
  caption: [Pengaruh panjang jendela $W$ terhadap Val MAE pada keempat arsitektur model (rata-rata 3 seed).],
) <fig:ablasi_w>
```
Lalu rujuk di paragraf dengan `@fig:ablasi_w`.

### C. Pembahasan Berbasis Sains, Bukan Sekadar Membaca Tabel
* ❌ *Salah:* "Tabel 1 menunjukkan ANN mendapat nilai 0.172, RNN mendapat 0.155, dan LSTM 0.158."
* ✅ *Benar:* "RNN dan GRU secara konsisten mengungguli ANN dengan selisih MAE sebesar $0.017$, mengonfirmasi bahwa mekanisme rekurensi mampu menangkap dinamika dependensi temporal pendek lebih baik dibandingkan pembobotan statis ANN. Pada jendela panjang ($W=168$), LSTM menunjukkan penurunan performa yang lebih lambat dibanding RNN berkat *gating mechanism* yang memitigasi *vanishing gradient*."
