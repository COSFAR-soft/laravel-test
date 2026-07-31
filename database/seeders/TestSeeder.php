<?php

namespace Database\Seeders;

use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // ТЕСТ 1: Основы Laravel (маршруты, контроллеры, общие концепции)
        // ============================================
        $test1 = Test::create([
            'title' => 'Основы Laravel',
            'description' => 'Проверьте свои знания основ фреймворка Laravel. Вопросы охватывают маршруты, контроллеры, Blade, Eloquent и миграции.',
            'time_limit' => 15,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $q1 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какой метод используется для определения GET-маршрута в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => 'Route::get()', 'is_correct' => true]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => 'Route::post()', 'is_correct' => false]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => 'Route::any()', 'is_correct' => false]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => 'Route::delete()', 'is_correct' => false]);

        $q2 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какая команда создает новый контроллер в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => 'php artisan make:controller', 'is_correct' => true]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => 'php artisan create:controller', 'is_correct' => false]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => 'php artisan new:controller', 'is_correct' => false]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => 'php artisan controller:make', 'is_correct' => false]);

        $q3 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какой файл содержит маршруты веб-приложения?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => 'routes/web.php', 'is_correct' => true]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => 'routes/api.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => 'routes/console.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => 'routes/channels.php', 'is_correct' => false]);

        $q4 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какой метод используется для валидации данных в контроллере?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '$request->validate()', 'is_correct' => true]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '$request->check()', 'is_correct' => false]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '$request->verify()', 'is_correct' => false]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '$request->test()', 'is_correct' => false]);

        $q5 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какой файл используется для настройки подключения к базе данных?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => 'config/database.php', 'is_correct' => true]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '.env', 'is_correct' => false]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => 'config/app.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => 'bootstrap/app.php', 'is_correct' => false]);

        $q6 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Что такое Service Provider в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'Центральное место регистрации сервисов', 'is_correct' => true]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'Класс для работы с БД', 'is_correct' => false]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'Middleware для обработки запросов', 'is_correct' => false]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'Роутер для API', 'is_correct' => false]);

        $q7 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какие HTTP методы поддерживаются в маршрутах Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 7,
        ]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => 'GET', 'is_correct' => true]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => 'POST', 'is_correct' => true]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => 'PUT', 'is_correct' => true]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => 'DELETE', 'is_correct' => true]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => 'PATCH', 'is_correct' => true]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => 'OPTIONS', 'is_correct' => true]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => 'CONNECT', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 2: Eloquent ORM и Базы данных (объединен)
        // ============================================
        $test2 = Test::create([
            'title' => 'Eloquent ORM и Базы данных',
            'description' => 'Комплексный тест по Eloquent ORM, миграциям и работе с базой данных в Laravel.',
            'time_limit' => 15,
            'passing_score' => 75,
            'is_published' => true,
        ]);

        $q8 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод Eloquent используется для получения всех записей?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => 'Model::all()', 'is_correct' => true]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => 'Model::get()', 'is_correct' => false]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => 'Model::find()', 'is_correct' => false]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => 'Model::fetch()', 'is_correct' => false]);

        $q9 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод используется для создания новой записи в Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Model::create()', 'is_correct' => true]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Model::insert()', 'is_correct' => false]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Model::save()', 'is_correct' => false]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Model::store()', 'is_correct' => false]);

        $q10 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Как правильно называется связь "один ко многим" в Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'hasMany / belongsTo', 'is_correct' => true]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'hasOne / belongsTo', 'is_correct' => false]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'manyToMany', 'is_correct' => false]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'belongsToMany', 'is_correct' => false]);

        $q11 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Что делает метод `$model->load(\'relation\')`?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Загружает отношение "на лету"', 'is_correct' => true]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Сохраняет модель в БД', 'is_correct' => false]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Удаляет модель', 'is_correct' => false]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Создает новое отношение', 'is_correct' => false]);

        $q12 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Что такое `$fillable` в модели Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'Поля, разрешенные для массового заполнения', 'is_correct' => true]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'Поля, запрещенные для массового заполнения', 'is_correct' => false]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'Поля, которые никогда не сохраняются', 'is_correct' => false]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'Первичный ключ модели', 'is_correct' => false]);

        $q13 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какая связь используется для "многие ко многим"?',
            'type' => 'single',
            'points' => 1,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'belongsToMany', 'is_correct' => true]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'hasMany', 'is_correct' => false]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'hasOne', 'is_correct' => false]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'morphMany', 'is_correct' => false]);

        $q14 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод возвращает первый найденный результат?',
            'type' => 'single',
            'points' => 1,
            'order' => 7,
        ]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::first()', 'is_correct' => true]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::find()', 'is_correct' => false]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::take(1)', 'is_correct' => false]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::one()', 'is_correct' => false]);

        $q15 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какая команда создает миграцию?',
            'type' => 'single',
            'points' => 1,
            'order' => 8,
        ]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'php artisan make:migration', 'is_correct' => true]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'php artisan migrate:make', 'is_correct' => false]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'php artisan create:migration', 'is_correct' => false]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'php artisan new:migration', 'is_correct' => false]);

        $q16 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какая команда применяет все миграции?',
            'type' => 'single',
            'points' => 1,
            'order' => 9,
        ]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'php artisan migrate', 'is_correct' => true]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'php artisan db:migrate', 'is_correct' => false]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'php artisan schema:migrate', 'is_correct' => false]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'php artisan up', 'is_correct' => false]);

        $q17 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какая команда откатывает последнюю миграцию?',
            'type' => 'single',
            'points' => 1,
            'order' => 10,
        ]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'php artisan migrate:rollback', 'is_correct' => true]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'php artisan migrate:down', 'is_correct' => false]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'php artisan migrate:reset', 'is_correct' => false]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'php artisan db:rollback', 'is_correct' => false]);

        $q18 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какая команда показывает статус миграций?',
            'type' => 'single',
            'points' => 1,
            'order' => 11,
        ]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'php artisan migrate:status', 'is_correct' => true]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'php artisan status:migrate', 'is_correct' => false]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'php artisan db:status', 'is_correct' => false]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'php artisan show:migrations', 'is_correct' => false]);

        $q19 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод Schema используется для создания новой таблицы?',
            'type' => 'single',
            'points' => 1,
            'order' => 12,
        ]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => 'Schema::create()', 'is_correct' => true]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => 'Schema::make()', 'is_correct' => false]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => 'Schema::new()', 'is_correct' => false]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => 'Schema::table()', 'is_correct' => false]);

        $q20 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Где хранятся файлы миграций?',
            'type' => 'single',
            'points' => 1,
            'order' => 13,
        ]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => 'database/migrations', 'is_correct' => true]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => 'app/migrations', 'is_correct' => false]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => 'storage/migrations', 'is_correct' => false]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => 'resources/migrations', 'is_correct' => false]);

        $q21 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод используется для пагинации в Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 14,
        ]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => 'Model::paginate()', 'is_correct' => true]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => 'Model::page()', 'is_correct' => false]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => 'Model::limit()', 'is_correct' => false]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => 'Model::offset()', 'is_correct' => false]);

        $q22 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какие методы Eloquent можно использовать для фильтрации данных?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 15,
        ]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => 'where()', 'is_correct' => true]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => 'whereBetween()', 'is_correct' => true]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => 'whereIn()', 'is_correct' => true]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => 'filter()', 'is_correct' => false]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => 'orderBy()', 'is_correct' => true]);

        $q23 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какие типы связей есть в Eloquent?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 16,
        ]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => 'Один к одному (hasOne / belongsTo)', 'is_correct' => true]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => 'Один ко многим (hasMany / belongsTo)', 'is_correct' => true]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => 'Многие ко многим (belongsToMany)', 'is_correct' => true]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => 'Один к одному (hasMany / belongsTo)', 'is_correct' => false]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => 'Полиморфные связи', 'is_correct' => true]);

        // ============================================
        // ТЕСТ 3: Blade и Frontend
        // ============================================
        $test3 = Test::create([
            'title' => 'Blade и Frontend',
            'description' => 'Тест по Blade-шаблонизатору и работе с фронтендом в Laravel.',
            'time_limit' => 10,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $q24 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой синтаксис Blade для вывода переменной?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '{{ $variable }}', 'is_correct' => true]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '<?= $variable ?>', 'is_correct' => false]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '{!! $variable !!}', 'is_correct' => false]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '${ $variable }', 'is_correct' => false]);

        $q25 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой синтаксис Blade для условного оператора `if`?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '@if / @endif', 'is_correct' => true]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '{{ if }} / {{ endif }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '<?php if ?> / <?php endif ?>', 'is_correct' => false]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '<if> / </if>', 'is_correct' => false]);

        $q26 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой метод Blade используется для создания компонентов?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => '<x-component-name />', 'is_correct' => true]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => '@component(\'name\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => '{{ component(\'name\') }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => '@include(\'component\')', 'is_correct' => false]);

        $q27 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Как вывести HTML без экранирования в Blade?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => '{!! $html !!}', 'is_correct' => true]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => '{{ $html }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => '{{ $html | raw }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => '@html($html)', 'is_correct' => false]);

        $q28 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой синтаксис для цикла `foreach` в Blade?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => '@foreach / @endforeach', 'is_correct' => true]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => '{{ foreach }} / {{ endforeach }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => '@for / @endfor', 'is_correct' => false]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => '@each / @endeach', 'is_correct' => false]);

        $q29 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой метод используется для включения другого шаблона?',
            'type' => 'single',
            'points' => 1,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => '@include(\'view.name\')', 'is_correct' => true]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => '@import(\'view.name\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => '@require(\'view.name\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => '@extends(\'view.name\')', 'is_correct' => false]);

        $q30 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой синтаксис для вывода CSRF-токена в форме?',
            'type' => 'single',
            'points' => 1,
            'order' => 7,
        ]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => '@csrf', 'is_correct' => true]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => '{{ csrf_token() }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => '@token', 'is_correct' => false]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => '{{ token() }}', 'is_correct' => false]);

        $q31 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какие директивы Blade используются для циклов?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 8,
        ]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => '@for', 'is_correct' => true]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => '@foreach', 'is_correct' => true]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => '@while', 'is_correct' => true]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => '@loop', 'is_correct' => false]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => '@each', 'is_correct' => true]);

        $q32 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какие директивы Blade используются для условий?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 9,
        ]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => '@if', 'is_correct' => true]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => '@else', 'is_correct' => true]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => '@elseif', 'is_correct' => true]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => '@unless', 'is_correct' => true]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => '@switch', 'is_correct' => true]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => '@condition', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 4: API в Laravel
        // ============================================
        $test4 = Test::create([
            'title' => 'API в Laravel',
            'description' => 'Тест по созданию REST API, аутентификации через Sanctum и работе с JSON.',
            'time_limit' => 8,
            'passing_score' => 80,
            'is_published' => true,
        ]);

        $q33 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой файл используется для API-маршрутов?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'routes/api.php', 'is_correct' => true]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'routes/web.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'routes/console.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'routes/channels.php', 'is_correct' => false]);

        $q34 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой пакет используется для API-аутентификации в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'Laravel Sanctum', 'is_correct' => true]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'Laravel Passport', 'is_correct' => false]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'JWT Auth', 'is_correct' => false]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'Laravel Fortify', 'is_correct' => false]);

        $q35 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой метод используется для создания токена в Sanctum?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => '$user->createToken(\'name\')', 'is_correct' => true]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => 'Token::create($user)', 'is_correct' => false]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => 'Sanctum::createToken()', 'is_correct' => false]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => '$user->generateToken()', 'is_correct' => false]);

        $q36 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой middleware защищает API-маршруты в Sanctum?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'auth:sanctum', 'is_correct' => true]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'auth:api', 'is_correct' => false]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'sanctum:auth', 'is_correct' => false]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'api:auth', 'is_correct' => false]);

        $q37 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой метод возвращает JSON-ответ в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => 'response()->json()', 'is_correct' => true]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => 'json_response()', 'is_correct' => false]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => '$request->json()', 'is_correct' => false]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => 'Response::json()', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 5: Продвинутый Laravel (События, очереди, кеширование, email)
        // ============================================
        $test5 = Test::create([
            'title' => 'Продвинутый Laravel',
            'description' => 'Сложные вопросы по Laravel: события, очереди, кеширование и тестирование.',
            'time_limit' => 10,
            'passing_score' => 80,
            'is_published' => true,
        ]);

        $q38 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какая команда создает событие в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'php artisan make:event', 'is_correct' => true]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'php artisan event:create', 'is_correct' => false]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'php artisan new:event', 'is_correct' => false]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'php artisan generate:event', 'is_correct' => false]);

        $q39 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какой драйвер очереди используется по умолчанию в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'sync', 'is_correct' => true]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'redis', 'is_correct' => false]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'database', 'is_correct' => false]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'beanstalkd', 'is_correct' => false]);

        $q40 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какой метод используется для кеширования данных на 60 минут?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'Cache::put($key, $data, 3600)', 'is_correct' => true]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'Cache::store($key, $data, 60)', 'is_correct' => false]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'Cache::set($key, $data, 60)', 'is_correct' => false]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'Cache::add($key, $data, 3600)', 'is_correct' => false]);

        $q41 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какой метод используется для отправки email в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q41->id, 'answer_text' => 'Mail::to()->send()', 'is_correct' => true]);
        Answer::create(['question_id' => $q41->id, 'answer_text' => 'Email::send()', 'is_correct' => false]);
        Answer::create(['question_id' => $q41->id, 'answer_text' => 'Mailer::send()', 'is_correct' => false]);
        Answer::create(['question_id' => $q41->id, 'answer_text' => 'SendMail::to()', 'is_correct' => false]);

        $q42 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какая команда запускает воркер очередей?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q42->id, 'answer_text' => 'php artisan queue:work', 'is_correct' => true]);
        Answer::create(['question_id' => $q42->id, 'answer_text' => 'php artisan queue:listen', 'is_correct' => false]);
        Answer::create(['question_id' => $q42->id, 'answer_text' => 'php artisan work:queue', 'is_correct' => false]);

        $q43 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какие драйверы поддерживаются для очередей в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q43->id, 'answer_text' => 'sync', 'is_correct' => true]);
        Answer::create(['question_id' => $q43->id, 'answer_text' => 'database', 'is_correct' => true]);
        Answer::create(['question_id' => $q43->id, 'answer_text' => 'redis', 'is_correct' => true]);
        Answer::create(['question_id' => $q43->id, 'answer_text' => 'beanstalkd', 'is_correct' => true]);
        Answer::create(['question_id' => $q43->id, 'answer_text' => 'sqs', 'is_correct' => true]);
        Answer::create(['question_id' => $q43->id, 'answer_text' => 'amqp', 'is_correct' => false]);

        $q44 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какие драйверы кеширования поддерживаются в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 7,
        ]);
        Answer::create(['question_id' => $q44->id, 'answer_text' => 'file', 'is_correct' => true]);
        Answer::create(['question_id' => $q44->id, 'answer_text' => 'redis', 'is_correct' => true]);
        Answer::create(['question_id' => $q44->id, 'answer_text' => 'memcached', 'is_correct' => true]);
        Answer::create(['question_id' => $q44->id, 'answer_text' => 'database', 'is_correct' => true]);
        Answer::create(['question_id' => $q44->id, 'answer_text' => 'array', 'is_correct' => true]);
        Answer::create(['question_id' => $q44->id, 'answer_text' => 'session', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 6: Команды Artisan и общие вопросы
        // ============================================
        $test6 = Test::create([
            'title' => 'Artisan и общие вопросы',
            'description' => 'Тест по командам Artisan и общим вопросам по Laravel.',
            'time_limit' => 8,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $q45 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какая команда очищает кэш приложения?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q45->id, 'answer_text' => 'php artisan cache:clear', 'is_correct' => true]);
        Answer::create(['question_id' => $q45->id, 'answer_text' => 'php artisan config:clear', 'is_correct' => false]);
        Answer::create(['question_id' => $q45->id, 'answer_text' => 'php artisan view:clear', 'is_correct' => false]);
        Answer::create(['question_id' => $q45->id, 'answer_text' => 'php artisan route:clear', 'is_correct' => false]);

        $q46 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Что делает команда `php artisan migrate:fresh`?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q46->id, 'answer_text' => 'Удаляет все таблицы и запускает миграции заново', 'is_correct' => true]);
        Answer::create(['question_id' => $q46->id, 'answer_text' => 'Обновляет только новые миграции', 'is_correct' => false]);
        Answer::create(['question_id' => $q46->id, 'answer_text' => 'Показывает список миграций', 'is_correct' => false]);
        Answer::create(['question_id' => $q46->id, 'answer_text' => 'Создает новую миграцию', 'is_correct' => false]);

        $q47 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какой метод используется для создания URL в Blade?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q47->id, 'answer_text' => '{{ url(\'path\') }}', 'is_correct' => true]);
        Answer::create(['question_id' => $q47->id, 'answer_text' => '{{ route(\'name\') }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q47->id, 'answer_text' => '@url(\'path\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q47->id, 'answer_text' => '{{ path(\'path\') }}', 'is_correct' => false]);

        $q48 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какой пакет используется для тестирования в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q48->id, 'answer_text' => 'PHPUnit', 'is_correct' => true]);
        Answer::create(['question_id' => $q48->id, 'answer_text' => 'Pest', 'is_correct' => false]);
        Answer::create(['question_id' => $q48->id, 'answer_text' => 'Codeception', 'is_correct' => false]);
        Answer::create(['question_id' => $q48->id, 'answer_text' => 'Behat', 'is_correct' => false]);


        // ============================================
        // ТЕСТ 7: Архитектура и паттерны проектирования в Laravel
        // ============================================
        $test7 = Test::create([
            'title' => 'Архитектура и паттерны проектирования',
            'description' => '🔥 Сложные вопросы по архитектуре Laravel: Service Container, Facades, паттерны проектирования и лучшие практики.',
            'time_limit' => 12,
            'passing_score' => 85,
            'is_published' => true,
        ]);

        $q49 = Question::create([
            'test_id' => $test7->id,
            'question_text' => 'Какой метод Service Container используется для разрешения зависимости с передачей параметров конструктора?',
            'type' => 'single',
            'points' => 2,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q49->id, 'answer_text' => 'app()->makeWith($abstract, $parameters)', 'is_correct' => true]);
        Answer::create(['question_id' => $q49->id, 'answer_text' => 'app()->resolve($abstract, $parameters)', 'is_correct' => false]);
        Answer::create(['question_id' => $q49->id, 'answer_text' => 'app()->build($abstract, $parameters)', 'is_correct' => false]);
        Answer::create(['question_id' => $q49->id, 'answer_text' => 'app()->create($abstract, $parameters)', 'is_correct' => false]);

        $q50 = Question::create([
            'test_id' => $test7->id,
            'question_text' => 'Какие паттерны проектирования реализованы в Laravel? (Выберите все верные)',
            'type' => 'multiple',
            'points' => 3,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q50->id, 'answer_text' => 'Factory (фабрика)', 'is_correct' => true]);
        Answer::create(['question_id' => $q50->id, 'answer_text' => 'Singleton (одиночка)', 'is_correct' => true]);
        Answer::create(['question_id' => $q50->id, 'answer_text' => 'Observer (наблюдатель)', 'is_correct' => true]);
        Answer::create(['question_id' => $q50->id, 'answer_text' => 'Repository (репозиторий)', 'is_correct' => true]);
        Answer::create(['question_id' => $q50->id, 'answer_text' => 'Strategy (стратегия)', 'is_correct' => true]);
        Answer::create(['question_id' => $q50->id, 'answer_text' => 'Adapter (адаптер)', 'is_correct' => true]);
        Answer::create(['question_id' => $q50->id, 'answer_text' => 'Composite (компоновщик)', 'is_correct' => false]);

        $q51 = Question::create([
            'test_id' => $test7->id,
            'question_text' => 'Что такое "Service Provider" и в каком порядке они загружаются?',
            'type' => 'single',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q51->id, 'answer_text' => 'Класс для регистрации сервисов, загружаются в порядке: сначала в config/app.php, затем автоматически', 'is_correct' => true]);
        Answer::create(['question_id' => $q51->id, 'answer_text' => 'Класс для маршрутизации, загружаются по алфавиту', 'is_correct' => false]);
        Answer::create(['question_id' => $q51->id, 'answer_text' => 'Middleware для обработки запросов, загружаются по приоритету', 'is_correct' => false]);
        Answer::create(['question_id' => $q51->id, 'answer_text' => 'Фасад для работы с БД, загружаются в порядке объявления', 'is_correct' => false]);

        $q52 = Question::create([
            'test_id' => $test7->id,
            'question_text' => 'В чем разница между методами `bind()` и `singleton()` в Service Container?',
            'type' => 'single',
            'points' => 2,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q52->id, 'answer_text' => 'bind() создает новый экземпляр при каждом разрешении, singleton() создает только один экземпляр', 'is_correct' => true]);
        Answer::create(['question_id' => $q52->id, 'answer_text' => 'bind() используется для интерфейсов, singleton() для классов', 'is_correct' => false]);
        Answer::create(['question_id' => $q52->id, 'answer_text' => 'bind() работает только с фасадами, singleton() с провайдерами', 'is_correct' => false]);
        Answer::create(['question_id' => $q52->id, 'answer_text' => 'Разницы нет, это синонимы', 'is_correct' => false]);

        $q53 = Question::create([
            'test_id' => $test7->id,
            'question_text' => 'Как правильно реализовать Dependency Injection в конструкторе контроллера?',
            'type' => 'single',
            'points' => 2,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q53->id, 'answer_text' => 'public function __construct(UserService $userService)', 'is_correct' => true]);
        Answer::create(['question_id' => $q53->id, 'answer_text' => 'public function __construct($userService)', 'is_correct' => false]);
        Answer::create(['question_id' => $q53->id, 'answer_text' => 'public function __construct(Container $container)', 'is_correct' => false]);
        Answer::create(['question_id' => $q53->id, 'answer_text' => 'public function __construct() { $this->userService = app(UserService::class); }', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 8: Оптимизация и производительность
        // ============================================
        $test8 = Test::create([
            'title' => 'Оптимизация и производительность',
            'description' => 'Продвинутые вопросы по оптимизации запросов, кешированию, индексам и работе с большими данными.',
            'time_limit' => 10,
            'passing_score' => 85,
            'is_published' => true,
        ]);

        $q54 = Question::create([
            'test_id' => $test8->id,
            'question_text' => 'Какие методы Eloquent помогают оптимизировать N+1 проблему?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q54->id, 'answer_text' => 'with()', 'is_correct' => true]);
        Answer::create(['question_id' => $q54->id, 'answer_text' => 'load()', 'is_correct' => true]);
        Answer::create(['question_id' => $q54->id, 'answer_text' => 'lazy()', 'is_correct' => true]);
        Answer::create(['question_id' => $q54->id, 'answer_text' => 'chunk()', 'is_correct' => true]);
        Answer::create(['question_id' => $q54->id, 'answer_text' => 'cursor()', 'is_correct' => true]);
        Answer::create(['question_id' => $q54->id, 'answer_text' => 'first()', 'is_correct' => false]);

        $q55 = Question::create([
            'test_id' => $test8->id,
            'question_text' => 'Какая стратегия индексации наиболее эффективна для поиска по тексту в MySQL?',
            'type' => 'single',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q55->id, 'answer_text' => 'Полнотекстовый индекс (FULLTEXT) с использованием MATCH AGAINST', 'is_correct' => true]);
        Answer::create(['question_id' => $q55->id, 'answer_text' => 'Обычный индекс B-Tree с LIKE запросом', 'is_correct' => false]);
        Answer::create(['question_id' => $q55->id, 'answer_text' => 'Составной индекс по нескольким полям', 'is_correct' => false]);
        Answer::create(['question_id' => $q55->id, 'answer_text' => 'Уникальный индекс с точным совпадением', 'is_correct' => false]);

        $q56 = Question::create([
            'test_id' => $test8->id,
            'question_text' => 'Какой метод кеширования наиболее эффективен для часто изменяемых данных?',
            'type' => 'single',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q56->id, 'answer_text' => 'Cache::remember() с установкой TTL и использованием tags для инвалидации', 'is_correct' => true]);
        Answer::create(['question_id' => $q56->id, 'answer_text' => 'Cache::forever() без ограничения времени', 'is_correct' => false]);
        Answer::create(['question_id' => $q56->id, 'answer_text' => 'Хранение в сессии', 'is_correct' => false]);
        Answer::create(['question_id' => $q56->id, 'answer_text' => 'Кеширование в файлах', 'is_correct' => false]);

        $q57 = Question::create([
            'test_id' => $test8->id,
            'question_text' => 'Какие команды Artisan используются для оптимизации производительности?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q57->id, 'answer_text' => 'php artisan optimize', 'is_correct' => true]);
        Answer::create(['question_id' => $q57->id, 'answer_text' => 'php artisan config:cache', 'is_correct' => true]);
        Answer::create(['question_id' => $q57->id, 'answer_text' => 'php artisan route:cache', 'is_correct' => true]);
        Answer::create(['question_id' => $q57->id, 'answer_text' => 'php artisan view:cache', 'is_correct' => true]);
        Answer::create(['question_id' => $q57->id, 'answer_text' => 'php artisan optimize:clear', 'is_correct' => true]);
        Answer::create(['question_id' => $q57->id, 'answer_text' => 'php artisan serve --optimize', 'is_correct' => false]);

        $q58 = Question::create([
            'test_id' => $test8->id,
            'question_text' => 'Что такое "eager loading" и когда его следует использовать?',
            'type' => 'single',
            'points' => 2,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q58->id, 'answer_text' => 'Загрузка связей при первом запросе для избежания N+1 проблемы, использовать когда точно знаем, что связи понадобятся', 'is_correct' => true]);
        Answer::create(['question_id' => $q58->id, 'answer_text' => 'Отложенная загрузка связей при обращении к свойству', 'is_correct' => false]);
        Answer::create(['question_id' => $q58->id, 'answer_text' => 'Загрузка всех моделей без ограничений', 'is_correct' => false]);
        Answer::create(['question_id' => $q58->id, 'answer_text' => 'Загрузка только первого уровня вложенности', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 9: Безопасность и аутентификация
        // ============================================
        $test9 = Test::create([
            'title' => 'Безопасность и аутентификация',
            'description' => '🔥 Сложные вопросы по безопасности, аутентификации, авторизации и защите от атак.',
            'time_limit' => 10,
            'passing_score' => 90,
            'is_published' => true,
        ]);

        $q59 = Question::create([
            'test_id' => $test9->id,
            'question_text' => 'Какие меры безопасности автоматически применяются в Laravel?',
            'type' => 'multiple',
            'points' => 3,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q59->id, 'answer_text' => 'CSRF защита', 'is_correct' => true]);
        Answer::create(['question_id' => $q59->id, 'answer_text' => 'XSS экранирование в Blade', 'is_correct' => true]);
        Answer::create(['question_id' => $q59->id, 'answer_text' => 'SQL инъекции через Eloquent', 'is_correct' => true]);
        Answer::create(['question_id' => $q59->id, 'answer_text' => 'Шифрование паролей', 'is_correct' => true]);
        Answer::create(['question_id' => $q59->id, 'answer_text' => 'Защита от Clickjacking через заголовки', 'is_correct' => true]);
        Answer::create(['question_id' => $q59->id, 'answer_text' => 'Защита от DDoS атак', 'is_correct' => false]);

        $q60 = Question::create([
            'test_id' => $test9->id,
            'question_text' => 'Какие методы шифрования данных доступны в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q60->id, 'answer_text' => 'OpenSSL AES-256-CBC', 'is_correct' => true]);
        Answer::create(['question_id' => $q60->id, 'answer_text' => 'bcrypt для хеширования паролей', 'is_correct' => true]);
        Answer::create(['question_id' => $q60->id, 'answer_text' => 'Argon2 для хеширования', 'is_correct' => true]);
        Answer::create(['question_id' => $q60->id, 'answer_text' => 'MD5 шифрование', 'is_correct' => false]);
        Answer::create(['question_id' => $q60->id, 'answer_text' => 'SHA-1 хеширование', 'is_correct' => false]);

        $q61 = Question::create([
            'test_id' => $test9->id,
            'question_text' => 'Как правильно реализовать двухфакторную аутентификацию (2FA) в Laravel?',
            'type' => 'single',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q61->id, 'answer_text' => 'Использовать пакеты типа laravel/two-factor или реализовать через Google Authenticator + временные коды', 'is_correct' => true]);
        Answer::create(['question_id' => $q61->id, 'answer_text' => 'Просто добавить поле в таблицу пользователей', 'is_correct' => false]);
        Answer::create(['question_id' => $q61->id, 'answer_text' => 'Использовать только SMS-коды', 'is_correct' => false]);
        Answer::create(['question_id' => $q61->id, 'answer_text' => 'Добавить проверку по email', 'is_correct' => false]);

        $q62 = Question::create([
            'test_id' => $test9->id,
            'question_text' => 'Какие типы авторизации поддерживаются в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q62->id, 'answer_text' => 'Gates (шлюзы)', 'is_correct' => true]);
        Answer::create(['question_id' => $q62->id, 'answer_text' => 'Policies (политики)', 'is_correct' => true]);
        Answer::create(['question_id' => $q62->id, 'answer_text' => 'Middleware авторизация', 'is_correct' => true]);
        Answer::create(['question_id' => $q62->id, 'answer_text' => 'RBAC (ролевая модель)', 'is_correct' => true]);
        Answer::create(['question_id' => $q62->id, 'answer_text' => 'ABAC (атрибутная модель)', 'is_correct' => false]);

        $q63 = Question::create([
            'test_id' => $test9->id,
            'question_text' => 'Какой middleware используется для защиты от подделки межсайтовых запросов (CSRF)?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q63->id, 'answer_text' => 'VerifyCsrfToken', 'is_correct' => true]);
        Answer::create(['question_id' => $q63->id, 'answer_text' => 'Authenticate', 'is_correct' => false]);
        Answer::create(['question_id' => $q63->id, 'answer_text' => 'ThrottleRequests', 'is_correct' => false]);
        Answer::create(['question_id' => $q63->id, 'answer_text' => 'ValidatePostSize', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 10: Тестирование и TDD
        // ============================================
        $test10 = Test::create([
            'title' => 'Тестирование и TDD',
            'description' => '🔥 Продвинутые вопросы по тестированию в Laravel, включая Unit-тесты, Feature-тесты и мокирование.',
            'time_limit' => 10,
            'passing_score' => 85,
            'is_published' => true,
        ]);

        $q64 = Question::create([
            'test_id' => $test10->id,
            'question_text' => 'Какие методы для тестирования API доступны в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q64->id, 'answer_text' => '$this->getJson()', 'is_correct' => true]);
        Answer::create(['question_id' => $q64->id, 'answer_text' => '$this->postJson()', 'is_correct' => true]);
        Answer::create(['question_id' => $q64->id, 'answer_text' => '$this->putJson()', 'is_correct' => true]);
        Answer::create(['question_id' => $q64->id, 'answer_text' => '$this->deleteJson()', 'is_correct' => true]);
        Answer::create(['question_id' => $q64->id, 'answer_text' => '$this->get()', 'is_correct' => false]);
        Answer::create(['question_id' => $q64->id, 'answer_text' => '$this->call()', 'is_correct' => false]);

        $q65 = Question::create([
            'test_id' => $test10->id,
            'question_text' => 'Как правильно мокировать фасад в тестах?',
            'type' => 'single',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q65->id, 'answer_text' => 'Facade::shouldReceive() с использованием shouldReceive и mock', 'is_correct' => true]);
        Answer::create(['question_id' => $q65->id, 'answer_text' => 'Facade::fake() для всех фасадов', 'is_correct' => false]);
        Answer::create(['question_id' => $q65->id, 'answer_text' => 'Mockery::mock(Facade::class)', 'is_correct' => false]);
        Answer::create(['question_id' => $q65->id, 'answer_text' => 'Просто переопределить фасад в тесте', 'is_correct' => false]);

        $q66 = Question::create([
            'test_id' => $test10->id,
            'question_text' => 'Какие утверждения (assertions) доступны для проверки JSON-ответов?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q66->id, 'answer_text' => 'assertJson()', 'is_correct' => true]);
        Answer::create(['question_id' => $q66->id, 'answer_text' => 'assertExactJson()', 'is_correct' => true]);
        Answer::create(['question_id' => $q66->id, 'answer_text' => 'assertJsonPath()', 'is_correct' => true]);
        Answer::create(['question_id' => $q66->id, 'answer_text' => 'assertJsonFragment()', 'is_correct' => true]);
        Answer::create(['question_id' => $q66->id, 'answer_text' => 'assertJsonStructure()', 'is_correct' => true]);
        Answer::create(['question_id' => $q66->id, 'answer_text' => 'assertJsonHas()', 'is_correct' => false]);

        $q67 = Question::create([
            'test_id' => $test10->id,
            'question_text' => 'В чем разница между UnitTest и FeatureTest в Laravel?',
            'type' => 'single',
            'points' => 2,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q67->id, 'answer_text' => 'UnitTest тестирует отдельные компоненты изолированно, FeatureTest тестирует интеграцию и полноценные запросы', 'is_correct' => true]);
        Answer::create(['question_id' => $q67->id, 'answer_text' => 'UnitTest тестирует только модели, FeatureTest - контроллеры', 'is_correct' => false]);
        Answer::create(['question_id' => $q67->id, 'answer_text' => 'UnitTest запускается быстрее, FeatureTest медленнее', 'is_correct' => false]);
        Answer::create(['question_id' => $q67->id, 'answer_text' => 'UnitTest использует базу данных, FeatureTest - нет', 'is_correct' => false]);

        $q68 = Question::create([
            'test_id' => $test10->id,
            'question_text' => 'Какие методы для создания фейковых данных доступны в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q68->id, 'answer_text' => 'Model::factory()', 'is_correct' => true]);
        Answer::create(['question_id' => $q68->id, 'answer_text' => 'Faker библиотека', 'is_correct' => true]);
        Answer::create(['question_id' => $q68->id, 'answer_text' => 'DatabaseSeeder для заполнения', 'is_correct' => true]);
        Answer::create(['question_id' => $q68->id, 'answer_text' => 'make() метод', 'is_correct' => true]);
        Answer::create(['question_id' => $q68->id, 'answer_text' => 'create() метод', 'is_correct' => true]);
        Answer::create(['question_id' => $q68->id, 'answer_text' => 'seed() метод', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 11: Пакеты и экосистема Laravel
        // ============================================
        $test11 = Test::create([
            'title' => 'Пакеты и экосистема Laravel',
            'description' => '🔥 Вопросы о популярных пакетах и экосистеме Laravel для решения сложных задач.',
            'time_limit' => 10,
            'passing_score' => 80,
            'is_published' => true,
        ]);

        $q69 = Question::create([
            'test_id' => $test11->id,
            'question_text' => 'Какие популярные пакеты используются в Laravel для разных задач? (Выберите все верные)',
            'type' => 'multiple',
            'points' => 3,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Debugbar - для отладки', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel IDE Helper - для автодополнения', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Telescope - для мониторинга', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Nova - админ-панель', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Horizon - мониторинг очередей', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Dusk - тестирование браузера', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Mix - сборка фронтенда', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Socialite - для OAuth', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Cashier - для платежей', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Spark - для SaaS', 'is_correct' => true]);
        Answer::create(['question_id' => $q69->id, 'answer_text' => 'Laravel Jetstream - стартовый набор', 'is_correct' => true]);

        $q70 = Question::create([
            'test_id' => $test11->id,
            'question_text' => 'Как создать собственный пакет для Laravel?',
            'type' => 'single',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q70->id, 'answer_text' => 'Создать структуру, зарегистрировать Service Provider, использовать composer autoload', 'is_correct' => true]);
        Answer::create(['question_id' => $q70->id, 'answer_text' => 'Просто скопировать код в папку packages', 'is_correct' => false]);
        Answer::create(['question_id' => $q70->id, 'answer_text' => 'Использовать artisan make:package', 'is_correct' => false]);
        Answer::create(['question_id' => $q70->id, 'answer_text' => 'Создать через Composer create-project', 'is_correct' => false]);

        $q71 = Question::create([
            'test_id' => $test11->id,
            'question_text' => 'Какие инструменты для мониторинга и отладки доступны в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q71->id, 'answer_text' => 'Laravel Telescope', 'is_correct' => true]);
        Answer::create(['question_id' => $q71->id, 'answer_text' => 'Laravel Debugbar', 'is_correct' => true]);
        Answer::create(['question_id' => $q71->id, 'answer_text' => 'Laravel Horizon', 'is_correct' => true]);
        Answer::create(['question_id' => $q71->id, 'answer_text' => 'Laravel Dusk', 'is_correct' => false]);
        Answer::create(['question_id' => $q71->id, 'answer_text' => 'Laravel Nova', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 12: Сложные сценарии и edge cases
        // ============================================
        $test12 = Test::create([
            'title' => 'Сложные сценарии и edge cases',
            'description' => '🔥🔥 Самые сложные вопросы по Laravel для опытных разработчиков: транзакции, очереди, исключения и конкурентность.',
            'time_limit' => 12,
            'passing_score' => 90,
            'is_published' => true,
        ]);

        $q72 = Question::create([
            'test_id' => $test12->id,
            'question_text' => 'Как правильно обрабатывать конкурентные запросы и race conditions в Laravel?',
            'type' => 'single',
            'points' => 3,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q72->id, 'answer_text' => 'Использовать пессимистическую блокировку (lockForUpdate()) или оптимистическую блокировку с версионированием', 'is_correct' => true]);
        Answer::create(['question_id' => $q72->id, 'answer_text' => 'Использовать только транзакции', 'is_correct' => false]);
        Answer::create(['question_id' => $q72->id, 'answer_text' => 'Использовать очереди для всех запросов', 'is_correct' => false]);
        Answer::create(['question_id' => $q72->id, 'answer_text' => 'Обрабатывать все запросы синхронно', 'is_correct' => false]);

        $q73 = Question::create([
            'test_id' => $test12->id,
            'question_text' => 'Какие методы используются для обработки исключений и создания кастомных error handler?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q73->id, 'answer_text' => 'try-catch блоки', 'is_correct' => true]);
        Answer::create(['question_id' => $q73->id, 'answer_text' => 'Кастомный render метод в Handler', 'is_correct' => true]);
        Answer::create(['question_id' => $q73->id, 'answer_text' => 'report метод для логирования', 'is_correct' => true]);
        Answer::create(['question_id' => $q73->id, 'answer_text' => 'Exception::handle()', 'is_correct' => false]);
        Answer::create(['question_id' => $q73->id, 'answer_text' => 'global try-catch', 'is_correct' => false]);

        $q74 = Question::create([
            'test_id' => $test12->id,
            'question_text' => 'Как реализовать сложные бизнес-процессы с использованием очередей и событий?',
            'type' => 'single',
            'points' => 3,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q74->id, 'answer_text' => 'Использовать цепочки заданий в очереди с событиями для обновления статуса и уведомлений', 'is_correct' => true]);
        Answer::create(['question_id' => $q74->id, 'answer_text' => 'Просто добавить в базу данных запись', 'is_correct' => false]);
        Answer::create(['question_id' => $q74->id, 'answer_text' => 'Обрабатывать все синхронно', 'is_correct' => false]);
        Answer::create(['question_id' => $q74->id, 'answer_text' => 'Использовать cron задачи', 'is_correct' => false]);

        $q75 = Question::create([
            'test_id' => $test12->id,
            'question_text' => 'Какие проблемы могут возникнуть при работе с большими данными в очереди и как их решить?',
            'type' => 'multiple',
            'points' => 3,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q75->id, 'answer_text' => 'Переполнение памяти - использовать chunk/processing', 'is_correct' => true]);
        Answer::create(['question_id' => $q75->id, 'answer_text' => 'Долгое выполнение - использовать timeout и retry', 'is_correct' => true]);
        Answer::create(['question_id' => $q75->id, 'answer_text' => 'Ошибки сериализации - использовать __sleep/__wakeup', 'is_correct' => true]);
        Answer::create(['question_id' => $q75->id, 'answer_text' => 'Мертвые блокировки - использовать try/finally', 'is_correct' => true]);
        Answer::create(['question_id' => $q75->id, 'answer_text' => 'Проблемы с индексами - добавить индексы', 'is_correct' => true]);
        Answer::create(['question_id' => $q75->id, 'answer_text' => 'Проблемы не возникает', 'is_correct' => false]);

        $q76 = Question::create([
            'test_id' => $test12->id,
            'question_text' => 'Как правильно реализовать кастомную коллекцию с дополнительными методами в Laravel?',
            'type' => 'single',
            'points' => 2,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q76->id, 'answer_text' => 'Создать класс расширяющий Illuminate\Support\Collection и использовать макросы', 'is_correct' => true]);
        Answer::create(['question_id' => $q76->id, 'answer_text' => 'Просто добавить метод в модель', 'is_correct' => false]);
        Answer::create(['question_id' => $q76->id, 'answer_text' => 'Использовать helper функции', 'is_correct' => false]);
        Answer::create(['question_id' => $q76->id, 'answer_text' => 'Создать отдельный класс без расширения', 'is_correct' => false]);

        $q77 = Question::create([
            'test_id' => $test12->id,
            'question_text' => 'Какие стратегии миграции базы данных существуют в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q77->id, 'answer_text' => 'Squash миграции для уменьшения количества файлов', 'is_correct' => true]);
        Answer::create(['question_id' => $q77->id, 'answer_text' => 'Rollback с восстановлением данных', 'is_correct' => true]);
        Answer::create(['question_id' => $q77->id, 'answer_text' => 'Fresh с повторным заполнением', 'is_correct' => true]);
        Answer::create(['question_id' => $q77->id, 'answer_text' => 'Refresh для полного перезапуска', 'is_correct' => true]);
        Answer::create(['question_id' => $q77->id, 'answer_text' => 'Migrate с проверкой данных', 'is_correct' => true]);
        Answer::create(['question_id' => $q77->id, 'answer_text' => 'Автоматическая миграция', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 13: WebSockets и real-time приложения
        // ============================================
        $test13 = Test::create([
            'title' => 'WebSockets и real-time приложения',
            'description' => '🔥 Сложные вопросы по работе с WebSockets, broadcasting и real-time функциональностью.',
            'time_limit' => 10,
            'passing_score' => 85,
            'is_published' => true,
        ]);

        $q78 = Question::create([
            'test_id' => $test13->id,
            'question_text' => 'Какие драйверы broadcasting поддерживаются в Laravel для real-time приложений?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q78->id, 'answer_text' => 'Pusher', 'is_correct' => true]);
        Answer::create(['question_id' => $q78->id, 'answer_text' => 'Redis', 'is_correct' => true]);
        Answer::create(['question_id' => $q78->id, 'answer_text' => 'Laravel WebSockets', 'is_correct' => true]);
        Answer::create(['question_id' => $q78->id, 'answer_text' => 'Ably', 'is_correct' => true]);
        Answer::create(['question_id' => $q78->id, 'answer_text' => 'WebSocket (сервер) напрямую', 'is_correct' => false]);
        Answer::create(['question_id' => $q78->id, 'answer_text' => 'Socket.io', 'is_correct' => false]);

        $q79 = Question::create([
            'test_id' => $test13->id,
            'question_text' => 'Как обеспечить авторизацию для private каналов в WebSockets?',
            'type' => 'single',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q79->id, 'answer_text' => 'Реализовать метод authorize в BroadcastServiceProvider или использовать кастомную авторизацию', 'is_correct' => true]);
        Answer::create(['question_id' => $q79->id, 'answer_text' => 'Использовать только public каналы', 'is_correct' => false]);
        Answer::create(['question_id' => $q79->id, 'answer_text' => 'Встроить токен в WebSocket URL', 'is_correct' => false]);
        Answer::create(['question_id' => $q79->id, 'answer_text' => 'Использовать middleware на клиенте', 'is_correct' => false]);

        $q80 = Question::create([
            'test_id' => $test13->id,
            'question_text' => 'Какие методы используются для отправки событий в реальном времени?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q80->id, 'answer_text' => 'event() helper', 'is_correct' => true]);
        Answer::create(['question_id' => $q80->id, 'answer_text' => 'Event::dispatch()', 'is_correct' => true]);
        Answer::create(['question_id' => $q80->id, 'answer_text' => 'Broadcast::event()', 'is_correct' => true]);
        Answer::create(['question_id' => $q80->id, 'answer_text' => 'WebSocket::send()', 'is_correct' => false]);
        Answer::create(['question_id' => $q80->id, 'answer_text' => 'Прямой вызов через сокет', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 14: Microservices и API Gateway
        // ============================================
        $test14 = Test::create([
            'title' => 'Microservices и API Gateway',
            'description' => '🔥🔥 Продвинутые вопросы по построению микросервисов и API Gateway в экосистеме Laravel.',
            'time_limit' => 10,
            'passing_score' => 85,
            'is_published' => true,
        ]);

        $q81 = Question::create([
            'test_id' => $test14->id,
            'question_text' => 'Какие подходы используются для межсервисной аутентификации в микросервисах на Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q81->id, 'answer_text' => 'JWT токены', 'is_correct' => true]);
        Answer::create(['question_id' => $q81->id, 'answer_text' => 'API ключи', 'is_correct' => true]);
        Answer::create(['question_id' => $q81->id, 'answer_text' => 'OAuth2 с Laravel Passport', 'is_correct' => true]);
        Answer::create(['question_id' => $q81->id, 'answer_text' => 'Базовая авторизация HTTP', 'is_correct' => false]);
        Answer::create(['question_id' => $q81->id, 'answer_text' => 'Сессии', 'is_correct' => false]);

        $q82 = Question::create([
            'test_id' => $test14->id,
            'question_text' => 'Как организовать межсервисную коммуникацию между Laravel микросервисами?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q82->id, 'answer_text' => 'HTTP клиенты (Guzzle)', 'is_correct' => true]);
        Answer::create(['question_id' => $q82->id, 'answer_text' => 'Очереди (RabbitMQ, Redis)', 'is_correct' => true]);
        Answer::create(['question_id' => $q82->id, 'answer_text' => 'WebSockets', 'is_correct' => true]);
        Answer::create(['question_id' => $q82->id, 'answer_text' => 'REST API', 'is_correct' => true]);
        Answer::create(['question_id' => $q82->id, 'answer_text' => 'GraphQL', 'is_correct' => true]);
        Answer::create(['question_id' => $q82->id, 'answer_text' => 'gRPC', 'is_correct' => true]);
        Answer::create(['question_id' => $q82->id, 'answer_text' => 'SOAP', 'is_correct' => false]);

        $q83 = Question::create([
            'test_id' => $test14->id,
            'question_text' => 'Как реализовать API Gateway с помощью Laravel?',
            'type' => 'single',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q83->id, 'answer_text' => 'Использовать Laravel как прокси с маршрутизацией и обработкой запросов к микросервисам', 'is_correct' => true]);
        Answer::create(['question_id' => $q83->id, 'answer_text' => 'Использовать отдельный Gateway пакет', 'is_correct' => false]);
        Answer::create(['question_id' => $q83->id, 'answer_text' => 'Использовать NGINX без Laravel', 'is_correct' => false]);
        Answer::create(['question_id' => $q83->id, 'answer_text' => 'Использовать сторонний сервис', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 15: Кэширование и Session Management
        // ============================================
        $test15 = Test::create([
            'title' => 'Кэширование и управление сессиями',
            'description' => '🔥 Глубокие вопросы по кэшированию, управлению сессиями и оптимизации хранилищ.',
            'time_limit' => 10,
            'passing_score' => 85,
            'is_published' => true,
        ]);

        $q84 = Question::create([
            'test_id' => $test15->id,
            'question_text' => 'Какие драйверы для управления сессиями поддерживаются в Laravel?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'file', 'is_correct' => true]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'cookie', 'is_correct' => true]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'database', 'is_correct' => true]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'redis', 'is_correct' => true]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'memcached', 'is_correct' => true]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'array', 'is_correct' => true]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'apc', 'is_correct' => true]);
        Answer::create(['question_id' => $q84->id, 'answer_text' => 'session', 'is_correct' => false]);

        $q85 = Question::create([
            'test_id' => $test15->id,
            'question_text' => 'Как правильно реализовать кэширование с использованием тегов в Redis?',
            'type' => 'single',
            'points' => 2,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q85->id, 'answer_text' => 'Cache::tags([\'tag1\', \'tag2\'])->put($key, $value, $ttl)', 'is_correct' => true]);
        Answer::create(['question_id' => $q85->id, 'answer_text' => 'Redis::tag()->set()', 'is_correct' => false]);
        Answer::create(['question_id' => $q85->id, 'answer_text' => 'Cache::tagged()->store()', 'is_correct' => false]);
        Answer::create(['question_id' => $q85->id, 'answer_text' => 'Теги не поддерживаются в Laravel', 'is_correct' => false]);

        $q86 = Question::create([
            'test_id' => $test15->id,
            'question_text' => 'Какие стратегии кэширования используются для динамических данных?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q86->id, 'answer_text' => 'Cache-Aside (Lazy Loading)', 'is_correct' => true]);
        Answer::create(['question_id' => $q86->id, 'answer_text' => 'Write-Through (сквозная запись)', 'is_correct' => true]);
        Answer::create(['question_id' => $q86->id, 'answer_text' => 'Write-Behind (отложенная запись)', 'is_correct' => true]);
        Answer::create(['question_id' => $q86->id, 'answer_text' => 'Refresh-Ahead (обновление перед доступом)', 'is_correct' => true]);
        Answer::create(['question_id' => $q86->id, 'answer_text' => 'Только Eager Loading', 'is_correct' => false]);

        // ============================================
        // ТЕСТ 16: Очереди и фоновые задачи (продвинутый)
        // ============================================
        $test16 = Test::create([
            'title' => 'Очереди и фоновые задачи (продвинутый)',
            'description' => '🔥🔥 Самые сложные вопросы по очередям, batch-обработке, и фоновым задачам в Laravel.',
            'time_limit' => 12,
            'passing_score' => 90,
            'is_published' => true,
        ]);

        $q87 = Question::create([
            'test_id' => $test16->id,
            'question_text' => 'Какие функции доступны для batch-обработки заданий в очереди?',
            'type' => 'multiple',
            'points' => 3,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q87->id, 'answer_text' => 'Bus::batch() для группировки заданий', 'is_correct' => true]);
        Answer::create(['question_id' => $q87->id, 'answer_text' => 'Отслеживание прогресса через then', 'is_correct' => true]);
        Answer::create(['question_id' => $q87->id, 'answer_text' => 'Обработка ошибок в batch', 'is_correct' => true]);
        Answer::create(['question_id' => $q87->id, 'answer_text' => 'Ограничение параллельности', 'is_correct' => true]);
        Answer::create(['question_id' => $q87->id, 'answer_text' => 'Отмена выполнения batch', 'is_correct' => true]);
        Answer::create(['question_id' => $q87->id, 'answer_text' => 'Batch не поддерживается в Laravel', 'is_correct' => false]);

        $q88 = Question::create([
            'test_id' => $test16->id,
            'question_text' => 'Как правильно обрабатывать ошибки и повторные попытки в очередях?',
            'type' => 'single',
            'points' => 3,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q88->id, 'answer_text' => 'Использовать retry_until с экспоненциальной задержкой и метод failed для финальной обработки', 'is_correct' => true]);
        Answer::create(['question_id' => $q88->id, 'answer_text' => 'Просто использовать try-catch', 'is_correct' => false]);
        Answer::create(['question_id' => $q88->id, 'answer_text' => 'Бесконечно повторять задание', 'is_correct' => false]);
        Answer::create(['question_id' => $q88->id, 'answer_text' => 'Игнорировать ошибки', 'is_correct' => false]);

        $q89 = Question::create([
            'test_id' => $test16->id,
            'question_text' => 'Какие подходы используются для ограничения нагрузки на очереди?',
            'type' => 'multiple',
            'points' => 2,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q89->id, 'answer_text' => 'Rate limiting (ограничение скорости)', 'is_correct' => true]);
        Answer::create(['question_id' => $q89->id, 'answer_text' => 'Приоритеты заданий', 'is_correct' => true]);
        Answer::create(['question_id' => $q89->id, 'answer_text' => 'Разные очереди с разными worker', 'is_correct' => true]);
        Answer::create(['question_id' => $q89->id, 'answer_text' => 'Ограничение количества worker', 'is_correct' => true]);
        Answer::create(['question_id' => $q89->id, 'answer_text' => 'Throttle middleware', 'is_correct' => true]);
        Answer::create(['question_id' => $q89->id, 'answer_text' => 'Автоматическое масштабирование', 'is_correct' => false]);

        $q90 = Question::create([
            'test_id' => $test16->id,
            'question_text' => 'Как реализовать отложенную обработку с динамической задержкой?',
            'type' => 'single',
            'points' => 2,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q90->id, 'answer_text' => 'dispatch()->delay(now()->addSeconds($calculatedDelay))', 'is_correct' => true]);
        Answer::create(['question_id' => $q90->id, 'answer_text' => 'Использовать sleep в задании', 'is_correct' => false]);
        Answer::create(['question_id' => $q90->id, 'answer_text' => 'Использовать cron', 'is_correct' => false]);
        Answer::create(['question_id' => $q90->id, 'answer_text' => 'Использовать loop с проверкой времени', 'is_correct' => false]);


        $this->command->info('Тесты успешно созданы!');
        $this->command->info('Всего создано: ' . Test::count() . ' тестов');
        $this->command->info('Всего вопросов: ' . Question::count());
        $this->command->info('Всего ответов: ' . Answer::count());
    }
}
