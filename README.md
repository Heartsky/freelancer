========= setup ==========
```
run PHP version 7.3
laravel 8.0
maria db any
```
1. clone source from http://gitlab.eplatform.vn/sonthanh/acount.git
2. run install composer: ``` composer install ```
3. clone file .env.example to .env
4. run key: ``` php artisan key:generate ```
5. fill database infor

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

6. run migrate db: ``` php artisan migrate ```
7. run db default: ``` php artisan db:seed ```