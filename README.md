<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Status-Initiated-yellow" alt="Project Status"></a>
  <a href="#"><img src="https://img.shields.io/badge/API-Laravel-blue" alt="Tech Stack"></a>
  <a href="#"><img src="https://img.shields.io/badge/License-MIT-lightgrey" alt="License"></a>
</p>

---

## 🚀 Project Overview

This is a RESTful API built with Laravel, intended for future development of an expedition system including user roles and customer data management.

---

## 🌐 Test Environment

The API can be tested at:

<p align="center">
    <a href="http://api.frdhsym.space" target="_blank">http://api.frdhsym.space</a>
</p>

---

## 📦 Tech Stack

### Core

-   PHP ^8.2
-   Laravel Framework ^11.31
-   MySQL / MariaDB

### Main Packages

-   Laravel Sanctum ^4.0
    -   API token authentication
    -   SPA authentication
    -   Mobile application authentication
-   Laravel Tinker ^2.9
    -   Interactive REPL
    -   Testing and debugging tool
-   Laravolt Indonesia ^0.36.0
    -   Indonesia territories data
    -   Provinces, cities, districts management
    -   Postal code information

### Development Tools

-   Laravel Pail ^1.1
    -   Real-time log viewer
    -   Debug logs monitoring
-   Laravel Pint ^1.13
    -   PHP code style fixer
    -   PSR-12 standards enforcement
-   Laravel Sail ^1.26
    -   Docker development environment
    -   Easy local setup
-   PHPUnit ^11.0.1
    -   Unit testing framework
-   Faker ^1.23
    -   Testing data generation
    -   Seeding dummy data

---

## 👨‍💻 Developer

Developed and maintained by:

**IT Internal**

-   <a href="https://github.com/neveleneve">Mohammad Farid Hasymi</a>
-   <a href="https://github.com/hasanicahyadi">Hasani Cahyadi</a>

## ⚙️ Installation

```bash
# Clone the repository
git clone https://github.com/sibalogistik-dev/api.git
cd api

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate

# Install and setup Laravolt Indonesia
php artisan vendor:publish --provider="Laravolt\Indonesia\ServiceProvider"
php artisan migrate --seed
php artisan laravolt:indonesia:seed

# Start the development server
php artisan serve
```

### Additional Configuration

1. Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

2. Configure Laravolt Indonesia:

```env
INDONESIA_TABLE_PREFIX=indonesia_
```

3. Configure Sanctum (if using domains):

```env
SANCTUM_STATEFUL_DOMAINS=your-domain.com
```

---

## 📄 License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
