@echo off
REM Helin queue worker - Windows wrapper (restart loop)
cd /d C:\xampp\htdocs\helin
:loop
"C:\xampp\php\php.exe" artisan queue:work --queue=whatsapp,default --tries=3 --backoff=30 --sleep=3 --timeout=120
echo [%date% %time%] Worker salio con codigo %ERRORLEVEL%. Reiniciando en 5s...
timeout /t 5 /nobreak >nul
goto loop
