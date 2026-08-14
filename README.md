<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

# Laravel Test System

Платформа для проведения тестирования знаний по Laravel с системой баллов и админ-панелью для управления тестами.

---

## О проекте

Платформа для тестирования знаний по Laravel. Пользователи проходят тесты с вопросами разных типов, получают баллы и
отслеживают прогресс. Администраторы управляют тестами и вопросами через удобную админ-панель с конструктором.

---

## Стек технологий

### Backend

| Технология      | Версия | Назначение                   |
|-----------------|--------|------------------------------|
| PHP             | 8.2+   | Язык программирования        |
| Laravel         | 10.x   | Фреймворк                    |
| PostgreSQL      | 15.x   | База данных                  |
| Laravel Sanctum | 3.x    | API аутентификация           |
| Redis           | -      | Кеширование, очереди, сессии |

### Frontend

| Технология      | Версия | Назначение            |
|-----------------|--------|-----------------------|
| Bootstrap       | 5.3    | CSS-фреймворк         |
| jQuery          | 4.x    | JavaScript библиотека |
| Vite            | -      | Сборка assets         |
| SortableJS      | -      | Drag-and-drop         |
| Bootstrap Icons | 1.11   | Иконки                |

### Инфраструктура

| Компонент             | Назначение            |
|-----------------------|-----------------------|
| Docker / Laravel Sail | Контейнеризация       |
| Redis                 | Кеширование и очереди |
| Mailpit               | Отладка почты         |

### Требования

| Компонент      | Минимальная версия |
|----------------|--------------------|
| Docker         | 20.10+             |
| Docker Compose | 2.0+               |
| PHP            | 8.2+               |
| Composer       | 2.0+               |
| Node.js        | 18+                |
| NPM            | 8+                 |

---

## Установка

```bash
# 1. Клонировать репозиторий
git clone git@github.com:COSFAR-soft/laravel-test.git
cd laravel-test

# 2. Скопировать .env
cp .env.example .env

# 3. Запустить контейнеры
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

# 7. Запустить Vite
./vendor/bin/sail npm run dev
#или
docker-compose exec laravel.test npm run dev

# Для покрытия тестами
./vendor/bin/sail artisan test --coverage
# Требуется Xdebug (уже настроен в .env: SAIL_XDEBUG_MODE=coverage)
```

## Настройка

```env
APP_NAME="Laravel Test"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_LOCALE=ru
APP_FALLBACK_LOCALE=ru

# База данных
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# Redis (кеширование, очереди, сессии)
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mailpit (почта)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="laraveltest@example.com"
MAIL_FROM_NAME="${APP_NAME}"

```

## Администратор

По умолчанию создается администратор:

Email: admin@example.com
Пароль: password

## Тестовый пользователь

Email:    test@example.com
Пароль:    password

## Функциональность

Для пользователей

| Страница          | URL                   | Описание                      |
|-------------------|-----------------------|-------------------------------|
| Список тестов     | `/tests`              | Все доступные тесты           |
| Страница теста    | `/tests/{id}`         | Информация о тесте            |
| Прохождение теста | `/tests/{id}/take`    | Вопросы                       |
| Результаты        | `/tests/{id}/results` | Результаты                    |
| История           | `/history`            | Все прохождения пользователя  |
| Статистика        | `/dashboard`          | Общая статистика пользователя |

Для администратора

| Страница             | URL                             | Описание                       |
|----------------------|---------------------------------|--------------------------------|
| Дашборд              | `/admin/dashboard`              | Общая статистика по платформе  |                                 |                                |
| Управление тестами   | `/admin/tests`                  | Список всех тестов             |
| Создание теста       | `/admin/tests/create`           | Форма создания                 |
| Редактирование теста | `/admin/tests/{id}/edit`        | Изменение Теста и публикация   |
| Конструктор          | `/admin/tests/{id}/constructor` | Конструктор вопросов           |
| Пользователи         | `/admin/users`                  | Список пользователей с поиском |

API

| Метод | URL                         | Описание                        |
|-------|-----------------------------|---------------------------------|
| POST  | `/api/login`                | Получение токена аутентификации |
| GET   | `/api/user`                 | Текущий пользователь            |                
| GET   | `/api/tests`                | Список тестов                   |                      
| GET   | `/api/tests/{id}`           | Детали теста                    |                  
| POST  | `/api/tests/{id}/start`     | Начать тест                     |             
| GET   | `/api/tests/{id}/questions` | Получить вопросы                |    
| POST  | `/api/tests/{id}/submit`    | Отправить ответы                |       
| GET   | `/api/tests/{id}/results`   | Получить результаты             |   
| GET   | `/api/tests/history`        | История пользователя            |       
| GET   | `/api/tests/statistics`     | Статистика пользователя         | 

## Тестирование

```
# Запустить все тесты
./vendor/bin/sail artisan test
# или
docker-compose exec laravel.test php artisan test

# Запустить с покрытием
./vendor/bin/sail artisan test --coverage
# или
docker-compose exec laravel.test php artisan test --coverage

# Конкретная папка
./vendor/bin/sail artisan test tests/Feature/Test/
# или
docker-compose exec laravel.test php artisan test tests/Feature/Test/

# Конкретный файл
./vendor/bin/sail artisan test tests/Feature/Test/TestPageTest.php
# или
docker-compose exec laravel.test php artisan test tests/Feature/Test/TestPageTest.php

```

## Структура

```text
laravel-test/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php   # Статистика админа
│   │   │   │   ├── TestController.php        # CRUD тестов
│   │   │   │   ├── QuestionController.php    # Управление вопросами (AJAX)
│   │   │   │   └── UserController.php        # Управление пользователями
│   │   │   ├── Auth/                         # Контроллеры Breeze
│   │   │   ├── Test/
│   │   │   │   └── TestController.php        # Публичные тесты
│   │   │   └── DashboardController.php       # Дашборд пользователя
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php           # Проверка роли админа
│   │   └── Requests/
│   └── Models/
│       ├── Test.php                          # Тест (связи, атрибуты)
│       ├── Question.php                      # Вопрос (связи, атрибуты)
│       ├── Answer.php                        # Ответ (связи)
│       ├── TestResult.php                    # Результат (проценты, баллы)
│       └── User.php                          # Пользователь (связи)
├── database/
│   ├── migrations/                           # Все миграции
│   └── seeders/
│       ├── AdminUserSeeder.php               # Админ и тестовый пользователь
│       ├── TestSeeder.php                    # 16 тестов с вопросами
│       └── UserResultSeeder.php              # 50 пользователей с результатами
├── resources/
│   └── views/
│       ├── admin/                            # Админ-панель
│       │   ├── dashboard/                    # Статистика
│       │   ├── questions/                    # Редакторы вопросов
│       │   └── tests/                        # CRUD и конструктор
│       ├── auth/                             # Страницы Breeze
│       ├── components/                       # Blade-компоненты
│       │   ├── tests/                        # Компоненты тестов
│       │   └── ui/                           # UI-компоненты
│       ├── tests/                            # Публичные страницы
│       ├── layouts/                          # Базовые шаблоны
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── web.php                               # Публичные маршруты
│   ├── api.php                               # API маршруты
│   ├── auth.php                              # Маршруты Breeze
│   └── admin.php                             # Маршруты админки
├── tests/
│   ├── Feature/
│   │   ├── Admin/                            # Тесты админки
│   │   ├── Auth/                             # Тесты аутентификации
│   │   ├── Test/                             # Тесты публичных тестов
│   │   ├── ApiAuthTest.php
│   │   └── PageRenderTest.php                # Тесты рендеринга страниц
│   └── Unit/
│       └── ModelsTest.php                    # Тесты моделей
├── lang/
│   └── ru/                                   # Русская локализация
├── public/
│   ├── favicon.ico
│   └── robots.txt
├── docker-compose.yml
├── .env.example
├── package.json
├── composer.json
└── README.md
```

## Структура базы данных

### Таблица users

- id (bigint, PK)
- name (string)
- email (string, unique)
- password (string)
- email_verified_at (timestamp, nullable)
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)

---

### Таблица tests

- id (bigint, PK)
- title (string)
- description (text, nullable)
- time_limit (integer) - время в минутах
- passing_score (integer) - проходной балл в процентах
- is_published (boolean)
- created_at (timestamp)
- updated_at (timestamp)

---

### Таблица questions

- id (bigint, PK)
- test_id (bigint, FK -> tests.id)
- question_text (text)
- type (enum: single, multiple)
- points (integer)
- order (integer) - порядок отображения
- created_at (timestamp)
- updated_at (timestamp)

---

### Таблица answers

- id (bigint, PK)
- question_id (bigint, FK -> questions.id)
- answer_text (string)
- is_correct (boolean)
- created_at (timestamp)
- updated_at (timestamp)

---

### Таблица test_results

- id (bigint, PK)
- user_id (bigint, FK -> users.id)
- test_id (bigint, FK -> tests.id)
- score (integer) - процент правильных ответов (0-100)
- total_questions (integer)
- correct_answers (integer)
- answers (json) - ответы пользователя
- started_at (timestamp)
- completed_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)

---

### Таблица personal_access_tokens (Sanctum)

- id (bigint, PK)
- tokenable_type (string)
- tokenable_id (bigint, FK -> users.id)
- name (string)
- token (string, unique)
- abilities (json, nullable)
- last_used_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)

---

### Связи между таблицами

```text
users
└── hasMany → test_results
└── hasMany → personal_access_tokens

tests
└── hasMany → questions
└── hasMany → test_results

questions
└── belongsTo → tests
└── hasMany → answers

answers
└── belongsTo → questions

test_results
└── belongsTo → users
└── belongsTo → tests
```

---

## Аутентификация

- Пакет: Laravel Sanctum
- API-токены через `$user->createToken('api-token')`
- Администратор определяется по email: `admin@example.com`
- Middleware:
    - `auth:sanctum` — защита API
    - `admin` — проверка роли администратора

---

### Типы вопросов

single
- Одиночный выбор
- Проверка: сравнение ID выбранного ответа с правильным

multiple
- Множественный выбор
- Проверка: массивы ID ответов сортируются, затем сравниваются строго
- Порядок выбора не имеет значения
- Частично правильный ответ (выбраны не все правильные варианты) = 0 баллов

---

### Логика подсчёта

score (проценты)
- round((correct_answers / total_questions) * 100)

earned_points (заработанные баллы)
- Рассчитывается в методе getEarnedPointsAttribute() модели TestResult
- Суммирует points за вопросы, где ответ полностью правильный
- Для multiple: 0 баллов, если выбраны не все правильные варианты

is_passed
- score >= test.passing_score

---

## Автор

**COSFAR-soft**

- GitHub: [github.com/COSFAR-soft](https://github.com/COSFAR-soft)
