Quick Start
------------
Simple steps

      1. intsall php                    (if you dont have one)
      2. rename path in php.ini
      3. php composer.phar install      download packages
      4. rename .env.example -> .env
      5. php artisan key:generate       generate key
      6. Create db and change name to it in .env
      DB_DATABASE=
      DB_USERNAME=
      DB_PASSWORD=
      And input aws keys
      AWS_ACCESS_KEY_ID=
      AWS_SECRET_ACCESS_KEY=
      AWS_DEFAULT_REGION=
      AWS_BUCKET=
      7. php artisan migrate            make migration
      8. php artisan serve              start serve
