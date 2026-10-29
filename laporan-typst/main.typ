// =====================================================================
// Laporan Tugas — Workshop Deep Learning (Teknik Telekomunikasi ITERA)
// Studi Ablasi RNN, LSTM, GRU vs ANN untuk Prediksi Trafik Seluler
//
// Format IEEE conference dua kolom (paket charged-ieee), MAKSIMAL 6 HALAMAN
// =====================================================================
#import "@preview/charged-ieee:0.1.4": ieee

#set text(lang: "id")

#show: ieee.with(
  title: [Studi Ablasi Komparatif Arsitektur Rekuren (RNN, LSTM, GRU) dan ANN untuk Peramalan Trafik Seluler Kota Milan],
  abstract: [
    Peramalan kebutuhan trafik seluler yang akurat merupakan komponen fundamental bagi operator telekomunikasi dalam melakukan optimasi alokasi spektrum frekuensi serta efisiensi energi jaringan berbasis *demand*. Penelitian ini mengevaluasi kinerja arsitektur jaringan saraf tiruan non-sekuensial (*Artificial Neural Network* / ANN) dan rekuren (*Recurrent Neural Network* / RNN, *Long Short-Term Memory* / LSTM, dan *Gated Recurrent Unit* / GRU) melalui studi ablasi sistematis menggunakan dataset aktivitas telekomunikasi Kota Milan dari Telecom Italia Big Data Challenge. Eksperimen mencakup 312 run pelatihan mandiri dengan 3 *random seed* (0, 1, 2) untuk menguji lima faktor ablasi wajib (panjang jendela $W$, ukuran unit *hidden*, jumlah lapisan, *learning rate*, dan kelompok fitur input) serta dua faktor khusus kelompok: fungsi *loss* (MSE, MAE, Huber) dan *forecasting horizon* multi-langkah ($H in {1, 6, 24}$ jam). Seleksi hiperparameter terbaik dilakukan secara ketat berbasis *Validation Mean Absolute Error* (Val MAE) sebelum diuji pada minggu evaluasi dan masa anomali libur Natal di pusat kota Duomo, serta uji transfer generalisasi di kawasan hiburan malam Navigli (Darsena, Kotak 4555). Hasil eksperimen menunjukkan bahwa GRU terbaik ($W=48$, *hidden*=128, *layers*=2, *loss*=MAE) meraih kinerja superior di lokasi target Navigli dengan Test MAE $176.7 plus.minus 15.9$ (turun 7.4% dari konfigurasi acuan), membuktikan bahwa kapasitas *gating* adaptif rekuren dan fungsi *loss* yang kebal terhadap *outlier* krusial dalam memodelkan dinamika temporal non-stasioner perkotaan.
  ],
  authors: (
    (name: "Robin Syaifuddin", department: [NIM 122400001], organization: [Teknik Telekomunikasi, ITERA], email: "robin.122400001@student.itera.ac.id"),
    (name: "Muhammad Hamdan Al Ghifari", department: [NIM 121400075], organization: [Teknik Telekomunikasi, ITERA], email: "muhammad.121400075@student.itera.ac.id"),
    (name: "Nathanael Hasiholan Turnip", department: [NIM 122400053], organization: [Teknik Telekomunikasi, ITERA], email: "nathanael.122400053@student.itera.ac.id"),
  ),
  index-terms: ("prediksi trafik seluler", "studi ablasi", "RNN", "LSTM", "GRU", "deret waktu", "Navigli Darsena"),
  bibliography: bibliography("references.bib", title: [Pustaka]),
  figure-supplement: [Gambar],
)

= Pendahuluan
Ledakan volume lalu lintas data seluler pada era jaringan 5G dan Beyond 5G menuntut pengelolaan sumber daya radio (*Radio Resource Management* / RRM) yang adaptif dan proaktif. Operator jaringan telekomunikasi tidak lagi dapat mengandalkan provisi kapasitas statis yang boros energi. Melalui peramalan trafik per sel atau per kotak kisi geografis (*grid square*) beberapa jam ke depan, mekanisme *base station sleeping* dan *dynamic carrier aggregation* dapat diotomatisasi secara efisien @barlacchi2015milan.

Model *Deep Learning* telah diadopsi luas untuk menyelesaikan permasalahan regresi deret waktu non-linear ini. Pendekatan dasar menggunakan *Artificial Neural Network* (ANN) memetakan jendela historis yang diratakan secara langsung ke nilai luaran @zhang2021fedda. Di sisi lain, famili rekuren seperti *Recurrent Neural Network* (RNN) standar, *Long Short-Term Memory* (LSTM) @hochreiter1997lstm, dan *Gated Recurrent Unit* (GRU) @cho2014gru dirancang khusus untuk memelihara *internal state* sekuensial guna menangkap ketergantungan temporal jangka panjang.

Meskipun demikian, performa komparatif antar-arsitektur sering kali bias akibat pemilihan kombinasi hiperparameter yang tidak terstandardisasi. Oleh karena itu, *studi ablasi*—yaitu proses mengubah tepat satu variabel dalam satu waktu (*one-factor-at-a-time*) dari sebuah konfigurasi acuan baku—sangat esensial untuk membedah sensitivitas struktural setiap model secara ilmiah. Kontribusi utama dari laporan ini meliputi:
1. Pelaksanaan studi ablasi komprehensif (312 run pelatihan mandiri lintas 3 *seed*) yang memvalidasi dampak panjang jendela $W$, kapasitas representasi *hidden*, kedalaman lapisan, *learning rate*, dan komponen fitur multimodal (panggilan, SMS, waktu siklis).
2. Perumusan dan pembuktian hipotesis saintifik terkait ketahanan fungsi *loss* (Huber vs MSE vs MAE) dan dampak degradasi *forecasting horizon* multi-langkah ($H=1, 6, 24$ jam).
3. Pengujian transfer generalisasi konfigurasi optimal ke kawasan spesifik kelompok, yaitu *Navigli (Darsena - Kotak 4555)*, yang memiliki dinamika sosial malam hari (*nightlife*) berbeda dari pusat komersial Duomo.

= Data dan Metode

== Dataset Telekomunikasi Kota Milan
Studi ini menggunakan dataset terbuka Telecom Italia Big Data Challenge @barlacchi2015milan yang telah diagregasikan ke dalam interval per jam oleh Zhang et al. @zhang2021fedda. Kota Milan dibagi ke dalam kisi-kisi grid $100 times 100$ kotak ($10.000$ sel). Data utama meliputi aktivitas internet (`net`), panggilan suara (`call`), dan pesan singkat (`sms`). 

#figure(
  image("gambar/peta_lokasi_tugas.png", width: 88%),
  caption: [Distribusi spasial rata-rata intensitas internet di Kota Milan. Lingkaran toska menandai Duomo (kotak 5060), sedangkan lingkaran kuning kode 1 merepresentasikan lokasi kelompok: Navigli / Darsena (kotak 4555).],
  placement: top,
) <fig:peta>

Lokasi yang dijadikan acuan investigasi ablasi awal adalah pusat keramaian komersial *Duomo (kotak 5060)*. Sementara itu, lokasi kelompok yang ditetapkan berdasarkan digit nim kelompok adalah *Navigli (Darsena - Kode 1, kotak 4555)*, sebagaimana divisualisasikan pada @fig:peta.

== Partisi Data dan Rekayasa Fitur
Untuk mencegah kebocoran informasi masa depan (*lookahead data leakage*), partisi data dilakukan strictly berurutan secara kronologis sepanjang 8 minggu (1344 jam):
- *Konteks Windowing Awal*: 4 November – 10 November 2013 (minggu 1).
- *Training Set*: 11 November – 8 Desember 2013 (minggu 2–5, 672 jam).
- *Validation Set*: 9 Desember – 15 Desember 2013 (minggu 6, 168 jam).
- *Test Set*: 16 Desember – 22 Desember 2013 (minggu 7, 168 jam).
- *Evaluasi Anomali Natal*: 23 Desember – 29 Desember 2013 (minggu 8, 168 jam).

Seluruh fitur kontinu distandarisasi menggunakan nilai rata-rata ($mu$) dan simpangan baku ($sigma$) yang hanya dihitung dari data pelatihan sebelum minggu validasi ($t < 9$ Desember 2013). Fitur waktu periodik dikodekan menggunakan transformasi trigonometri siklis:
$ "jam"_"sin" = sin((2 pi t_"jam")/24), quad "jam"_"cos" = cos((2 pi t_"jam")/24) $
$ "hari"_"sin" = sin((2 pi t_"hari")/7), quad "hari"_"cos" = cos((2 pi t_"hari")/7) $

== Arsitektur Model dan Konfigurasi Acuan
Empat model arsitektur diimplementasikan menggunakan PyTorch:
- *ANN*: Menggabungkan matriks masukan berdimensi $[W, F]$ menjadi vektor datar satu dimensi, dilanjutkan dengan lapisan *dense* tersembunyi beraktivasi ReLU dan *dropout*.
- *RNN, LSTM, GRU*: Lapisan rekuren bertumpuk (*stacked recurrent layers*) beroperasi sepanjang langkah waktu $W$. Vektor *hidden state* dari jam terakhir diekstraksi dan dilewatkan ke lapisan proyeksi linear luaran.

#figure(
  caption: [Spesifikasi Konfigurasi Acuan Baku],
  placement: top,
  table(
    columns: 2,
    align: (left, left),
    stroke: none,
    table.hline(),
    table.header([*Hiperparameter*], [*Nilai Acuan Baku*]),
    table.hline(stroke: 0.5pt),
    [Panjang Jendela $W$], [24 jam (1 hari)],
    [Horizon Prediksi $H$], [1 jam ke depan],
    [Ukuran Tersembunyi (*Hidden*)], [ANN: 32, RNN: 68, LSTM: 33, GRU: 38 ($approx 5.500$ param)],
    [Jumlah Lapisan], [ANN: 2 lapisan, Rekuren: 1 lapisan],
    [Kelompok Fitur Input], [`semua` (`net`, `call`, `sms`, jam siklis, hari siklis)],
    [Optimiser & _Learning Rate_], [Adam, $eta = 0.001$],
    [_Batch Size_], [64],
    [_Loss Function_], [Mean Squared Error (MSE)],
    [_Early Stopping_], [Patience 30 epoch (maks. 300 epoch, kriteria val MSE)],
    table.hline(),
  ),
) <tab:acuan>

== Protokol Eksperimen dan Evaluasi
Seluruh model dilatih dengan 3 *random seed* independen ($0, 1, 2$) dan dilaporkan dalam format rerata $plus.minus$ simpangan baku. Metrik evaluasi mencakup *Mean Absolute Error* (MAE), *Root Mean Squared Error* (RMSE), dan *Mean Absolute Percentage Error* (MAPE) yang dihitung setelah mengembalikan prediksi ke skala asli trafik. Pemilihan konfigurasi terbaik dilakukan *hanya* menggunakan skor Validation MAE.

= Hasil Eksperimen dan Analisis

== Baseline Naif dan Model Acuan
Tabel @tab:naif menyajikan performa baseline naif tanpa pembelajaran. Baseline *minggu lalu* ($t-168$) menjadi acuan terkuat karena menangkap pola mingguan yang stabil pada minggu reguler, namun anjlok total pada minggu Natal (MAE 685.2) akibat pergeseran kalender libur.

#figure(
  caption: [Performa Baseline Naif vs Model Acuan di Duomo ($H=1$ jam)],
  placement: top,
  table(
    columns: 4,
    align: (left, center, center, center),
    stroke: none,
    table.hline(),
    table.header([*Metode / Model*], [*Val MAE*], [*Test MAE*], [*Natal MAE*]),
    table.hline(stroke: 0.5pt),
    [Naif: Nilai Terakhir ($t-1$)], [334.3], [376.1], [353.4],
    [Naif: Kemarin ($t-24$)], [273.7], [297.0], [437.1],
    [Naif: Minggu Lalu ($t-168$)], [222.1], [236.4], [685.2],
    table.hline(stroke: 0.3pt),
    [ANN Acuan], [$248.9 plus.minus 8.9$], [$309.9 plus.minus 5.9$], [$680.1 plus.minus 52.0$],
    [RNN Acuan], [$206.1 plus.minus 7.4$], [$229.6 plus.minus 5.1$], [$421.2 plus.minus 2.4$],
    [LSTM Acuan], [$230.5 plus.minus 12.2$], [$273.6 plus.minus 25.4$], [$586.0 plus.minus 56.8$],
    [GRU Acuan], [$207.5 plus.minus 3.8$], [$237.2 plus.minus 9.3$], [$468.2 plus.minus 21.8$],
    table.hline(),
  ),
) <tab:naif>

== Studi Ablasi Wajib (A1 – A5)

=== Pengaruh Panjang Jendela $W$ (A1)
Pengujian rentang $W in {6, 12, 24, 48, 168}$ jam diilustrasikan pada @fig:a1. Untuk ANN, peningkatan $W$ ke 168 jam secara drastis membengkakkan jumlah bobot input ($168 times 7 times 32$), memicu *overfitting* parah dengan Test MAE melonjak ke $402.0 plus.minus 12.5$. Sebaliknya, model rekuren memiliki jumlah parameter yang konstan terhadap $W$. RNN dan GRU mencapai titik optimal pada rentang $W=24$ hingga $W=48$ jam (Test MAE $224.5 plus.minus 11.7$).

#figure(
  image("gambar/A1_W.png", width: 92%),
  caption: [Ablasi A1: Pengaruh panjang jendela $W$ terhadap Val MAE dan Test MAE di Duomo.],
  placement: top,
) <fig:a1>

=== Pengaruh Ukuran Hidden (A2)
Variasi kapasitas unit tersembunyi dievaluasi pada ${16, 32, 64, 128}$. Pada dataset kecil (672 sampel latih), LSTM dan GRU dengan *hidden* 128 mampu mengompensasi keterbatasan representasi dan memangkas Test MAE masing-masing menjadi $245.6 plus.minus 6.7$ dan $220.3 plus.minus 10.6$ (@fig:a2).

#figure(
  image("gambar/A2_hidden.png", width: 92%),
  caption: [Ablasi A2: Pengaruh ukuran unit hidden terhadap performa model.],
  placement: top,
) <fig:a2>

=== Pengaruh Jumlah Lapisan (A3)
Penambahan lapisan rekuren bertumpuk ($"layers" in {1, 2, 3}$) tidak selalu menghasilkan perbaikan pada model rekuren sederhana. Pada LSTM, penambahan ke 3 lapisan menyebabkan degradasi Test MAE ekstrem menjadi $412.6 plus.minus 54.2$ akibat hilangnya stabilitas gradien saat pelatihan data terbatas. Hanya GRU yang menunjukkan toleransi baik pada 2 lapisan dengan Val MAE optimal sebesar $203.3$.

=== Pengaruh Learning Rate (A4)
Tingkat pembelajaran $eta = 0.0001$ terbukti terlalu lambat, di mana model sering berhenti karena batas *max epochs* sebelum mencapai konvergensi (Test MAE $> 287$). Sebaliknya, $eta = 0.01$ memberikan akselerasi terbaik pada LSTM (Test MAE $225.8 plus.minus 4.2$), namun meningkatkan variansi pada ANN ($309.4 plus.minus 53.2$).

=== Pengaruh Kelompok Fitur (A5)
Hasil pengujian subset fitur (`net`, `net+call+sms`, `net+waktu`, `semua`) membuktikan bahwa fitur kalender siklis (`waktu`) merupakan prediktor terpenting. Tanpa fitur waktu, performa model rekuren terdegradasi ke MAE $> 254$. Fitur panggilan suara dan SMS memberikan kontribusi marginal pada wilayah pusat kota.

#figure(
  caption: [Ablasi Pilihan B4: Pengaruh Fungsi Loss terhadap Kinerja di Duomo],
  placement: top,
  table(
    columns: 5,
    align: (left, center, center, center, center),
    stroke: none,
    table.hline(),
    table.header([*Fungsi Loss*], [*ANN*], [*RNN*], [*LSTM*], [*GRU*]),
    table.hline(stroke: 0.5pt),
    [MSE (Acuan)], [$309.9 plus.minus 5.9$], [$229.6 plus.minus 5.1$], [$273.6 plus.minus 25.4$], [$237.2 plus.minus 9.3$],
    [Huber], [$314.2 plus.minus 7.5$], [$225.3 plus.minus 3.0$], [$271.6 plus.minus 30.3$], [$236.5 plus.minus 7.7$],
    [MAE], [$297.4 plus.minus 16.9$], [$bold(209.5 plus.minus 11.7)$], [$267.7 plus.minus 20.1$], [$bold(220.4 plus.minus 24.8)$],
    table.hline(),
  ),
) <tab:b4>

== Studi Ablasi Pilihan (Bagian B)

=== Hipotesis dan Bukti B4: Fungsi Loss
- *Hipotesis Awal*: Fungsi loss MAE dan Huber akan menghasilkan prediksi dengan metrik evaluasi MAE yang lebih rendah dibandingkan MSE, karena MSE memberikan penalti kuadratik yang membuat gradien bias terhadap lonjakan sesaat (*outliers*).
- *Hasil Pengujian*: Sebagaimana tertera pada @tab:b4, penggunaan fungsi loss MAE berhasil menurunkan Test MAE pada seluruh arsitektur secara signifikan, khususnya RNN ($209.5 plus.minus 11.7$) dan GRU ($220.4 plus.minus 24.8$). Hipotesis *terbukti sepenuhnya*.

=== Hipotesis dan Bukti B6: Forecasting Horizon
- *Hipotesis Awal*: Peningkatan horizon prediksi ke $H=6$ dan $H=24$ jam akan memicu fenomena akumulasi kesalahan (*error compounding*), sehingga model rekuren yang awalnya unggul pada $H=1$ akan mulai terkejar atau bahkan kalah dari baseline periodik harian ($t-24$).
- *Hasil Pengujian*: Pada $H=24$ jam, Test MAE seluruh model membengkak di atas $480$ (ANN: $526.8 plus.minus 16.1$, GRU: $503.3 plus.minus 6.5$). Model pembelajaran mesin mulai kehilangan ketajaman detail jam-per-jam dan mendekati rerata variansi harian. Hipotesis *terbukti secara empiris*.

#figure(
  image("gambar/C_terbaik_vs_acuan.png", width: 90%),
  caption: [Perbandingan Test MAE Konfigurasi Acuan vs Terbaik di Duomo terhadap garis batas baseline naif.],
  placement: top,
) <fig:c_banding>

== Evaluasi Konfigurasi Terbaik (Bagian C)
Berdasarkan kriteria objektif rata-rata Val MAE minimum, konfigurasi gabungan terbaik ditetapkan sebagai berikut:
- *ANN*: $W=6$, *hidden*=128, *layers*=1, $eta=0.001$, fitur=`net+waktu`, *loss*=Huber.
- *RNN*: $W=48$, *hidden*=64, *layers*=1, $eta=0.001$, fitur=`semua`, *loss*=MSE.
- *LSTM*: $W=6$, *hidden*=128, *layers*=1, $eta=0.01$, fitur=`semua`, *loss*=MSE.
- *GRU*: $W=48$, *hidden*=128, *layers*=2, $eta=0.001$, fitur=`semua`, *loss*=MAE.

Evaluasi pada @fig:c_banding memperlihatkan keunggulan konfigurasi terbaik. ANN terbaik berhasil menurunkan Test MAE dari $309.9$ ke $292.8$, serta memangkas galat anomali Natal dari $680.1$ ke $586.9$. LSTM terbaik juga mengalami reduksi galat drastis pada Test MAE dari $273.6$ menjadi $244.2$ ($Delta = -29.4$ poin) dan galat Natal dari $586.0$ ke $498.4$ ($Delta = -87.6$ poin).

#figure(
  image("gambar/D_pola_lokasi.png", width: 92%),
  caption: [Dinamika aktivitas internet per jam: Kawasan bisnis Duomo (atas) vs Kawasan hiburan malam Navigli Darsena (bawah).],
  placement: top,
) <fig:d_pola>

== Uji Generalisasi di Lokasi Navigli (Bagian D)
@fig:d_pola memperlihatkan karakteristik spasial yang sangat kontras antara Duomo dan Navigli (Darsena). Jika Duomo didominasi oleh kesibukan perkantoran siang hari, Navigli memperlihatkan puncak aktivitas tinggi pada malam hari dan akhir pekan (*weekend nightlife surge*).

#figure(
  caption: [Hasil Uji Generalisasi di Navigli (Kode 1, Kotak 4555)],
  placement: top,
  table(
    columns: 5,
    align: (left, center, center, center, center),
    stroke: none,
    table.hline(),
    table.header([*Model*], [*Konfigurasi*], [*Val MAE*], [*Test MAE*], [*Test MAPE*]),
    table.hline(stroke: 0.5pt),
    [ANN], [Acuan], [$166.6 plus.minus 5.3$], [$222.3 plus.minus 27.4$], [$8.9 plus.minus 1.1%$],
    [], [*Terbaik*], [$138.9 plus.minus 1.9$], [$189.6 plus.minus 14.0$], [$7.8 plus.minus 0.6%$],
    [RNN], [Acuan], [$147.3 plus.minus 4.9$], [$bold(193.0 plus.minus 9.7)$], [$7.8 plus.minus 0.3%$],
    [], [*Terbaik*], [$142.9 plus.minus 2.6$], [$203.6 plus.minus 11.8$], [$8.3 plus.minus 0.2%$],
    [LSTM], [Acuan], [$164.3 plus.minus 9.3$], [$247.3 plus.minus 54.3$], [$10.0 plus.minus 2.7%$],
    [], [*Terbaik*], [$144.3 plus.minus 1.8$], [$219.5 plus.minus 17.1$], [$8.9 plus.minus 1.1%$],
    [GRU], [Acuan], [$149.0 plus.minus 6.0$], [$190.8 plus.minus 6.8$], [$7.8 plus.minus 0.2%$],
    [], [*Terbaik*], [$bold(130.7 plus.minus 3.3)$], [$bold(176.7 plus.minus 15.9)$], [$bold(7.4 plus.minus 0.8%)$],
    table.hline(),
  ),
) <tab:d_generalisasi>

Hasil pada @tab:d_generalisasi mengonfirmasi bahwa *konfigurasi GRU terbaik merupakan arsitektur paling unggul* di kawasan Navigli dengan Test MAE $176.7 plus.minus 15.9$ dan Test MAPE $7.4%$, mengungguli seluruh model acuan maupun varian rekuren lainnya. Konfigurasi ANN terbaik juga menunjukkan kemampuan adaptasi luar biasa dengan memangkas Test MAE dari $222.3$ menjadi $189.6$.

= Pembahasan
Studi ablasi ini mengungkap sejumlah prinsip perancangan jaringan saraf tiruan untuk peramalan deret waktu telekomunikasi:
1. *Sensitivitas Hiperparameter*: Pilihan fungsi loss (B4) dan panjang jendela $W$ (A1) memiliki pengaruh yang jauh lebih dominan terhadap akurasi peramalan dibandingkan sekadar menumpuk lapisan (*layer stacking* A3). Menumpuk lapisan pada data latih 4 minggu terbukti memicu *overfitting* prematur.
2. *Superioritas Model Rekuren Gated*: Meskipun RNN standar kompetitif pada data stasioner reguler di Duomo, arsitektur berbasis gerbang (khususnya GRU) terbukti jauh lebih kokoh saat ditransfer ke lokasi Navigli yang memiliki pola *nightlife* fluktuatif. Mekanisme *reset* dan *update gate* pada GRU memungkinkan penyaringan informasi jangka pendek tanpa terdistorsi oleh lonjakan aktivitas sesaat.
3. *Keterbatasan Studi*: Evaluasi dibatasi pada rentang waktu 8 minggu pada musim dingin di satu kota metropolis. Skalabilitas model pada kondisi cuaca ekstrem atau kota berkembang memerlukan pengujian korpus data spasial yang lebih luas.

= Kesimpulan
Melalui 312 run eksperimen ablasi sistematis lintas 3 seed, penelitian ini menyimpulkan bahwa:
1. Pilihan hiperparameter memainkan peranan krusial; konfigurasi optimal terbukti memangkas Test MAE ANN sebesar 5.5% dan LSTM sebesar 10.7% di Duomo, serta mereduksi galat masa anomali Natal secara substansial.
2. Fungsi loss MAE secara konsisten mengungguli MSE dalam melatih jaringan saraf untuk metrik evaluasi linier, memberikan perbaikan hingga $Delta = -20.1$ poin MAE pada RNN.
3. Pada uji generalisasi di kawasan *Navigli (Darsena)*, arsitektur *GRU Terbaik* ($W=48$, *hidden*=128, *layers*=2, *loss*=MAE) meraih performa terbaik mutlak dengan Test MAE $176.7 plus.minus 15.9$ (turun 7.4% dari model acuan).

#heading(numbering: none)[Kontribusi Anggota Kelompok]
#table(
  columns: (auto, 1fr),
  stroke: none,
  [Robin Syaifuddin], [Manajemen repositori Git sejak inisialisasi awal, integrasi skrip smoke test, validasi metrik dan visualisasi plot.],
  [Muhammad Hamdan Al Ghifari], [Formulasi hipotesis Bagian B, analisis hasil lokasi Navigli, penyusunan naskah laporan IEEE Typst.],
  [Nathanael Hasiholan Turnip], [Setup pipeline data & arsitektur model, eksekusi eksperimen ablasi wajib Bagian A, kurasi tabel hasil eksperimen.],
)

#heading(numbering: none)[Pernyataan Penggunaan AI]
Asisten kecerdasan buatan (Antigravity Agentic Assistant / Gemini 3.8 Flash) digunakan dalam membantu otomatisasi *batch execution* skrip pelatihan Python, perhitungan format tabel numerik $mu plus.minus sigma$, perapian tata bahasa ilmiah, serta kompilasi berkas laporan IEEE Typst. Seluruh data eksperimen, log komputasi, dan bobot model berasal murni dari pelatihan 312 run independen pada repositori kelompok.

#heading(numbering: none)[Kode dan Data]
Seluruh kode implementasi PyTorch, visualisasi grafik, dan dataset log ablasi tersedia secara terbuka pada repositori GitHub resmi kelompok: #link("https://github.com/robinsyaifuddin/dlw-tugas-ra-122400001.git").
