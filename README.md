## Test api 
#### создание заказа, добавление, редактирование и удаление товаров в заказе
###### install
-composer require zircote/swagger-php;  
-composer require "darkaonline/l5-swagger";  
-скачать репозиторий и поместить в папку с проектом с заменой файлов;  
-выполнить php artisan migrate;  
-выполнить php artisan db:seed --class=ProductsSeeder;  
-добавить в .env L5_SWAGGER_CONST_HOST=http://localhost:8000/api/v1;  
-php artisan l5-swagger:generate;  
-php artisan serve;  
- go http://127.0.0.1:8000/api/documentation;  
###### description
Тестовое задание, описание в swagger файле;  
Тесты не  делал - пока не было времени разобраться;  
Middleware не настраивал для тестов.
