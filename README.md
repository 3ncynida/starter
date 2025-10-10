<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About

<p>tugas sekolah. Persiapan UKK</p>

## How to

1. **Clone Repository**
```bash
git clone https://github.com/3ncynida/starter.git
```
2. **Buka terminal, lalu ketik**
```
cd starter
composer install
npm install
cp .env.example .env
php artisan key:generate
```

3. **Buka ```.env``` lalu ubah baris berikut sesuaikan dengan databasemu yang ingin dipakai**
```
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

3. **Jalankan bash**
```bash
php artisan config:cache
php artisan storage:link
php artisan route:clear
```

4. **Jalankan migrations dan seeders**
```
php artisan migrate --seed
```
5. **Jalankan nodejs**
```
npm run dev
```

5. **Jalankan website**
```bash
php artisan serve