I'm use the following versions:

Xampp = 2.4.58.0
PHP = 8.2.12
Laravel = 11.10.0
git = 2.45.0.windows.1
composer = 2.7.6
npm = 10.5.0

## How to run the proyect

Follow these steps for run this proyect successfuly.

1) You can use your preference db manage, we use XAMPP.

2) Install composer, for Windows you need to download composer for the following link: https://getcomposer.org/

3) Clone repo https://github.com/Brayan3268/placetogrow_ms.git

4) Run the comand: "Composer install"

5) Run the instruction: "cp .env.example .env"

6) Config the .env file with the next config:

DATABASE CREDENTIALS:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ms_ptw
DB_USERNAME=root
DB_PASSWORD=

PLACE TO PAY CREDENTIALS:
PLACETOPAY_LOGIN=
PLACETOPAY_SECRET_KEY=
PLACETOPAY_URL=

MAIL CREDENTIALS:
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=

7) Run the following comand if need it: "php artisan key:generate"

8) Run the command: "php artisan migrate"

9) Run the command: "php artisan migrate:fresh --seed"

10) Run the command: "npm i"

11) Run the command: "npm install"

12) Run the command: "npm run dev"

13) Run the command: "php artisan serve"

14) Run the command: "npm run dev"

15) For run the test:

Download the file from: https://xdebug.org/wizard and follow the steps

Add the following lines on your php.ini
zend_extension = xdebug
xdebug.mode=coverage

php -dxdebug.mode=coverage ./vendor/bin/phpunit --coverage-html coverage/ tests/Feature/<filename>.php