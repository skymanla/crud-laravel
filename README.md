# CRUD Laravel
## laravel로 CRUD 해보기

### 환경
- laravel -> v10
- php -> 8.2
- composer -> 2.5.7
### 실행방법
```shell
# 프로젝트 폴더로 이동
cd ~/{project}/{path}
# install dependencies
composer install
# env setting
copy .env_example .env && vi .env
# db migrations
php artisan migrate
# db seed
php artisan db:seed DatabaseSeeder
# php server start
php artisan serve
```
### 사용자 인증
- 인증 방법은 `sanctum`으로 되어 있습니다(회원가입, login, logout API 구현).
