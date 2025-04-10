# Laravel Starter

## About

This project contains **Laravel 12**, **Filament 3.3** and codes serving features such as Role and Permission management, User management and API Tokens managements.

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
   git clone https://github.com/antonraharja/laravel-starter starter
   ```

2. Enter installation path and edit `.env`
   
   In `.env` you need to edit at least `APP_NAME` `APP_URL` and `DB_*`
   ```
   cd starter
   cp .env.example .env
   nano .env
   ```

3. Install composer packages
   ```
   composer install
   ```

4. Generate key
   ```
   php artisan key:generate
   ```

5. Install database tables with some starter contents and setup admin's password
   ```
   php artisan migrate:fresh --seed
   ```

6. Depend on your installation you may need below actions

   Adjust storage file permissions:
   ```
   sudo find storage/ bootstrap/ -type f -exec chmod 666 {} \;
   ```

   Adjust storage folder permissions:
   ```
   sudo find storage/ bootstrap/ -type d -exec chmod 777 {} \;
   ```

## Screenshots

![Permission List](contribs/screenshots/1_permission_list_dark.png?raw=1 "Permission List")

![Create API Token](contribs/screenshots/3_api_token_create.png?raw=1 "Creare API Token")

![Edit Role](contribs/screenshots/2_role_edit.png?raw=1 "Edit Role")

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to Anton Raharja via [araharja@protonmail.com](mailto:araharja@protonmail.com). All security vulnerabilities will be promptly addressed.

## License

This project is open-sourced software licensed under the [MIT license](https://github.com/antonraharja/laravel-starter/blob/master/LICENSE).

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Filament is open-sourced software licensed under the [MIT license](https://github.com/filamentphp/filament/blob/3.x/LICENSE.md).
