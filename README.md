# Laravel Test 1

1. Как запустить:

* docker-compose up -d --build
* docker-compose exec app composer install
* docker-compose exec app php artisan migrate

2. Запуск тестов и линтеров:

* docker-compose exec app ./vendor/bin/phpunit
* docker-compose exec app ./vendor/bin/phpstan analyse
* docker-compose exec app ./vendor/bin/pint

3. Что улучшить в продакшене:

* Добавить проверку на упавшие джобсы.
* Добавить ограничение частоты запросов на API.
* Использовать DB Transactions при сохранении отчетов, если логика усложнится.
