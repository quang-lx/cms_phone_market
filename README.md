 

## install laravel
```
composer install
php artisan key:generate
php artisan jwt:secret

```
## create db & set database in .env

 ### install backend theme - node v10
 
```
cd themes/backend
npm install 
npm run dev
```
 
## create admin user username: admin, password : 1
 
```
 php artisan admin:create-root-user admin 1
```
 

### Sinh code CRUD 
```
php artisan module:entity:scaffold Post Admin

- tạo thêm transformer trong: Modules/Admin/Htp/Transformers 
- khai báo router cho laravel trong /Modules/Admin/Routes/admin, api.php lưu ý permission dùng  https://github.com/spatie/laravel-permission

Vue dùng https://element.eleme.io/#/en-US/component/table

vue js vao folder themes/backend/
vue router themes/backend/resources/js/routers
vue component themes/backend/resources/js/components
file đã ngôn ngữ : themes/backend/lang/vi/

```
