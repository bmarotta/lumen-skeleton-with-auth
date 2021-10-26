# Lumen PHP Skeleton with Authentication

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. 

To get a project running from scratch with authentication can take sometime. You can cut some corners by jusr forking this project.

## First steps

- Fork the repository
- Clone it
- Create an .env file and set it up with your DB information

In the repository root:
- Migrate the DB: ```php artisan migrate```
- Generate a secret key for the jwt tokens: ```php artisan jwt:secret```
- (*optional*) Run the unit tests: ```php .\vendor\phpunit\phpunit\phpunit```
- Run the application: ```php -S localhost:8000 -t public```
- Open the endpoints tester: http://localhost:8000/test-endpoints.html


## Lumen Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## License

The Lumen framework and this repository are open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
