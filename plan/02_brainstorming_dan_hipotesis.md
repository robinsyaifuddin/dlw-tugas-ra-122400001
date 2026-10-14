# 🧠 Lembar Brainstorming, Diskusi & Perumusan Hipotesis

> **Aturan Wajib Bagian B:** Tuliskan hipotesis ilmiah **sebelum** eksperimen dijalankan, lalu verifikasi apakah data eksperimen mendukung atau menolak hipotesis tersebut di laporan akhir.

---

## 📌 1. Identitas Kelompok & Karakteristik Lokasi

* **Anggota Kelompok:**
  1. **ROBIN SYAIFUDDIN** - NIM: `122400001`
  2. **Muhammad Hamdan Al Ghifari** - NIM: `121400075`
  3. **Nathanael Hasiholan Turnip** - NIM: `122400053`
* **NIM Terkecil:** `121400075` (atau `122400001` bila dilihat berdasarkan angkatan/akhiran `01` $\rightarrow$ digit terakhir: **1**)
* **Kode Lokasi:** **`1`** (Kotak `4555`)
* **Nama Lokasi:** **Navigli (Darsena)**
* **Karakteristik Trafik Lokasi:**
  * *Tipe area:* Kawasan kanal, kuliner, rekreasi, dan hiburan malam (*nightlife district*).
  * *Ekspektasi pola:*
    - Aktivitas trafik melonjak signifikan pada **malam hari hingga dini hari** (berbeda dengan area perkantoran/kampus yang puncaknya di siang hari).
    - Lonjakan trafik tajam di **akhir pekan (Jumat malam – Minggu malam)** (*weekend nightlife surge*).
    - Trafik cenderung rendah/landai pada pagi hingga siang hari kerja (*weekday mornings*).
  * *Tantangan peramalan:*
    - Pergeseran pola diurnal (puncak bergeser ke jam 20.00–02.00).
    - Tingginya varians antara hari kerja vs akhir pekan, menuntut model mampu menangkap periodisitas mingguan ($W \ge 24$ atau bahkan $W=168$) serta fitur waktu/kalender (jam & hari).

---

## 🔬 2. Eksplorasi Faktor Bagian B & Visi AI Agentik Koloni

Dalam proyek jangka panjang, tim kami mengarah pada pembangunan **AI Agentic Swarm / Koloni Staf Perusahaan**. Sebuah koloni agen yang cerdas dan handal membutuhkan:
1. **Ketahanan terhadap Noise & Anomali:** Tidak panik atau halusinasi saat menghadapi lonjakan data bisnis tak terduga (*robustness*).
2. **Perencanaan Multi-Langkah (*Foresight*):** Mampu melihat masa depan bukan hanya 1 langkah pendek ke depan (*short-sighted*), melainkan perencanaan jangka menengah dan panjang untuk alokasi staf dan sumber daya.
3. **Generalisasi Tanpa Overfitting:** Mampu beradaptasi di lingkungan baru (transfer dari Duomo ke Navigli) tanpa menghafal data mentah secara buta.

---

### 📖 Penjelasan Seluruh Kandidat Faktor Bagian B:

| Kode | Faktor | Nilai Variasi | Penjelasan Teknis & Relevansi Dunia AI Agentik |
|---|---|---|---|
| **B1** | **Batch Size** | 16, 64, 256 | **Dinamika Gradien & Noise Stokastik:** Batch kecil (16) menambahkan *gradient noise* yang membantu model keluar dari saddle points (seperti eksplorasi agen). Batch besar (256) lebih cepat diproses hardware tetapi rentan terjebak di *sharp minima* dengan generalisasi lebih buruk pada dataset 672 sampel. |
| **B2** | **Dropout** | 0, 0.2, 0.5 | **Regularisasi & Anti-Ketergantungan (Ensemble Effect):** Memutus koneksi neuron secara acak saat pelatihan. Mencegah neuron/sub-agen bergantung pada satu jalur informasi saja. Cocok untuk menguji apakah kapasitas model rekuren terlalu besar (*overparameterized*) pada data kecil. |
| **B3** | **Optimizer** | Adam, AdamW, SGD (mom 0.9) | **Mekanisme Belajar:** Adam menggunakan momen adaptif orde 1 & 2; AdamW memperbaiki *weight decay* terpisah untuk regularisasi bobot lebih sehat; SGD dengan momentum menyimulasikan pembelajaran deterministik dengan inersia tinggi yang membutuhkan tuning cermat. |
| **B4** | **Fungsi Loss** | MSE, MAE, Huber | **Kekebalan terhadap Anomali/Outlier:** MSE menghukum galat besar secara kuadratik (membuat model panik saat ada lonjakan malam di Navigli); MAE menghukum galat secara linier tetapi gradien konstan di nol; **Huber Loss (Smooth L1)** memadukan keunggulan keduanya: kuadratik untuk galat kecil, linier untuk anomali besar. |
| **B5** | **Early Stopping** | 10, 30, 60 | **Kematangan Pembelajaran & Waktu:** Menguji seberapa sabar sistem menunggu perbaikan loss validasi sebelum menyerah. Patience 10 rawan berhenti prematur (*underfitting*), sedangkan patience 60 memberi ruang bagi model rekuren lambat untuk konvergen. |
| **B6** | **Horizon** | 1, 6, 24 jam | **Kapasitas Perencanaan Masa Depan (*Foresight*):** Menguji apakah representasi internal sel memori (LSTM/GRU) mampu memproyeksikan kondisi 6 jam (taktis operasional) dan 24 jam (strategis harian) tanpa mengalami keruntuhan akumulasi galat (*compounding error*). |
| **B7** | **Gradient Clipping** | None, 0.5, 1.0 (lr 0.01) | **Stabilitas Pembelajaran Ekstrem:** Membatasi norma gradien agar tidak meledak (*exploding gradient*) saat dilatih dengan learning rate agresif (0.01), sangat krusial bagi arsitektur RNN vanilla. |

---

## 🎯 Keputusan Pilihan Tim: **B4 (Fungsi Loss)** & **B6 (Forecasting Horizon)**

### 💡 Pilihan Faktor 1: **B4 — Fungsi Loss (MSE, MAE, Huber)**
* **Alasan Pemilihan:**
  * Area Navigli memiliki karakteristik *nightlife* dengan lonjakan trafik tak terduga (*spikes/outliers*). Fungsi loss menentukan kepribadian agen: apakah terlalu reaktif terhadap noise (MSE) atau mampu menjaga keseimbangan stabilitas dan presisi (Huber).
* **Nilai yang Diuji:** `MSE`, `MAE`, `Huber`
* **Hipotesis Awal (Before Run):**
  > *"Fungsi **Huber Loss** akan menghasilkan Validation MAE rata-rata terendah dan generalisasi terbaik pada keempat model dibandingkan MSE dan MAE murni. Hal ini dikarenakan Huber Loss memadukan keunggulan sifat kuadratik MSE pada galat kecil (gradien halus mendekati titik konvergensi optimum) dan sifat linier MAE pada galat besar, sehingga efektif menahan pengaruh distorsi dari lonjakan anomali trafik malam hari tanpa menyebabkan ledakan gradien."*
* **Dasar Teori / Literatur:**
  * Huber loss membatasi gradien $\frac{\partial L}{\partial e} = \pm \delta$ untuk galat $|e| > \delta$, bertindak sebagai *M-estimator* yang tangguh (*robust regression*) terhadap data runtun waktu yang memiliki *heavy-tailed distribution*.
* **Hasil Eksperimen & Refleksi (After Run):**
  * *Apakah hipotesis terbukti?* `[Isi setelah run]`
  * *Temuan unik:* `[...]`

---

### 💡 Pilihan Faktor 2: **B6 — Forecasting Horizon (1 jam, 6 jam, 24 jam)**
* **Alasan Pemilihan:**
  * Selaras langsung dengan kapabilitas *forecasting & planning* agen perusahaan: peramalan 1 jam (taktis mikro), 6 jam (taktis pergantian shift), dan 24 jam (siklus harian penuh). Menguji sejauh mana *temporal memory* LSTM dan GRU benar-benar unggul dari baseline naif dan ANN saat horizon diperpanjang.
* **Nilai yang Diuji:** `1, 6, 24` jam
* **Hipotesis Awal (Before Run):**
  > *"Seiring bertambahnya horizon peramalan ($H=1 \rightarrow 6 \rightarrow 24$), nilai MAE seluruh model akan meningkat akibat akumulasi ketidakpastian. Namun, arsitektur rekuren dengan mekanisme gating (**LSTM dan GRU**) diprediksi mengalami laju penurunan performa (degradasi galat) yang jauh lebih lambat dibandingkan ANN dan RNN standar, karena mampu mempertahankan representasi pola laten siklus harian. Pada horizon $H=24$, model LSTM dan GRU diprediksi tetap mampu mengalahkan baseline naif 24 jam secara signifikan."*
* **Dasar Teori / Literatur:**
  * Hochreiter & Schmidhuber (1997) dan Cho et al. (2014) membuktikan bahwa *forget/update gates* mempertahankan sinyal galat melalui waktu yang panjang tanpa mengalami *exponential vanishing*, sehingga representasi state masa lalu tetap informatif untuk peramalan multi-langkah.
* **Hasil Eksperimen & Refleksi (After Run):**
  * *Apakah hipotesis terbukti?* `[Isi setelah run]`
  * *Temuan unik:* `[...]`

---

## 💬 3. Catatan Diskusi & Temuan Mengejutkan (Brainstorming Log)

Gunakan ruang ini untuk mencatat anomali atau fenomena tak terduga selama eksperimen:
- *Contoh: Kenapa RNN sederhana bisa mengalahkan LSTM pada window pendek? (Teori: LSTM over-parameterized untuk dependensi jangka pendek).*
- *Kenapa performa di minggu Natal (23–29 Des) anjlok untuk semua model? (Teori: Distribusi trafik liburan menyimpang drastis dari pola hari kerja data training).*
- *Apakah penambahan lapisan (A3: 2 atau 3 lapisan) menyebabkan vanishing gradient pada RNN tanpa gating?*
