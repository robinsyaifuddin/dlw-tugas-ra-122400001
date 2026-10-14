# 👥 Panduan Kolaborasi Tim & GitHub Workflow

Panduan ini mengatur mekanisme kerja bersama 1–3 orang anggota tim agar tidak terjadi konflik git (*merge conflict*) dan pembagian peran berjalan efisien.

---

## 🏗️ 1. Struktur Repositori & File Penting

Pastikan struktur root repositori GitHub Anda mengikuti format standar:
```
dlw-tugas-<kelas>-<nim_ketua>/
├── .gitignore
├── README.md               <-- Profil anggota, kelas, lokasi, cara menjalankan & rangkuman
├── pyproject.toml          <-- Dependensi uv / environment
├── tugas_ablasi.ipynb      <-- Notebook utama (Wajib di-commit BESERTA output sel-nya!)
├── data/
│   ├── milano_duomo_5060.csv
│   └── milano_tugas_10_lokasi.csv
├── hasil/
│   ├── ablasi.csv          <-- Database hasil seluruh run eksperimen
│   └── *.png / *.pdf       <-- Grafik kurva loss, visualisasi prediksi, matriks perbandingan
├── laporan-typst/          <-- Naskah Typst laporan IEEE dua kolom
│   ├── main.typ
│   ├── metadata.typ
│   └── gambar/
└── plan/                   <-- Folder planning, hipotesis & tracking kerja tim
```

---

## 🌿 2. Git Branching & Workflow

Untuk mencegah kerusakan pada branch utama (`main`):
1. **Branch `main`:** Selalu stabil, berisi kode final yang dapat dijalankan dan naskah laporan yang siap dinilai.
2. **Branch Fitur / Eksperimen:**
   - `exp/bagian-a`: Pengerjaan ablasi faktor A1–A5.
   - `exp/bagian-b`: Pengerjaan ablasi faktor B dan uji hipotesis.
   - `doc/typst-report`: Penulisan dan editing naskah Typst.

### ⚠️ Tips Mengelola Notebook Jupyter di Git:
- Notebook `.ipynb` rentan merge conflict jika dua orang mengedit sel bersamaan.
- **Rekomendasi:** Tentukan **1 PIC Runner Notebook** yang mengeksekusi training dan menghasilkan `hasil/ablasi.csv`. Anggota lain dapat fokus pada visualisasi, analisis statistik, dan penulisan teks laporan di Typst.
- Selalu `git pull origin main` sebelum mulai mengedit file lokal.

---

## 🤝 3. Pembagian Peran Tim (Contoh untuk 3 Anggota)

| Anggota | Peran Utama | Tanggung Jawab Spesifik |
|---|---|---|
| **Anggota 1 (Ketua / ML Runner)** | *Lead ML & Experiment Runner* | Menjalankan notebook `tugas_ablasi.ipynb`, mengelola komputasi GPU/Colab, memastikan `hasil/ablasi.csv` terisi lengkap 3 seed, dan memastikan notebook di-commit dengan output. |
| **Anggota 2** | *Data Analyst & Graph Generator* | Mengolah data dari `ablasi.csv` menjadi tabel statistik ($mean \pm std$), memvalidasi hipotesis Bagian B, dan mengekstrak grafik ke folder gambar Typst. |
| **Anggota 3** | *Lead Technical Writer (Typst)* | Mengelola proyek Typst IEEE, menyusun bab Pendahuluan, Metodologi, Analisis Pembahasan, menyusun referensi sitasi, dan memastikan batas 6 halaman dipatuhi. |

*(Jika tim terdiri dari 1 atau 2 orang, peran di atas digabungkan secara proporsional).*

---

## 📝 4. Standard Commit Messages

Gunakan format commit yang jelas:
- `feat(exp): complete A1 window size ablation with 3 seeds`
- `feat(exp): run B2 dropout experiment and update ablasi.csv`
- `docs(typst): add methodology section and IEEE table for Part A`
- `refactor(notebook): update group location code to location 4`
- `docs(readme): fill team member NIMs and reproduction steps`
