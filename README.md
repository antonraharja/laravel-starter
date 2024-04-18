# Laravel Starter

## About

This project contains Laravel 10, Filament 3 and codes serving features such as Role and Permission management, User management and API Tokens managements.

## Current Features

- Login with username or email
- Permission management
- Role management
- Assign multiple roles to user
- API token management
- General settings skel

## Installation

1. Get from github
   ```
   git clone https://github.com/antonraharja/laravel-starter starter`
   ```

2. Enter installation path and edit `.env`
   ```
   cd starter
   cp .env.example .env
   nano .env
   ```

3. Important, one-time during first installation only, create table **registries**
   ```
   mysql -uYourDatabaseUser -p YourDatabaseName < database/init.sql
   ```
   Change **YourDatabaseUser** and **YourDatabaseName** above according to your database setup

4. Install composer packages
   ```
   composer install
   ```

5. Generate key
   ```
   php artisan key:generate
   ```

6. Refresh databas table installation and setup admin's password
   ```
   php artisan migrate:fresh --seed
   ```

7. Depend on your installation you may need this
   ```
   sudo chmod -R 777 storage/logs/
   sudo chmod -R 777 storage/framework/*
   ```

## Screenshots

![Permission List](contribs/screenshots/1_permission_list_dark.png?raw=1 "Permission List")

![Create API Token](contribs/screenshots/3_api_token_create.png?raw=1 "Creare API Token")

![Edit Role](contribs/screenshots/2_role_edit.png?raw=1 "Edit Role")

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to Anton Raharja via [araharja@pm.me](mailto:araharja@pm.me). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
