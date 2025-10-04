# Desa Wisata Karanggayam - Deployment Guide

Proyek ini menggunakan **Laravel** (PHP) untuk aplikasi utama, dan **FastAPI** (Python) untuk layanan pengecekan gambar gapura.  
Dokumen ini menjelaskan cara deploy keduanya di server **Debian/Ubuntu**.

---

## 📌 Prasyarat Server
- Debian/Ubuntu 22.04+
- Nginx
- PHP 8.2+
- Composer
- MySQL
- Python 3.11+ (virtualenv)
- Git

---

## 🚀 1. Clone Project
```bash
cd /var/www
git clone https://github.com/devdesakaranggayam/desa-wisata-karanggayam.git
cd desa-wisata-karanggayam
````

---

## ⚙️ 2. Deploy Laravel

### Install Dependencies

```bash
cd desa-wisata-karanggayam
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

### Set Permissions

```bash
chown -R www-data:www-data /var/www/desa-wisata-karanggayam/laravel-app/storage /var/www/desa-wisata-karanggayam/laravel-app/bootstrap/cache
chmod -R 775 /var/www/desa-wisata-karanggayam/laravel-app/storage /var/www/desa-wisata-karanggayam/laravel-app/bootstrap/cache
```

### Database Migration

```bash
php artisan migrate --force
```

### Configure Web Server (Apache/Nginx)

---

## 🤖 3. Deploy FastAPI

### Install Python Virtualenv

```bash
cd /var/www/desa-wisata-karanggayam/fastapi
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

### Test Run Manual

```bash
venv/bin/uvicorn main:app --host 127.0.0.1 --port 8001
```

### Systemd Service

File: `/etc/systemd/system/fastapi.service`

```ini
[Unit]
Description=FastAPI Application
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/var/www/desa-wisata-karanggayam/fastapi
ExecStart=/var/www/desa-wisata-karanggayam/fastapi/venv/bin/uvicorn main:app --host 127.0.0.1 --port 8001 --workers 2
Restart=always

[Install]
WantedBy=multi-user.target
```

Aktifkan service:

```bash
sudo systemctl daemon-reload
sudo systemctl enable fastapi
sudo systemctl start fastapi
sudo systemctl status fastapi
```

---

## 🔄 4. Integrasi Laravel ↔ FastAPI

* FastAPI berjalan di `http://127.0.0.1:8001`
* Laravel dapat memanggil FastAPI via HTTP request (misalnya pakai `Http::post()`)

Contoh:

```php
$response = Http::post('http://127.0.0.1:8001/check-similarity', [
    'file' => $imagePath,
]);
```

---

## 📂 Struktur Direktori

```
desa-wisata-karanggayam/
├── app
├── bootstrap
├── ...
├── fastapi/       # Service FastAPI (AI/ML)
│   ├── venv/      # Virtualenv Python
│   ├── main.py    # Entry point FastAPI
│   ├── requirements.txt
│   └── ...
```

---

## ✅ Maintenance
### Restart FastAPI

```bash
sudo systemctl restart fastapi
```

### Logs

```bash
# Laravel
tail -f laravel-app/storage/logs/laravel.log

# FastAPI
journalctl -u fastapi -f
```

---

## 🛡️ Security Notes

* Jalankan FastAPI hanya di `127.0.0.1`, tidak di `0.0.0.0`
* Gunakan HTTPS di Web server
* Update paket server secara berkala

---

## Selesai

Sekarang aplikasi **Laravel + FastAPI** sudah jalan otomatis di server