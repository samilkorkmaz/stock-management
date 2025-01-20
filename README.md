# Laravel Stock Management System

A simple stock management system built with Laravel 11 and Filament, allowing shops to request items from central inventory and administrators to manage stock allocation.

## Features

- Multi-shop system with individual login
- Central inventory management
- Stock request system for shops
- Admin panel built with Filament
- Stock allocation management
- Uses SQLite for simplicity

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite

## Installation
```
git clone https://github.com/samilkorkmaz/stock-management
cd stock-management
composer install
???npm install
???npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan make:filament-user
php artisan serve
```
Admin Panel: http://localhost:8000/admin

Shop Login: http://localhost:8000/login

Create shops through the admin panel or using tinker: php artisan tinker
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

Key files and their purposes:

1. **Database Files**
   - Migrations define the database structure
   - SQLite database file stores all data

2. **Models**
   - `Shop.php`: Handles shop authentication and relationships
   - `Item.php`: Manages inventory items
   - `StockRequest.php`: Handles stock requests from shops

3. **Controllers**
   - `StockRequestController.php`: Manages shop stock request operations

4. **Filament Admin**
   - `AdminPanelProvider.php`: Configures the admin panel
   - `ItemResource.php`: Manages inventory in admin panel
   - `StockRequestResource.php`: Manages stock requests in admin panel

5. **Views**
   - `index.blade.php`: Shows shop's stock requests
   - `create.blade.php`: Form for creating new stock requests

6. **Configuration**
   - Modified `auth.php` for shop authentication
   - `.env` configured for SQLite

7. **Routes**
   - Modified `web.php` with shop and stock request routes
