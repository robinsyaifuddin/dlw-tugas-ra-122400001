# 🌐 Panduan Deploy Live Dashboard ke Vercel (Online & Publik)

Dokumen ini menjelaskan **arsitektur dan mekanisme** agar dashboard progres studi ablasi Anda dapat diakses secara publik oleh seluruh anggota kelompok (Robin, Hamdan, Nathanael) dan dosen melalui internet via **Vercel**.

---

## ⚙️ 1. Mekanisme & Cara Kerjanya

```
[Laptop Lokal Anda]                         [GitHub Repo Publik]                      [Vercel Cloud]
  • Edit kode / run eksperimen                • dlw-tugas-ra-122400001                  • https://dlw-tugas.vercel.app
  • `hasil/ablasi.csv` bertambah   ──► git push ──►  Auto-Trigger Webhook  ──►  Auto Re-Deploy (10-15 dtk)
  • `dashboard_state.json` terupdate                                                    • Tampil live di HP & Laptop Tim
```

1. **Vercel PHP Serverless Runtime:** File [`vercel.json`](file:///c:/Users/j/Documents/Website/studi%20ablasi/vercel.json) yang telah disiapkan di root folder menggunakan runtime `vercel-php@0.7.3`. Ini memungkinkan Vercel menjalankan file PHP tanpa memerlukan server VPS / hosting terpisah.
2. **Otomatisasi CI/CD:** Setiap kali ada commit baru yang di-push ke GitHub (`git push origin main`), Vercel akan otomatis mendeteksi perubahan, mengompilasi aplikasi dalam hitungan detik, dan memperbarui URL publik secara langsung.
3. **Statistik Run `ablasi.csv`:** Begitu notebook Anda selesai melakukan training dan menghasilkan baris baru di `hasil/ablasi.csv`, Anda cukup melakukan commit & push `ablasi.csv`. Dashboard Vercel akan otomatis menampilkan jumlah run terbaru (misal: `48 / 280 run tercatat`)!

---

## 🚀 2. Langkah-Langkah Deploy ke Vercel (Gratis & Cepat)

### Langkah 1: Pastikan Repositori Sudah di GitHub
1. Pastikan folder ini sudah menjadi git repo dan di-push ke GitHub:
   ```bash
   git init
   git add .
   git commit -m "feat: setup ablation study project and vercel dashboard"
   git branch -M main
   git remote add origin https://github.com/USERNAME-ANDA/dlw-tugas-ra-122400001.git
   git push -u origin main
   ```

### Langkah 2: Buka Akun Vercel
1. Buka [https://vercel.com](https://vercel.com) dan login menggunakan akun **GitHub** Anda.

### Langkah 3: Import Project
1. Di dashboard Vercel, klik tombol **"Add New..."** $\rightarrow$ pilih **"Project"**.
2. Cari repositori Anda (`dlw-tugas-ra-122400001`), lalu klik tombol **"Import"**.
3. Pada halaman konfigurasi:
   - **Framework Preset:** Biarkan `Other` (karena konfigurasi sudah ditangani oleh `vercel.json`).
   - **Root Directory:** `./` (default).
4. Klik tombol **"Deploy"**.

### Langkah 4: Selesai! Dashboard Anda Online 🎉
Dalam 15–30 detik, Vercel akan memberikan domain publik gratis, contohnya:
👉 `https://dlw-tugas-ra-122400001.vercel.app`

Bagikan link tersebut ke grup WhatsApp kelompok Anda! Seluruh anggota tim dapat memantau progres checklist, melihat identitas kelompok, dan mengawasi jumlah run eksperimen langsung dari browser mereka (laptop maupun smartphone).

---

## 🔒 3. Catatan Penyimpanan Data (Serverless vs GitHub)

* Di lingkungan cloud serverless Vercel, sistem penyimpanan bersifat *stateless* (read-only). 
* Oleh karena itu, jika Anda mencentang checklist di localhost atau meng-update file `dashboard_state.json`, perubahan tersebut akan menjadi permanen dan sinkron secara global ke seluruh anggota tim saat Anda melakukan **`git commit` & `git push`**.
* File `index.php` sudah dilengkapi pengaman `@file_put_contents` dan fallback sehingga tidak akan crash ketika diakses di cloud Vercel.
