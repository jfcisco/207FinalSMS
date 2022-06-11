# CMSC 207 Final - Tawk

Widget-based chat application made by team SMS.

## Required Software
- [PHP 8.0 or later](https://www.apachefriends.org/index.html)
- [PHP MongoDB Driver (latest)](https://pecl.php.net/package/mongodb)
- [Node.js 16 LTS](https://nodejs.org/en/download/)
- [Composer 2.3.5](https://getcomposer.org/download/)

## How to Run the Application
1. Clone this repository
2. Open your terminal or command prompt. The succeeding commands must be run in it.
3. Run `cd <path-to-the-repository-directory>` to set the repository as your current directory.
4. Run `npm install`
5. Run `composer install`
6. Run `cp .env.example .env` and configure your database in .env file (i.e., set the variables that start with `DB_`).
7. Make the following edits to the .env file:
    ```
    APP_URL=http://localhost:8000

    DB_CONNECTION=mongodb
    DB_PORT=27017
    DB_DSN=<see MongoDB Local Setup Document>

    MIX_SOCKET_SERVER=http://localhost:3000
    ```
    - If you are using a different domain or port, change the APP_URL to them.
    - The MongoDB Local Setup document can be found in our shared Google Drive.
    - If you did this step correctly, there should be a `.env` file and a `.env.example` file inside your 207FinalSMS folder.
8. Run `php artisan key:generate` to generate the application key.
9. Run `php artisan storage:link` to links to the images folder
10. Run `npm run watch` and wait for Laravel Mix to finish building. Keep this terminal or command prompt window open.
11. Open another terminal or command prompt window and run `php artisan serve`
12. Open your web browser and navigate to http://localhost:8000.

## Group Members
- Adrian Mark Arguelles
- Earl Dahildahil
- Charmaine Ebueng
- Leah Felismino
- Jasper Francisco
- Raymond Halim
- Karla Malla
- Ramel Nandwani
- Jalton Jan Richa
- Enrique Rafhael Sanchez
- Alyanna Seanard
- Dennis Vila

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
