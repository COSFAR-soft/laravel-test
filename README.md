<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## Laravel Testing

Платформа для проведения тестирования знаний по Laravel с системой баллов и админ-панелью для управления тестами.
 
## О проекте
Проект представляет собой платформу для тестирования знаний по Laravel. Пользователи могут проходить тесты с вопросами разных типов, получать баллы и отслеживать свой прогресс. Администраторы управляют тестами и вопросами через удобную админ-панель с конструктором.

## Стек технологий

### Backend
| Технология | Версия | Назначение |
|------------|--------|------------|
| PHP | 8.3+ | Язык программирования |
| Laravel | 10.x | Фреймворк |
| PostgreSQL | 15.x | База данных |
| Laravel Sanctum | - | API аутентификация |

### Frontend
| Технология | Версия | Назначение |
|------------|--------|------------|
| Bootstrap | 5.3 | CSS-фреймворк |
| jQuery | 4.x | JavaScript библиотека |
| Vite | - | Сборка assets |
| SortableJS | - | Drag-and-drop |
| Bootstrap Icons | 1.11 | Иконки |

### Инфраструктура
| Компонент | Назначение |
|-----------|------------|
| Docker / Laravel Sail | Контейнеризация |
| Redis | Кеширование |
| Mailhog | Отладка почты |

### Требования

| Компонент | Минимальная версия |
|-----------|-------------------|
| Docker | 20.10+ |
| Docker Compose | 2.0+ |
| PHP | 8.3+ |
| Composer | 2.0+ |
| Node.js | 18+ |
| NPM | 8+ |
| PostgreSQL | 15+ |

##  Установка

```bash
# 1. Клонировать репозиторий
git clone git@github.com:your-username/laravel-test.git
cd laravel-test

# 2. Скопировать .env
cp .env.example .env

# 3. Запустить контейнеры (выберите один способ)
./vendor/bin/sail up -d
# или
docker-compose up -d

# 4. Сгенерировать ключ
./vendor/bin/sail artisan key:generate
# или
docker-compose exec laravel.test php artisan key:generate

# 5. Запустить миграции и сидеры
./vendor/bin/sail artisan migrate:fresh --seed
# или
docker-compose exec laravel.test php artisan migrate:fresh --seed

# 6. Установить фронтенд зависимости и собрать
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
# или
docker-compose exec laravel.test npm install
docker-compose exec laravel.test npm run build

```

## Настройка

```

APP_NAME="Laravel Test"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# База данных
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# Редис для кеша и очередей
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

```

## Администратор

По умолчанию создается администратор:

Email: admin@example.com
Password: password

## Тестовый пользователь

Email	test@example.com
Пароль	password

## Функциональность

Для пользователей

| Страница | URL | Описание                      |
|----------|-----|-------------------------------|
| Список тестов | `/tests` | Все доступные тесты           |
| Страница теста | `/tests/{id}` | Информация о тесте            |
| Прохождение теста | `/tests/{id}/take` | Вопросы                       |
| Результаты | `/tests/{id}/results` | Результаты                    |
| История | `/history` | Все прохождения пользователя  |
| Статистика | `/dashboard` | Общая статистика пользователя |

Для администратора

| Страница | URL | Описание                     |
|----------|-----|------------------------------|
| Управление тестами | `/admin/tests` | Список всех тестов           |
| Создание теста | `/admin/tests/create` | Форма создания               |
| Редактирование теста | `/admin/tests/{id}/edit` | Изменение Теста и публикация |
| Конструктор | `/admin/tests/{id}/constructor` | Конструктор вопросов         |

## Тестирование

```
# Запустить все тесты
php artisan test

# Запустить тесты конкретной папки
php artisan test tests/Feature/Test/

# Запустить конкретный файл
php artisan test tests/Feature/Test/TestPageTest.php

```

## Структура

```text
laravel-test/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── TestController.php      # Управление тестами
│   │   │   │   └── QuestionController.php   # Управление вопросами
│   │   │   └── Test/
│   │   │       └── TestController.php       # Публичная часть
│   │   └── Middleware/
│   │       └── AdminMiddleware.php          # Проверка администратора
│   └── Models/
│       ├── Test.php
│       ├── Question.php
│       ├── Answer.php
│       └── TestResult.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── AdminUserSeeder.php
│       └── TestSeeder.php
├── resources/
│   └── views/
│       ├── admin/          # Админ-панель
│       ├── tests/          # Публичные страницы
│       └── components/     # компоненты
└── routes/
    ├── web.php
    └── admin.php
```
