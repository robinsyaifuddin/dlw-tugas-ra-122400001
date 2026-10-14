@echo off
title Dashboard Studi Ablasi DL - Tim Navigli
echo ========================================================
echo   MENJALANKAN DASHBOARD LOKAL STUDI ABLASI DEEP LEARNING
echo   Tim Navigli (Darsena - Kotak 4555)
echo ========================================================
echo.
echo Dashboard dapat diakses di browser pada:
echo http://localhost:8000
echo.
echo Tekan Ctrl+C di terminal ini untuk menghentikan server.
echo.
start http://localhost:8000
php -S localhost:8000
