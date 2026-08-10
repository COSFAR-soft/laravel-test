@echo off
title Laravel Test System Setup
echo ========================================
echo Laravel Test System - Установка
echo ========================================
echo.

:: Проверка Docker
echo [1/7] Проверка Docker...
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ОШИБКА] Docker не найден. Установите Docker Desktop.
    echo Скачать: https://www.docker.com/products/docker-desktop/
    pause
    exit /b 1
)
echo [OK] Docker найден
echo.

:: Проверка Composer
echo [2/7] Проверка Composer...
composer --version >nul 2>&1
if errorlevel 1 (
    echo [ОШИБКА] Composer не найден. Установите Composer.
    echo Скачать: https://getcomposer.org/download/
    pause
    exit /b 1
)
echo [OK] Composer найден
echo.

:: Проверка PHP
echo [3/7] Проверка PHP...
php --version >nul 2>&1
if errorlevel 1 (
    echo [ПРЕДУПРЕЖДЕНИЕ] PHP не найден в PATH, но проект может работать через Docker.
)
echo.

:: Установка зависимостей PHP
echo [4/7] Установка PHP зависимостей...
composer install --no-interaction
if errorlevel 1 (
    echo [ОШИБКА] Ошибка установки PHP зависимостей
    pause
    exit /b 1
)
echo [OK] PHP зависимости установлены
echo.

:: Установка зависимостей NPM
echo [5/7] Установка NPM зависимостей...
call npm install
if errorlevel 1 (
    echo [ОШИБКА] Ошибка установки NPM зависимостей
    pause
    exit /b 1
)
echo [OK] NPM зависимости установлены
echo.

:: Настройка .env
echo [6/7] Настройка окружения...
if not exist .env (
    echo Копирование .env.example -> .env
    copy .env.example .env
)
echo Генерация APP_KEY...
php artisan key:generate
if errorlevel 1 (
    echo [ОШИБКА] Ошибка генерации ключа
    pause
    exit /b 1
)
echo [OK] Округление настроено
echo.

:: Запуск Docker
echo [7/7] Запуск Docker контейнеров...
docker-compose up -d
if errorlevel 1 (
    echo [ОШИБКА] Ошибка запуска Docker
    pause
    exit /b 1
)
echo [OK] Docker контейнеры запущены
echo.

:: Миграции и сиды
echo.
echo [ДОПОЛНИТЕЛЬНО] Выполнение миграций и сидов...
docker-compose exec laravel.test php artisan migrate --seed
if errorlevel 1 (
    echo [ОШИБКА] Ошибка миграций
    pause
    exit /b 1
)
echo [OK] Миграции и сиды выполнены
echo.

:: Сборка фронтенда
echo.
echo [ДОПОЛНИТЕЛЬНО] Сборка фронтенда...
docker-compose exec laravel.test npm run build
if errorlevel 1 (
    echo [ПРЕДУПРЕЖДЕНИЕ] Ошибка сборки фронтенда
    echo Попробуйте запустить вручную: docker-compose exec laravel.test npm run build
)
echo.

:: Запуск Vite (в отдельном окне)
echo.
echo [ДОПОЛНИТЕЛЬНО] Запуск Vite (в новом окне)...
start "Vite Dev Server" cmd /c "docker-compose exec laravel.test npm run dev"
echo.

echo ========================================
echo УСТАНОВКА ЗАВЕРШЕНА!
echo ========================================
echo.
echo Сайт: http://localhost
echo Mailpit: http://localhost:8025
echo.
echo Вход:
echo   Email: admin@example.com
echo   Пароль: password
echo.
echo Чтобы остановить проект:
echo   docker-compose down
echo.
pause
