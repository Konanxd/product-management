# product-management

Tata cara melakukan instalasi:

## 1. Buka Git Bash dan masuk ke directory project di local
Contoh untuk pindah disk:
```bash
cd D:
```
Contoh untuk pindah ke directory project:
```bash
cd project/Laravel/Komawan
```

## 2. Lakukan clone
```bash
git clone https://github.com/Konanxd/product-management.git
cd product-management
```

## 3. Setup backend
Install PHP Dependencies
```bash
composer install
```
Setup environment
```bash
cp .env.example .env
```
Generate key (wajib)
```bash
php artisan key:generate
```
Lakukan migrasi database
```bash
php artisan migrate
```

## 4. Setup frontend
Install Node.js Dependencies
```bash
npm install
```
(Opsional) Jika menggunakan yarn
```bash
yarn install
```

## 4. Menjalankan aplikasi
Direkomendasikan menggunakan Laragon atau XAMPP. Namun, service backend masih dapat dijalankan dengan:
```bash
php artisan serve
```
Compile frontend (jika ingin menjalankan project, wajib lakukan ini)
```bash
npm run dev
```
