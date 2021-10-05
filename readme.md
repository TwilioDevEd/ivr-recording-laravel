# IVR Phone Tree: IVR, call screening and recording for beginners. powered by Twilio - Laravel

An example application implementing an automated phone line using
Twilio and Laravel.

[Read the full tutorial](https://www.twilio.com/docs/tutorials/walkthrough/ivr-screening/php/laravel)!

[![Build Status](https://travis-ci.org/TwilioDevEd/ivr-recording-laravel.svg?branch=master)](https://travis-ci.org/TwilioDevEd/ivr-recording-laravel)

## Run the application

1. Clone the repository and `cd` into it.
1. Install the application's dependencies with [Composer](https://getcomposer.org/)

   ```bash
   composer install
   ```
1. The application uses PostgreSQL as the persistence layer. If you
   don't have it already, you should install it. The easiest way is by
   using [Postgres.app](http://postgresapp.com/).
1. Create a database.

   ```bash
   createdb ivr_recording
   ```
1. Copy the sample configuration file and edit it to match your configuration.

    ```bash
    cp .env.example .env
    ```

   You'll need to set `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, and
   `DB_PASSWORD`. You can often leave `DB_USERNAME` and `DB_PASSWORD`
   empty. `DB_HOST` should be `localhost` if you're running the DB in
   your own machine.
1. Generate an `APP_KEY`:

   ```bash
   php artisan key:generate
   ```
1. Run the migrations:

   ```bash
   php artisan migrate
   ```
1. Load seed data:

   ```bash
   php artisan db:seed
   ```
1. Run the application using Artisan.

   ```bash
   php artisan serve
   ```
1. Expose the application to the wider Internet using [ngrok](https://ngrok.com/)

   ```bash
   ngrok http 8000
   ```
1. Provision a number under the
   [Manage Numbers page](https://www.twilio.com/user/account/phone-numbers/incoming)
   on your account. Set the voice URL for the number to
   `http://<your-ngrok-subdomain>.ngrok.io/ivr/welcome`.
1. Grab your phone and call your newly-provisioned number!

## Dependencies

This application uses this Twilio helper library:
* [twilio-php](https://github.com/twilio/twilio-php)

## Run the tests

1. Configure a test database in `.env.test`.
1. Run the database migrations for the test database
   ```bash
   APP_ENV=testing php artisan migrate
   ```

1. Run at the top-level directory:

   ```bash
   phpunit --coverage-text
   ```

If your PHP installation doesn't have `xdebug` support then simply run
the tests without coverage reporting:

```bash
phpunit
```
