<?php
/**
 * Mission Control & Ablation Study Tracker
 * Deep Learning Workshop · Teknik Telekomunikasi ITERA
 * Lokasi Kelompok: Navigli (Darsena) - Kotak 4555
 */

$dataFilePath = __DIR__ . '/dashboard_state.json';

// Inisialisasi state default jika belum ada
if (!file_exists($dataFilePath)) {
    $initialState = [
        'github_repo' => 'mctosima/dl-workshop', // Default repo acuan, dapat diganti ke repo kelompok
        'tasks' => [
            // Fase 1
            'f1_identitas' => true,
            'f1_lokasi' => true,
            'f1_faktor_b' => true,
            'f1_planning' => true,
            'f1_clone_structure' => false,
            'f1_setup_env' => false,
            'f1_vercel_deploy' => false,
            'f1_smoke_test' => false,
            // Fase 2
            'f2_bag0_naive' => false,
            'f2_bag0_acuan' => false,
            'f2_a1_window' => false,
            'f2_a2_hidden' => false,
            'f2_a3_layers' => false,
            'f2_a4_lr' => false,
            'f2_a5_fitur' => false,
            'f2_b4_loss' => false,
            'f2_b6_horizon' => false,
            // Fase 3
            'f3_val_mae' => false,
            'f3_best_config' => false,
            'f3_test_eval' => false,
            'f3_natal_eval' => false,
            // Fase 4
            'f4_navigli_acuan' => false,
            'f4_navigli_best' => false,
            'f4_analisis_spasial' => false,
            // Fase 5
            'f5_typst_project' => false,
            'f5_tabel_mean_std' => false,
            'f5_grafik_export' => false,
            'f5_audit_6hal' => false,
            'f5_commit_full_nb' => false,
            'f5_tally_submit' => false,
        ],
        'notes' => "Prioritas eksperimen: Uji Bagian B difokuskan pada B4 (Fungsi Loss: MSE, MAE, Huber) untuk meredam lonjakan anomali di Navigli, serta B6 (Horizon: 1, 6, 24 jam) untuk menguji kapasitas memori jangka panjang agen."
    ];
    @file_put_contents($dataFilePath, json_encode($initialState, JSON_PRETTY_PRINT));
}

$content = @file_get_contents($dataFilePath);
$state = $content ? json_decode($content, true) : [];
$tasks = $state['tasks'] ?? [];
$githubRepo = $state['github_repo'] ?? 'mctosima/dl-workshop';

// Handle AJAX update konfigurasi repo GitHub atau Notes (Local-only/Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'update_github_repo') {
        $newRepo = trim($_POST['repo'] ?? '');
        if (!empty($newRepo)) {
            $state['github_repo'] = $newRepo;
            @file_put_contents($dataFilePath, json_encode($state, JSON_PRETTY_PRINT));
            echo json_encode(['status' => 'success', 'repo' => $newRepo]);
            exit;
        }
    }
    
    if ($_POST['action'] === 'toggle_task_audit') {
        $taskId = $_POST['task_id'] ?? '';
        $checked = !empty($_POST['checked']);
        $device = $_POST['device'] ?? 'Unknown Device';
        $ip = $_POST['ip'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);
        $time = $_POST['time'] ?? date('d M Y, H:i') . ' WIB';

        if (!isset($state['tasks'])) $state['tasks'] = [];
        if (!isset($state['audit_trail'])) $state['audit_trail'] = [];

        $state['tasks'][$taskId] = $checked;
        if ($checked) {
            $state['audit_trail'][$taskId] = [
                'device' => $device,
                'ip' => $ip,
                'time' => $time
            ];
        } else {
            unset($state['audit_trail'][$taskId]);
        }

        @file_put_contents($dataFilePath, json_encode($state, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'taskId' => $taskId, 'checked' => $checked]);
        exit;
    }
    
    if ($_POST['action'] === 'save_notes') {
        $state['notes'] = $_POST['notes'] ?? '';
        @file_put_contents($dataFilePath, json_encode($state, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success']);
        exit;
    }
    
    echo json_encode(['status' => 'error', 'message' => 'Action tidak didukung']);
    exit;
}

$totalTasks = count($tasks);
$completedTasks = count(array_filter($tasks));
$percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

// Cek file hasil/ablasi.csv
$csvFile = __DIR__ . '/hasil/ablasi.csv';
$csvCount = 0;
if (file_exists($csvFile)) {
    $lines = file($csvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $csvCount = max(0, count($lines) - 1);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studi Ablasi Deep Learning · Workspace Navigli</title>
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        canvas: '#f8fafc',
                    },
                    boxShadow: {
                        'subtle': '0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.03)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>
<body class="min-h-screen pb-20">

    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-subtle">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div>
                <h1 class="text-base font-bold text-slate-900 tracking-tight">Studi Ablasi Deep Learning</h1>
                <p class="text-xs text-slate-500 font-medium">Workshop Deep Learning · Kecerdasan Buatan RA</p>
            </div>

            <!-- Progress Meter & Tombol Repo -->
            <div class="flex items-center gap-6">
                <!-- Progress Meter -->
                <div class="hidden sm:flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Progres Resmi</div>
                        <div class="text-xs font-bold font-mono text-slate-900" id="progress-text"><?= $completedTasks ?> / <?= $totalTasks ?> Selesai (<?= $percentage ?>%)</div>
                    </div>
                    <div class="w-28 bg-slate-100 rounded-full h-2 p-0.5 border border-slate-200 flex items-center">
                        <div id="progress-bar" class="bg-slate-900 h-full rounded-full transition-all duration-300 ease-out" style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>

                <!-- Tombol Repo GitHub -->
                <a href="https://github.com/<?= htmlspecialchars($githubRepo) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-xs transition">
                    <i class="fa-brands fa-github text-sm"></i>
                    <span>Repository</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Subheader Alert / Deadline Bar -->
    <div class="bg-slate-100/70 border-b border-slate-200/60 py-2.5 px-4 sm:px-6 text-xs text-slate-600">
        <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Tenggat Pengumpulan: <strong class="text-slate-900 font-semibold">Rabu, 28 Oktober 2026, 23.59 WIB</strong></span>
            </div>
            <div class="flex items-center gap-4 text-slate-500 font-medium">
                <span>Format: IEEE 2-Kolom (Typst)</span>
                <span>•</span>
                <span>Status Tracker: <strong class="text-emerald-700 font-semibold">Tersinkronisasi Otomatis</strong></span>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-8">

        <!-- Top Information Grid: Editorial Style -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            
            <!-- Card 1: Anggota Tim -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-subtle flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <span>Anggota Kelompok</span>
                        <i class="fa-regular fa-user-group text-slate-400"></i>
                    </div>
                    <div class="mt-3.5 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-900">Robin Syaifuddin</span>
                            <span class="font-mono text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">122400001 (01)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-700">M. Hamdan Al Ghifari</span>
                            <span class="font-mono text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">121400075</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-700">Nathanael Hasiholan T.</span>
                            <span class="font-mono text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">122400053</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Penentu Lokasi:</span>
                    <span class="font-semibold text-slate-700">NIM Terkecil (Digit 1)</span>
                </div>
            </div>

            <!-- Card 2: Lokasi Kelompok -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-subtle flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <span>Target Lokasi (Bagian D)</span>
                        <i class="fa-regular fa-location-dot text-slate-400"></i>
                    </div>
                    <div class="mt-3.5">
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-bold text-slate-900 tracking-tight">Navigli (Darsena)</span>
                            <span class="text-xs font-mono font-semibold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">Kotak 4555</span>
                        </div>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Kawasan kanal & hiburan malam (*nightlife*). Karakteristik: beban trafik melonjak tajam saat malam hingga dini hari (20.00–02.00) dan *weekend surge*.
                        </p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Fokus Pola:</span>
                    <span class="font-medium text-slate-700">Spike Malam & Weekend</span>
                </div>
            </div>

            <!-- Card 3: Faktor Bagian B & Visi AI Agentik -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-subtle flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <span>Ablasi Pilihan & Visi Agentik</span>
                        <i class="fa-regular fa-compass text-slate-400"></i>
                    </div>
                    <div class="mt-3.5 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-900 text-white font-mono">B4</span>
                            <span class="text-xs font-semibold text-slate-800">Fungsi Loss: MSE, MAE, <span class="underline decoration-emerald-500 decoration-2">Huber</span></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-900 text-white font-mono">B6</span>
                            <span class="text-xs font-semibold text-slate-800">Horizon: 1, 6, 24 jam (Multi-step foresight)</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Database Run:</span>
                    <span class="font-mono font-semibold text-slate-800"><?= $csvCount ?> / ±280 run tercatat</span>
                </div>
            </div>

        </div>

        <!-- Checklist Container (READ-ONLY OTOMATIS) -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-subtle overflow-hidden">
            
            <!-- Navigation Header Inside Card -->
            <div class="border-b border-slate-200 px-6 py-4 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-900">Checklist Eksekusi & Fase Proyek</h2>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-200/70 text-slate-700">
                            <i class="fa-solid fa-lock text-[9px]"></i> Read-Only Otomatis
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Status indikator diperbarui otomatis dari repositori Git dan file konfigurasi proyek.</p>
                </div>

                <div class="flex items-center gap-1.5 bg-white p-1 rounded-lg border border-slate-200 shadow-xs text-xs font-medium">
                    <button type="button" class="px-3 py-1.5 rounded-md bg-slate-900 text-white font-semibold shadow-xs">Semua Fase</button>
                    <a href="#fase-1" class="px-3 py-1.5 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">Fase 1</a>
                    <a href="#fase-2" class="px-3 py-1.5 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">Fase 2</a>
                    <a href="#fase-3" class="px-3 py-1.5 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">Fase 3 & 4</a>
                    <a href="#fase-5" class="px-3 py-1.5 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">Fase 5</a>
                </div>
            </div>

            <div class="p-6 space-y-8 divide-y divide-slate-100">

                <!-- FASE 1 -->
                <section id="fase-1" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-800 text-xs font-bold flex items-center justify-center border border-slate-300">1</span>
                            <h3 class="text-sm font-bold text-slate-900">Fase 1: Inisialisasi, Setup Lingkungan & Publikasi Live Tracker</h3>
                        </div>
                        <span class="text-xs font-medium text-slate-400">Target: 16 Okt 2026</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                        <?php
                        $fase1Tasks = [
                            'f1_identitas' => 'Pendaftaran nama & NIM anggota tim lengkap',
                            'f1_lokasi' => 'Penguncian Kode Lokasi 1: Navigli (Darsena - Kotak 4555)',
                            'f1_faktor_b' => 'Pemilihan 2 faktor Bagian B (B4 Loss & B6 Horizon) + Hipotesis',
                            'f1_planning' => 'Pembuatan master plan & dokumen strategi di folder plan/',
                            'f1_clone_structure' => 'Setup & kloning repo publik dengan struktur proyek yang benar',
                            'f1_setup_env' => 'Setup dependensi Python (uv sync / pip install)',
                            'f1_vercel_deploy' => 'Kaitkan repo GitHub ke Vercel agar dashboard live online publik',
                            'f1_smoke_test' => 'Smoke test notebook dengan flag CEPAT = True',
                        ];
                        foreach ($fase1Tasks as $id => $label):
                            $checked = !empty($tasks[$id]);
                        ?>
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200/80 bg-white select-none">
                            <div class="flex items-start gap-3">
                                <?php if ($checked): ?>
                                    <span class="w-4 h-4 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] mt-0.5 shrink-0">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="w-4 h-4 rounded border border-slate-300 bg-slate-50 flex items-center justify-center text-[10px] mt-0.5 shrink-0"></span>
                                <?php endif; ?>
                                <span class="text-xs font-medium text-slate-700 leading-snug <?= $checked ? 'line-through text-slate-400' : '' ?>">
                                    <?= htmlspecialchars($label) ?>
                                </span>
                            </div>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded font-semibold <?= $checked ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' ?>">
                                <?= $checked ? 'Selesai' : 'Pending' ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- FASE 2 -->
                <section id="fase-2" class="pt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-800 text-xs font-bold flex items-center justify-center border border-slate-300">2</span>
                            <h3 class="text-sm font-bold text-slate-900">Fase 2: Eksekusi Eksperimen (Bagian 0, Bagian A, dan Bagian B)</h3>
                        </div>
                        <span class="text-xs font-mono font-medium text-slate-500">Total ±280 run (3 seed, CEPAT = False)</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                        <?php
                        $fase2Tasks = [
                            'f2_bag0_naive' => 'Bagian 0: Hitung 3 baseline naif (terakhir, kemarin, minggu lalu)',
                            'f2_bag0_acuan' => 'Bagian 0: Run acuan 4 model (ANN, RNN, LSTM, GRU) × 3 seed',
                            'f2_a1_window' => 'Bagian A1: Window Size W (6, 12, 24, 48, 168 jam) - 60 run',
                            'f2_a2_hidden' => 'Bagian A2: Hidden Size (16, 32, 64, 128) - 48 run',
                            'f2_a3_layers' => 'Bagian A3: Jumlah Lapisan (1, 2, 3) - 36 run',
                            'f2_a4_lr' => 'Bagian A4: Learning Rate (0.0001, 0.001, 0.01) - 36 run',
                            'f2_a5_fitur' => 'Bagian A5: Kelompok Fitur (net, +call+sms, +waktu, semua) - 48 run',
                            'f2_b4_loss' => 'Bagian B4: Fungsi Loss (MSE, MAE, Huber Loss) - 36 run',
                            'f2_b6_horizon' => 'Bagian B6: Horizon (1 jam, 6 jam, 24 jam) - 36 run',
                        ];
                        foreach ($fase2Tasks as $id => $label):
                            $checked = !empty($tasks[$id]);
                        ?>
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200/80 bg-white select-none">
                            <div class="flex items-start gap-3">
                                <?php if ($checked): ?>
                                    <span class="w-4 h-4 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] mt-0.5 shrink-0">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="w-4 h-4 rounded border border-slate-300 bg-slate-50 flex items-center justify-center text-[10px] mt-0.5 shrink-0"></span>
                                <?php endif; ?>
                                <span class="text-xs font-medium text-slate-700 leading-snug <?= $checked ? 'line-through text-slate-400' : '' ?>">
                                    <?= htmlspecialchars($label) ?>
                                </span>
                            </div>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded font-semibold <?= $checked ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' ?>">
                                <?= $checked ? 'Selesai' : 'Pending' ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- FASE 3 & 4 -->
                <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- FASE 3 -->
                    <section id="fase-3" class="space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-800 text-xs font-bold flex items-center justify-center border border-slate-300">3</span>
                            <h3 class="text-sm font-bold text-slate-900">Fase 3: Seleksi Model Terbaik (Bagian C)</h3>
                        </div>

                        <div class="space-y-2">
                            <?php
                            $fase3Tasks = [
                                'f3_val_mae' => 'Hitung rata-rata Val MAE 3 seed untuk semua faktor',
                                'f3_best_config' => 'Pilih nilai faktor optimal per model (HANYA dari Val MAE)',
                                'f3_test_eval' => 'Evaluasi model terbaik pada Minggu Test (16–22 Des)',
                                'f3_natal_eval' => 'Evaluasi model terbaik pada Minggu Natal (23–29 Des)',
                            ];
                            foreach ($fase3Tasks as $id => $label):
                                $checked = !empty($tasks[$id]);
                            ?>
                            <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200/80 bg-white select-none">
                                <div class="flex items-start gap-3">
                                    <?php if ($checked): ?>
                                        <span class="w-4 h-4 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] mt-0.5 shrink-0">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    <?php else: ?>
                                        <span class="w-4 h-4 rounded border border-slate-300 bg-slate-50 flex items-center justify-center text-[10px] mt-0.5 shrink-0"></span>
                                    <?php endif; ?>
                                    <span class="text-xs font-medium text-slate-700 leading-snug <?= $checked ? 'line-through text-slate-400' : '' ?>">
                                        <?= htmlspecialchars($label) ?>
                                    </span>
                                </div>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded font-semibold <?= $checked ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= $checked ? 'Selesai' : 'Pending' ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- FASE 4 -->
                    <section id="fase-4" class="space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-800 text-xs font-bold flex items-center justify-center border border-slate-300">4</span>
                            <h3 class="text-sm font-bold text-slate-900">Fase 4: Uji Generalisasi di Navigli (Bagian D)</h3>
                        </div>

                        <div class="space-y-2">
                            <?php
                            $fase4Tasks = [
                                'f4_navigli_acuan' => 'Latih konfigurasi acuan di Navigli (Kotak 4555) 3 seed',
                                'f4_navigli_best' => 'Latih konfigurasi terbaik di Navigli (Kotak 4555) 3 seed',
                                'f4_analisis_spasial' => 'Analisis korelasi hasil dengan pola nightlife & kanal Navigli',
                            ];
                            foreach ($fase4Tasks as $id => $label):
                                $checked = !empty($tasks[$id]);
                            ?>
                            <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200/80 bg-white select-none">
                                <div class="flex items-start gap-3">
                                    <?php if ($checked): ?>
                                        <span class="w-4 h-4 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] mt-0.5 shrink-0">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    <?php else: ?>
                                        <span class="w-4 h-4 rounded border border-slate-300 bg-slate-50 flex items-center justify-center text-[10px] mt-0.5 shrink-0"></span>
                                    <?php endif; ?>
                                    <span class="text-xs font-medium text-slate-700 leading-snug <?= $checked ? 'line-through text-slate-400' : '' ?>">
                                        <?= htmlspecialchars($label) ?>
                                    </span>
                                </div>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded font-semibold <?= $checked ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= $checked ? 'Selesai' : 'Pending' ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>

                <!-- FASE 5 -->
                <section id="fase-5" class="pt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-800 text-xs font-bold flex items-center justify-center border border-slate-300">5</span>
                            <h3 class="text-sm font-bold text-slate-900">Fase 5: Penulisan Laporan IEEE Typst & Submission Tally</h3>
                        </div>
                        <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">Deadline: 28 Okt 23.59 WIB</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                        <?php
                        $fase5Tasks = [
                            'f5_typst_project' => 'Setup template laporan-typst di typst.app / lokal (IEEE 2 kolom)',
                            'f5_tabel_mean_std' => 'Generate tabel komprehensif dalam format (rata-rata ± std)',
                            'f5_grafik_export' => 'Ekspor grafik kurva konvergensi & peramalan Natal ke Typst',
                            'f5_audit_6hal' => 'Audit kepatuhan: Maks 6 halaman, ada Bab AI & Kontribusi',
                            'f5_commit_full_nb' => 'Commit tugas_ablasi.ipynb DENGAN seluruh output sel',
                            'f5_tally_submit' => 'Submit form Tally (URL repo publik + PDF) oleh ketua tim',
                        ];
                        foreach ($fase5Tasks as $id => $label):
                            $checked = !empty($tasks[$id]);
                        ?>
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200/80 bg-white select-none">
                            <div class="flex items-start gap-3">
                                <?php if ($checked): ?>
                                    <span class="w-4 h-4 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] mt-0.5 shrink-0">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="w-4 h-4 rounded border border-slate-300 bg-slate-50 flex items-center justify-center text-[10px] mt-0.5 shrink-0"></span>
                                <?php endif; ?>
                                <span class="text-xs font-medium text-slate-700 leading-snug <?= $checked ? 'line-through text-slate-400' : '' ?>">
                                    <?= htmlspecialchars($label) ?>
                                </span>
                            </div>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded font-semibold <?= $checked ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' ?>">
                                <?= $checked ? 'Selesai' : 'Pending' ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

            </div>
        </div>

        <!-- SECTION: RIWAYAT COMMIT GITHUB (REALTIME) -->
        <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-subtle space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <i class="fa-brands fa-github text-slate-900 text-lg"></i>
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Commit Anggota Tim (Realtime)</h3>
                        <span class="flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live Sync
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Memantau kontribusi commit aktif Robin, Hamdan, dan Nathanael langsung dari GitHub.</p>
                </div>

                <!-- Tombol Refresh & Ganti Repo -->
                <div class="flex items-center gap-2">
                    <button id="btn-refresh-commits" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs rounded-md font-semibold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate text-xs" id="refresh-icon"></i> Refresh
                    </button>
                    <button id="btn-toggle-repo-setting" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs rounded-md font-semibold transition flex items-center gap-1.5 shadow-xs">
                        <i class="fa-regular fa-folder-gear text-xs"></i> Set Repo
                    </button>
                </div>
            </div>

            <!-- Form Edit Repo GitHub (Bisa di-toggle) -->
            <div id="repo-setting-box" class="hidden bg-slate-50 border border-slate-200 rounded-lg p-3.5 space-y-2">
                <label class="block text-xs font-semibold text-slate-700">Repositori GitHub Target (format: username/nama-repo):</label>
                <div class="flex items-center gap-2">
                    <input type="text" id="input-github-repo" value="<?= htmlspecialchars($githubRepo) ?>" placeholder="contoh: username/dlw-tugas-ra-122400001" class="flex-1 bg-white border border-slate-300 rounded-md px-3 py-1.5 text-xs text-slate-900 font-mono focus:outline-none focus:ring-1 focus:ring-slate-900">
                    <button id="btn-save-repo" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-md transition">Simpan</button>
                </div>
                <p class="text-[11px] text-slate-500">Saat repo kelompok Anda sudah dibuat dan di-push publik, masukkan slug repo di atas.</p>
            </div>

            <!-- Container List Commit Live -->
            <div id="commits-container" class="space-y-2.5">
                <div class="py-8 text-center text-slate-400 text-xs flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-notch fa-spin text-sm"></i>
                    <span>Menghubungkan ke GitHub REST API...</span>
                </div>
            </div>
        </div>

        <!-- Section Catatan Diskusi Tim -->
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-subtle">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Catatan Kerja & Hipotesis Tim</span>
                    <span id="notes-status" class="text-xs text-emerald-600 font-medium hidden">• Tersimpan otomatis</span>
                </div>
                <button id="save-notes-btn" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 text-white text-xs rounded-md font-semibold transition flex items-center gap-1.5 shadow-xs">
                    <i class="fa-regular fa-floppy-disk text-xs"></i> Simpan Catatan
                </button>
            </div>
            <textarea id="team-notes" rows="3" class="w-full bg-slate-50/70 border border-slate-200 rounded-lg p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 leading-relaxed font-sans" placeholder="Tuliskan catatan teknis atau status pembagian tugas di sini..."><?= htmlspecialchars($state['notes'] ?? '') ?></textarea>
        </div>

    </main>

    <!-- Footer -->
    <footer class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 text-center text-xs text-slate-400 font-medium">
        <p>Studi Ablasi Deep Learning · Teknik Telekomunikasi ITERA · Tim Navigli 2026</p>
    </footer>

    <!-- Script Fetch Realtime GitHub Commits -->
    <script>
        let currentRepo = "<?= htmlspecialchars($githubRepo) ?>";

        function timeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return `${seconds} detik yang lalu`;
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `${minutes} menit yang lalu`;
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours} jam yang lalu`;
            const days = Math.floor(hours / 24);
            return `${days} hari yang lalu`;
        }

        function loadGitHubCommits() {
            const container = document.getElementById('commits-container');
            const icon = document.getElementById('refresh-icon');
            icon.classList.add('fa-spin');

            fetch(`https://api.github.com/repos/${currentRepo}/commits?per_page=8`)
                .then(res => {
                    if (!res.ok) throw new Error('Repositori tidak ditemukan atau belum dibuat publik di GitHub.');
                    return res.json();
                })
                .then(commits => {
                    icon.classList.remove('fa-spin');
                    if (!Array.isArray(commits) || commits.length === 0) {
                        container.innerHTML = `<div class="py-6 text-center text-slate-400 text-xs">Belum ada commit yang tercatat di repositori ini.</div>`;
                        return;
                    }

                    let html = '';
                    commits.forEach(item => {
                        const commitMsg = item.commit.message.split('\n')[0];
                        const authorName = item.commit.author.name;
                        const avatarUrl = item.author ? item.author.avatar_url : 'https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png';
                        const commitSha = item.sha.substring(0, 7);
                        const commitUrl = item.html_url;
                        const dateFormatted = timeAgo(item.commit.author.date);

                        html += `
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="${avatarUrl}" alt="${authorName}" class="w-8 h-8 rounded-full border border-slate-200 shrink-0">
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-slate-900 truncate">${commitMsg}</div>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                        <span class="font-medium text-slate-700">${authorName}</span>
                                        <span>•</span>
                                        <span>${dateFormatted}</span>
                                    </div>
                                </div>
                            </div>
                            <a href="${commitUrl}" target="_blank" rel="noopener noreferrer" class="ml-3 shrink-0 font-mono text-[11px] font-semibold text-slate-600 bg-white hover:text-slate-900 hover:border-slate-400 border border-slate-200 px-2 py-1 rounded transition flex items-center gap-1">
                                <i class="fa-solid fa-code-commit text-[10px]"></i> ${commitSha}
                            </a>
                        </div>
                        `;
                    });

                    container.innerHTML = html;
                })
                .catch(err => {
                    icon.classList.remove('fa-spin');
                    container.innerHTML = `
                    <div class="py-6 text-center text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-lg p-4">
                        <i class="fa-regular fa-circle-exclamation text-amber-500 text-sm mb-1"></i>
                        <p class="font-semibold text-slate-700">Status Repositori: [ ${currentRepo} ]</p>
                        <p class="text-[11px] text-slate-400 mt-1">${err.message}</p>
                        <p class="text-[11px] text-slate-500 mt-2">Gunakan tombol <strong>"Set Repo"</strong> di kanan atas untuk memasukkan slug repo kelompok Anda setelah di-push ke GitHub.</p>
                    </div>
                    `;
                });
        }

        // Jalankan saat pertama kali halaman terbuka
        document.addEventListener('DOMContentLoaded', loadGitHubCommits);
        document.getElementById('btn-refresh-commits').addEventListener('click', loadGitHubCommits);

        // Toggle form set repo
        document.getElementById('btn-toggle-repo-setting').addEventListener('click', function() {
            const box = document.getElementById('repo-setting-box');
            box.classList.toggle('hidden');
        });

        // Simpan Repo Target
        document.getElementById('btn-save-repo').addEventListener('click', function() {
            const newRepo = document.getElementById('input-github-repo').value.trim();
            if (!newRepo) return;

            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_github_repo&repo=${encodeURIComponent(newRepo)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    currentRepo = newRepo;
                    document.getElementById('repo-setting-box').classList.add('hidden');
                    loadGitHubCommits();
                }
            });
        });

        // Simpan Catatan Tim
        document.getElementById('save-notes-btn').addEventListener('click', function() {
            const notes = document.getElementById('team-notes').value;
            const statusEl = document.getElementById('notes-status');

            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=save_notes&notes=${encodeURIComponent(notes)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    statusEl.classList.remove('hidden');
                    setTimeout(() => statusEl.classList.add('hidden'), 2500);
                }
            });
        });
    </script>
</body>
</html>
