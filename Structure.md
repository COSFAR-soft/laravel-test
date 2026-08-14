# ПРОЕКТ: LARAVEL TEST SYSTEM

## ОПИСАНИЕ ПРОЕКТА
- **Название:** Laravel Test System
- **Версия Laravel:** 10.x
- **БД:** PostgreSQL 15
- **Фронтенд:** Bootstrap 5, Vite, jQuery
- **Контейнеризация:** Docker (Laravel Sail)
- **Тесты:** PHPUnit
- **Локализация:** Русский (ru)

---

## АУТЕНТИФИКАЦИЯ

### Sanctum
- Пакет: laravel/sanctum
- Используется для API-аутентификации
- Токены создаются через createToken('api-token')

### Middleware
- `auth:sanctum` — защита API-маршрутов
- `admin` — проверка роли админа (email: admin@example.com)

### Роли
- Админ: email = admin@example.com
- Обычный пользователь: все остальные

### Маршруты API
- POST /api/login → возвращает токен + данные пользователя
- GET /api/user → текущий пользователь (требует токен)

---

## КЛЮЧЕВЫЕ ОСОБЕННОСТИ

### Подсчет результатов
- `score` в TestResult хранит ПРОЦЕНТЫ (0-100), а не баллы
- `earned_points` — заработанные баллы (вычисляется по ответам)
- Проходной балл: passing_score из теста

### Множественный выбор
- Тип вопроса: `multiple`
- Правильными считаются ТОЛЬКО все выбранные варианты
- Частично правильный ответ = 0 баллов

### Авто-завершение
- При истечении времени (time_limit) тест завершается автоматически
- Используются сохраненные ответы пользователя
- Результат сохраняется с пометкой "Время вышло"

### Конструктор тестов (админка)
- Drag-and-drop порядок вопросов (SortableJS)
- AJAX-сохранение через QuestionController
- Два типа вопросов: single, multiple

---

## ФРОНТЕНД

### Сборка
- Vite + Laravel Plugin
- HMR работает на порту 5173

### Стили
- Bootstrap 5 (через Sass)
- Bootstrap Icons
- Кастомные стили в resources/sass/app.scss

### JavaScript
- jQuery (глобально через window.$)
- Axios (для AJAX)
- Alpine.js (для интерактивности)
- Vite для HMR

### Компоненты Blade
- x-app-layout — основной layout для авторизованных
- x-guest-layout — для гостевых страниц
- admin.layouts.admin — для админки

---

## ЗАВИСИМОСТИ (ключевые)

### PHP
- laravel/framework ^10.0
- laravel/sanctum ^3.3
- laravel/breeze ^1.9 (только для auth)
- phpunit/phpunit ^10.0

### NPM
- vite ^5.4.0
- bootstrap ^5.3.8
- jquery ^4.0.0
- alpinejs ^3.4.2
- laravel-vite-plugin ^1.3.0
- sass ^1.100.0

---

## СТРУКТУРА ФАЙЛОВ (КЛЮЧЕВЫЕ)
```text
laravel-test/
├── app/
│ ├── Http/
│ │ ├── Controllers/
│ │ │ ├── Admin/
│ │ │ │ ├── DashboardController.php
│ │ │ │ ├── TestController.php
│ │ │ │ ├── QuestionController.php
│ │ │ │ └── UserController.php
│ │ │ ├── Auth/
│ │ │ │ ├── AuthenticatedSessionController.php
│ │ │ │ ├── ConfirmablePasswordController.php
│ │ │ │ ├── EmailVerificationNotificationController.php
│ │ │ │ ├── EmailVerificationPromptController.php
│ │ │ │ ├── NewPasswordController.php
│ │ │ │ ├── PasswordResetLinkController.php
│ │ │ │ ├── RegisteredUserController.php
│ │ │ │ └── VerifyEmailController.php
│ │ │ ├── Test/
│ │ │ │ └── TestController.php
│ │ │ └── DashboardController.php
│ │ ├── Middleware/
│ │ │ └── AdminMiddleware.php
│ │ └── Requests/
│ │ └── Auth/
│ │ └── LoginRequest.php
│ └── Models/
│ ├── Answer.php
│ ├── Question.php
│ ├── Test.php
│ ├── TestResult.php
│ └── User.php
│
├── database/
│ ├── migrations/
│ │ ├── 2014_10_12_000000_create_users_table.php
│ │ ├── 2019_12_14_000001_create_personal_access_tokens_table.php
│ │ ├── 2025_01_01_000000_create_tests_table.php
│ │ ├── 2025_01_01_000001_create_questions_table.php
│ │ ├── 2025_01_01_000002_create_answers_table.php
│ │ └── 2025_01_01_000003_create_test_results_table.php
│ └── seeders/
│ ├── DatabaseSeeder.php
│ ├── AdminUserSeeder.php
│ ├── TestSeeder.php
│ └── UserResultSeeder.php
│
├── resources/
│ ├── views/
│ │ ├── layouts/
│ │ │ ├── app.blade.php
│ │ │ ├── guest.blade.php
│ │ │ └── navigation.blade.php
│ │ ├── admin/
│ │ │ ├── layouts/
│ │ │ │ └── admin.blade.php
│ │ │ ├── dashboard/
│ │ │ │ ├── index.blade.php
│ │ │ │ ├── test-stats.blade.php
│ │ │ │ ├── user-stats.blade.php
│ │ │ │ └── result-view.blade.php
│ │ │ ├── questions/
│ │ │ │ ├── single-choice.blade.php
│ │ │ │ └── multiple-choice.blade.php
│ │ │ └── tests/
│ │ │ ├── index.blade.php
│ │ │ ├── create.blade.php
│ │ │ ├── edit.blade.php
│ │ │ └── constructor.blade.php
│ │ ├── auth/
│ │ │ ├── login.blade.php
│ │ │ ├── register.blade.php
│ │ │ ├── forgot-password.blade.php
│ │ │ ├── reset-password.blade.php
│ │ │ ├── confirm-password.blade.php
│ │ │ └── verify-email.blade.php
│ │ ├── components/
│ │ │ ├── tests/
│ │ │ │ ├── progress-panel.blade.php
│ │ │ │ ├── question-card.blade.php
│ │ │ │ ├── test-card.blade.php
│ │ │ │ ├── timer.blade.php
│ │ │ │ └── results/
│ │ │ │ ├── actions.blade.php
│ │ │ │ ├── answer-details.blade.php
│ │ │ │ ├── result-header.blade.php
│ │ │ │ └── result-stats.blade.php
│ │ │ └── ui/
│ │ │ ├── alert.blade.php
│ │ │ ├── button.blade.php
│ │ │ ├── stats-card.blade.php
│ │ │ └── stats-row.blade.php
│ │ ├── tests/
│ │ │ ├── index.blade.php
│ │ │ ├── show.blade.php
│ │ │ ├── take.blade.php
│ │ │ ├── results.blade.php
│ │ │ └── history.blade.php
│ │ ├── dashboard.blade.php
│ │ └── welcome.blade.php
│ ├── js/
│ │ └── app.js
│ └── sass/
│ └── app.scss
│
├── routes/
│ ├── web.php
│ ├── api.php
│ ├── auth.php
│ └── admin.php
│
├── tests/
│ ├── Feature/
│ │ ├── Admin/
│ │ │ ├── DashboardTest.php
│ │ │ ├── TestManagementTest.php
│ │ │ ├── QuestionManagementTest.php
│ │ │ └── UserManagementTest.php
│ │ ├── Auth/
│ │ │ ├── AuthenticationTest.php
│ │ │ ├── EmailVerificationTest.php
│ │ │ ├── PasswordConfirmationTest.php
│ │ │ ├── PasswordResetTest.php
│ │ │ └── RegistrationTest.php
│ │ ├── Test/
│ │ │ ├── TestAPITest.php
│ │ │ ├── TestCalculationTest.php
│ │ │ ├── TestControllerTest.php
│ │ │ └── TestPageTest.php
│ │ ├── ApiAuthTest.php
│ │ └── PageRenderTest.php
│ └── Unit/
│ └── ModelsTest.php
│
├── lang/
│ └── ru/
│ ├── auth.php
│ ├── passwords.php
│ ├── pagination.php
│ └── validation.php
│
├── public/
│ ├── favicon.ico
│ └── robots.txt
│
├── docker-compose.yml
├── .env.example
├── setup.bat
├── package.json
└── composer.json
```

---

## СТРУКТУРА БАЗЫ ДАННЫХ

### Таблица: `users`
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| name | string | Имя |
| email | string | Email |
| password | string | Хеш пароля |
| email_verified_at | timestamp | Верификация |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### Таблица: `tests`
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| title | string | Название теста |
| description | text | Описание |
| time_limit | integer | Время в минутах |
| passing_score | integer | Проходной балл % |
| is_published | boolean | Опубликован |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### Таблица: `questions`
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| test_id | bigint | FK → tests.id |
| question_text | text | Текст вопроса |
| type | enum | `single` или `multiple` |
| points | integer | Баллы за вопрос |
| order | integer | Порядок |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### Таблица: `answers`
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| question_id | bigint | FK → questions.id |
| answer_text | string | Текст ответа |
| is_correct | boolean | Правильный? |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### Таблица: `test_results`
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| user_id | bigint | FK → users.id |
| test_id | bigint | FK → tests.id |
| score | integer | Процент (0-100) |
| total_questions | integer | Всего вопросов |
| correct_answers | integer | Правильных ответов |
| answers | json | Ответы пользователя |
| started_at | timestamp | Время начала |
| completed_at | timestamp | Время завершения |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### Таблица: `personal_access_tokens`
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| tokenable_type | string | Модель |
| tokenable_id | bigint | FK → users.id |
| name | string | Название токена |
| token | string | Токен |
| abilities | json | Права |
| last_used_at | timestamp | |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## КОНТРОЛЛЕРЫ И ИХ МЕТОДЫ

### 1. `App\Http\Controllers\Test\TestController`
Публичные методы для прохождения тестов пользователями.

| Метод | URL | Метод HTTP | Назначение |
|-------|-----|------------|------------|
| `index()` | `/tests` | GET | Список доступных тестов |
| `show(Test $test)` | `/tests/{test}` | GET | Детали теста |
| `start(Test $test)` | `/tests/{test}/start` | GET | Начать тест |
| `take(Test $test)` | `/tests/{test}/take` | GET | Страница прохождения |
| `submit(Request $request, Test $test)` | `/tests/{test}/submit` | POST | Отправить ответы |
| `results(Test $test)` | `/tests/{test}/results` | GET | Результаты |
| `history()` | `/history` | GET | История прохождений |
| `autoSubmit()` | — | private | Авто-завершение при истечении времени |

---

### 2. `App\Http\Controllers\Admin\TestController`
Управление тестами (админка).

| Метод | URL | Метод HTTP | Назначение |
|-------|-----|------------|------------|
| `index()` | `/admin/tests` | GET | Список тестов |
| `create()` | `/admin/tests/create` | GET | Форма создания |
| `store(Request $request)` | `/admin/tests` | POST | Создание теста |
| `edit(Test $test)` | `/admin/tests/{test}/edit` | GET | Форма редактирования |
| `update(Request $request, Test $test)` | `/admin/tests/{test}` | PUT | Обновление теста |
| `destroy(Test $test)` | `/admin/tests/{test}` | DELETE | Удаление теста |
| `constructor(Test $test)` | `/admin/tests/{test}/constructor` | GET | Конструктор вопросов |

---

### 3. `App\Http\Controllers\Admin\QuestionController`
Управление вопросами (админка, AJAX).

| Метод | URL | Метод HTTP | Назначение |
|-------|-----|------------|------------|
| `index(Test $test)` | `/admin/tests/{test}/questions` | GET | Список вопросов (JSON) |
| `store(Request $request, Test $test)` | `/admin/tests/{test}/questions` | POST | Создать вопрос (JSON) |
| `update(Request $request, Question $question)` | `/admin/questions/{question}` | PUT | Обновить вопрос (JSON) |
| `destroy(Question $question)` | `/admin/questions/{question}` | DELETE | Удалить вопрос (JSON) |
| `reorder(Request $request)` | `/admin/questions/reorder` | POST | Изменить порядок (JSON) |
| `partial(Request $request)` | `/admin/questions/partial` | POST | Загрузить HTML-часть редактора (JSON) |

---

### 4. `App\Http\Controllers\Admin\DashboardController`
Статистика админ-панели.

| Метод | URL | Метод HTTP | Назначение |
|-------|-----|------------|------------|
| `index()` | `/admin/dashboard` | GET | Дашборд со статистикой |
| `testStats(Test $test)` | `/admin/dashboard/test/{test}` | GET | Статистика по тесту |
| `viewResult(TestResult $result)` | `/admin/result/{result}` | GET | Просмотр результата |
| `apiStatistics()` | `/api/admin/statistics` | GET | API статистика |
| `apiTestStats(Test $test)` | `/api/admin/tests/{test}/statistics` | GET | API статистика по тесту |

---

### 5. `App\Http\Controllers\Admin\UserController`
Управление пользователями (админка).

| Метод | URL | Метод HTTP | Назначение |
|-------|-----|------------|------------|
| `index(Request $request)` | `/admin/users` | GET | Список пользователей (поиск, сортировка) |
| `show(User $user)` | `/admin/users/{user}` | GET | Профиль пользователя с историей |

---

### 6. `App\Http\Controllers\DashboardController`
Дашборд обычного пользователя.

| Метод | URL | Метод HTTP | Назначение |
|-------|-----|------------|------------|
| `index()` | `/dashboard` | GET | Статистика пользователя |

---

## ФАЙЛЫ МАРШРУТОВ

### `routes/web.php`
- `GET /` → Главная
- `GET /dashboard` → Дашборд
- `GET /tests` → Список тестов
- `GET /tests/{test}` → Детали теста
- `GET /tests/{test}/start` → Начать тест
- `GET /tests/{test}/take` → Прохождение
- `POST /tests/{test}/submit` → Отправить ответы
- `GET /tests/{test}/results` → Результаты
- `GET /history` → История

### `routes/admin.php`
- `GET /admin/dashboard` → Админ-дашборд
- `GET /admin/tests` → Список тестов
- `GET /admin/tests/create` → Создание теста
- `POST /admin/tests` → Сохранить тест
- `GET /admin/tests/{test}/edit` → Редактировать тест
- `PUT /admin/tests/{test}` → Обновить тест
- `DELETE /admin/tests/{test}` → Удалить тест
- `GET /admin/tests/{test}/constructor` → Конструктор
- `GET /admin/users` → Список пользователей
- `GET /admin/users/{user}` → Профиль пользователя
- `GET /admin/dashboard/test/{test}` → Статистика по тесту
- `GET /admin/result/{result}` → Просмотр результата
- `GET /admin/tests/{test}/questions` → Список вопросов (AJAX)
- `POST /admin/tests/{test}/questions` → Создать вопрос (AJAX)
- `PUT /admin/questions/{question}` → Обновить вопрос (AJAX)
- `DELETE /admin/questions/{question}` → Удалить вопрос (AJAX)
- `POST /admin/questions/reorder` → Изменить порядок (AJAX)
- `POST /admin/questions/partial` → Загрузить редактор (AJAX)

### `routes/api.php`
- `POST /api/login` → Логин (токен)
- `GET /api/user` → Текущий пользователь
- `GET /api/tests` → Список тестов
- `GET /api/tests/{test}` → Детали теста
- `POST /api/tests/{test}/start` → Начать тест
- `GET /api/tests/{test}/questions` → Вопросы
- `POST /api/tests/{test}/submit` → Отправить ответы
- `GET /api/tests/{test}/results` → Результаты
- `GET /api/tests/history` → История
- `GET /api/tests/statistics` → Статистика

---

## ТЕСТЫ

### `tests/Feature/Admin/`
| Файл | Назначение |
|------|------------|
| `DashboardTest.php` | Статистика админа |
| `TestManagementTest.php` | CRUD тестов |
| `QuestionManagementTest.php` | CRUD вопросов |
| `UserManagementTest.php` | Управление пользователями |

### `tests/Feature/Auth/`
| Файл | Назначение |
|------|------------|
| `AuthenticationTest.php` | Логин/логаут |
| `EmailVerificationTest.php` | Верификация email |
| `PasswordConfirmationTest.php` | Подтверждение пароля |
| `PasswordResetTest.php` | Сброс пароля |
| `RegistrationTest.php` | Регистрация |

### `tests/Feature/Test/`
| Файл | Назначение |
|------|------------|
| `TestAPITest.php` | API тестов |
| `TestCalculationTest.php` | Подсчёт баллов |
| `TestControllerTest.php` | AutoSubmit, множественный выбор |
| `TestPageTest.php` | Страницы тестов |

### `tests/Feature/`
| Файл | Назначение |
|------|------------|
| `ApiAuthTest.php` | API аутентификация |
| `PageRenderTest.php` | Рендеринг страниц |

### `tests/Unit/`
| Файл | Назначение |
|------|------------|
| `ModelsTest.php` | Модели, связи, атрибуты |

---

## КЛЮЧЕВЫЕ МОДЕЛИ

### `TestResult`
| Атрибут | Тип | Описание |
|---------|-----|----------|
| `percentage` | float | Процент правильных ответов |
| `score_percentage` | float | Процент набранных баллов |
| `is_passed` | bool | Статус прохождения |
| `time_spent` | int | Время в минутах |
| `earned_points` | int | Заработанные баллы |

### `Test`
| Атрибут | Тип | Описание |
|---------|-----|----------|
| `questions_count` | int | Количество вопросов |
| `total_points` | int | Сумма баллов |

### `Question`
| Атрибут | Тип | Описание |
|---------|-----|----------|
| `correct_answers` | Collection | Правильные ответы |
| `is_multiple` | bool | Множественный выбор? |

---

## СЕРВИСЫ (DOCKER)

| Сервис | Порт | Назначение |
|--------|------|------------|
| `laravel.test` | 80 | PHP + Nginx |
| `pgsql` | 5432 | PostgreSQL 15 |
| `redis` | 6379 | Кеширование |
| `mailpit` | 8025 | Тестирование писем |
| `vite` | 5173 | HMR |

---

## КОМАНДЫ ДЛЯ РАЗРАБОТКИ

```bash
# Запуск
docker-compose up -d

# Остановка
docker-compose down

# Миграции
docker-compose exec laravel.test php artisan migrate --seed

# Тесты
docker-compose exec laravel.test php artisan test

# Покрытие
docker-compose exec laravel.test php artisan test --coverage

# Vite
docker-compose exec laravel.test npm run dev

# Вход в контейнер
docker-compose exec laravel.test bash
```
## ПЕРЕМЕННЫЕ ОКРУЖЕНИЯ (.env)

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

APP_LOCALE=ru
APP_FALLBACK_LOCALE=ru
```
