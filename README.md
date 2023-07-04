# CRUD Laravel
## laravel로 CRUD 해보기
<br/>

### 환경
- laravel -> v10
- php -> 8.2

### 실행방법
- docker 없이 작동하는 방법
```shell
cd ~/{project}/{path}
composer install
php artisan migrate
php artisan db:seeder BoardCategory

php artisan serve
```
