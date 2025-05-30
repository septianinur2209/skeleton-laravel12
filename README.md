<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Skeleton Laravel 12

**Skeleton Laravel 12** is a minimal starter kit for developing Laravel applications using version 12. This project provides a clean foundation to quickly start building scalable and modern web applications using Laravel's robust ecosystem.

Features include:

- Pre-configured Laravel 12 structure
- Environment and config setup out-of-the-box
- Vite-based asset pipeline for modern frontend
- Composer dependencies included
- Ready for migrations, seeding, and testing

## Installation

1. Clone the repository:

```bash
git clone https://github.com/septianinur2209/skeleton-laravel12.git
cd skeleton-laravel12
```

2. Install PHP dependencies:

```bash
composer install
```

3. Copy `.env` file and generate key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations and seeder:

```bash
php artisan migrate --seed
```

5. Start the development server:

```bash
php artisan serve
```

## Learning Laravel

For extensive documentation, tutorials, and guides, visit the official Laravel resources:

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Bootcamp](https://bootcamp.laravel.com)
- [Laracasts](https://laracasts.com)

## Contributing

Feel free to fork and submit pull requests. Issues and suggestions are welcome.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
