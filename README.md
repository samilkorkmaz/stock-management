# Laravel Stock Management System

A simple stock management system built with [Laravel](https://laravel.com/) 11 using [Breeze](https://github.com/laravel/breeze) and [Filament](https://filamentphp.com/), allowing shops to request items from central inventory and administrators to manage stock allocation.

Shop panel (http://localhost:8000/dashboard):</br>
![image](https://github.com/user-attachments/assets/cbb6a348-ec15-41d7-9337-0dccc937a1f5)


Admin panel (http://localhost:8000/admin/stock-requests):</br>
![image](https://github.com/user-attachments/assets/bf03b147-c46a-4f97-bb95-b5f2b506e90b)

## Features

- Multi-shop system with individual login
- Central inventory management
- Stock request system for shops
- Admin panel built with Filament
- Stock allocation management
- Uses SQLite for simplicity

## Requirements

- PHP 8.2+
- Composer
- Laravel 11+
- Node.js & NPM
- SQLite3

## Installation
First install PHP, composer and Laravel. Then do the following to run this repo locally:
```
git clone https://github.com/samilkorkmaz/stock-management
cd stock-management
composer install
npm install
npm run build
copy .env.example as .env
php artisan key:generate
sqlite3 database/database.sqlite ".databases"
php artisan migrate:fresh --seed
php artisan make:filament-user
php artisan serve
```
Admin Panel: http://localhost:8000/admin

Shop Login: http://localhost:8000/login

Create shops using tinker: ```php artisan tinker``` and then pasting:
```
\App\Models\Shop::create([
    'name' => 'Shop Name',
    'email' => 'shop@example.com',
    'password' => bcrypt('password')
]);
```
Managing Stock:
1. Log in to admin panel
2. Add inventory items through the Items resource
3. Manage stock requests through the Stock Requests resource

Shop Operations:
1. Log in as shop
2. Create new stock requests
3. View request status and allocated quantities
