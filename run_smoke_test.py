import time
from pathlib import Path
import pandas as pd
import numpy as np
import torch
import torch.nn as nn

print("=== 1. TEST DATA LOADING ===")
df_duomo = pd.read_csv('data/milano_duomo_5060.csv')
print(f"Data Duomo shape: {df_duomo.shape}")
df_lokasi = pd.read_csv('data/milano_tugas_10_lokasi.csv')
print(f"Data 10 Lokasi shape: {df_lokasi.shape}")

# Filter lokasi kelompok 1 (Navigli) berdasarkan kolom 'kode'
df_navigli = df_lokasi[df_lokasi['kode'] == 1]
print(f"Data Navigli (Lokasi 1 - {df_navigli['lokasi'].iloc[0]}): {df_navigli.shape}")
assert len(df_navigli) > 0, "Data Navigli tidak boleh kosong!"

print("\n=== 2. TEST TENSOR & MODEL ARCHITECTURES ===")
class SmokeANN(nn.Module):
    def __init__(self):
        super().__init__()
        self.fc = nn.Linear(24 * 7, 32)
        self.out = nn.Linear(32, 1)
    def forward(self, x):
        return self.out(torch.relu(self.fc(x.flatten(1))))

class SmokeGRU(nn.Module):
    def __init__(self):
        super().__init__()
        self.gru = nn.GRU(input_size=7, hidden_size=38, batch_first=True)
        self.out = nn.Linear(38, 1)
    def forward(self, x):
        out, _ = self.gru(x)
        return self.out(out[:, -1, :])

dummy_x = torch.randn(16, 24, 7) # batch 16, window 24, 7 fitur
ann = SmokeANN()
gru = SmokeGRU()
print(f"ANN forward output shape: {ann(dummy_x).shape}")
print(f"GRU forward output shape: {gru(dummy_x).shape}")

print("\n=== 3. TEST LOSS B4 & BACKWARD PASS ===")
criterion = nn.HuberLoss() # Uji loss Huber
pred = gru(dummy_x)
target = torch.randn(16, 1)
loss = criterion(pred, target)
loss.backward()
print(f"Huber Loss value: {loss.item():.4f} | Backward pass gradient computed!")

Path("hasil").mkdir(exist_ok=True)
Path("hasil/smoke_test_result.txt").write_text(
    f"SMOKE TEST PASSED: {time.strftime('%Y-%m-%d %H:%M:%S')}\n"
    f"Data Duomo: {df_duomo.shape}\n"
    f"Data Navigli: {df_navigli.shape}\n"
    f"PyTorch: {torch.__version__}\n"
    f"Loss Huber: {loss.item():.4f}\n"
)
print("\n=== SMOKE TEST SUKSES 100%! PIPELINE SIAP UNTUK EKSPERIMEN ===")
