# Laravel-api

## Установка

Клонировать и перейти в папку
```
git clone https://github.com/ardenwynn/laravel-api.git

cd laravel-api
```

Подгрузить зависимости
```
composer install
```

Создать и запустить контейнеры
```
./vendor/bin/sail up
```
Создать таблицы и заполнить базу тестовыми данными
```
./vendor/bin/sail php artisan migrate --seed
```

Для тестирование желательно закомментировать
cache middleware в ресурсных контроллерах (Article,Comment,Role).
Кэш - 10 сек.
## Доступные параметры для index и Store

### Articles

##### ?filter[param]=value   | title, content, id
##### ?sort=-param   | title, content, id
##### ?fields[articles]=param   | title, content, id
##### ?include=param   | user, comments
##### ?paginate=param

### Comments

##### ?filter[param]=value   | content, id
##### ?sort=-param   | content, id
##### ?fields[articles]=param   | content, id
##### ?include=param   | user, article
##### ?paginate=param

### Roles

##### ?filter[param]=value   | name, id
##### ?sort=-param   | name, id
##### ?fields[articles]=param   | name, id
##### ?include=param   | users
##### ?paginate=param

## Другое
Создание юзера из консоли
```
./vendor/bin/sail php artisan user:create
```

[mailhog](http://localhost:8025/)

[Postman коллекция](https://drive.google.com/file/d/1kdj1-VTjbwKdQKSUJWYXwqotFKUBJKwZ/view?usp=sharing)

[Реализованные пункты отмечены знаком +](https://drive.google.com/file/d/1xZ1u94R1C87EGavvfPU88wmcQwr6z-fW/view?usp=sharing)


