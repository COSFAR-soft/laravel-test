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
            'question_text' => 'Какой метод Eloquent используется для получения всех записей?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => 'Model::all()', 'is_correct' => true]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => 'Model::get()', 'is_correct' => false]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => 'Model::find()', 'is_correct' => false]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => 'Model::fetch()', 'is_correct' => false]);

        $q5 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какой синтаксис Blade для вывода переменной?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '{{ $variable }}', 'is_correct' => true]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '<?= $variable ?>', 'is_correct' => false]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '{!! $variable !!}', 'is_correct' => false]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '${ $variable }', 'is_correct' => false]);

        $q6 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какая команда создает миграцию?',
            'type' => 'single',
            'points' => 1,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'php artisan make:migration', 'is_correct' => true]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'php artisan migrate:make', 'is_correct' => false]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'php artisan create:migration', 'is_correct' => false]);
        Answer::create(['question_id' => $q6->id, 'answer_text' => 'php artisan new:migration', 'is_correct' => false]);

        $q7 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какой метод используется для валидации данных в контроллере?',
            'type' => 'single',
            'points' => 1,
            'order' => 7,
        ]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => '$request->validate()', 'is_correct' => true]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => '$request->check()', 'is_correct' => false]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => '$request->verify()', 'is_correct' => false]);
        Answer::create(['question_id' => $q7->id, 'answer_text' => '$request->test()', 'is_correct' => false]);

        $q8 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какой файл используется для настройки подключения к базе данных?',
            'type' => 'single',
            'points' => 1,
            'order' => 8,
        ]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => 'config/database.php', 'is_correct' => true]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => '.env', 'is_correct' => false]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => 'config/app.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q8->id, 'answer_text' => 'bootstrap/app.php', 'is_correct' => false]);

        $q9 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Что такое Service Provider в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 9,
        ]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Центральное место регистрации сервисов', 'is_correct' => true]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Класс для работы с БД', 'is_correct' => false]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Middleware для обработки запросов', 'is_correct' => false]);
        Answer::create(['question_id' => $q9->id, 'answer_text' => 'Роутер для API', 'is_correct' => false]);

        $q10 = Question::create([
            'test_id' => $test1->id,
            'question_text' => 'Какая команда применяет все миграции?',
            'type' => 'single',
            'points' => 1,
            'order' => 10,
        ]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'php artisan migrate', 'is_correct' => true]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'php artisan db:migrate', 'is_correct' => false]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'php artisan schema:migrate', 'is_correct' => false]);
        Answer::create(['question_id' => $q10->id, 'answer_text' => 'php artisan up', 'is_correct' => false]);


        $test2 = Test::create([
            'title' => 'Eloquent ORM',
            'description' => 'Тест по работе с Eloquent ORM — основной ORM для работы с базой данных в Laravel.',
            'time_limit' => 12,
            'passing_score' => 75,
            'is_published' => true,
        ]);

        $q11 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод используется для создания новой записи в Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Model::create()', 'is_correct' => true]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Model::insert()', 'is_correct' => false]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Model::save()', 'is_correct' => false]);
        Answer::create(['question_id' => $q11->id, 'answer_text' => 'Model::store()', 'is_correct' => false]);

        $q12 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Как называется связь "один ко многим" в Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'hasMany / belongsTo', 'is_correct' => true]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'hasOne / belongsTo', 'is_correct' => false]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'manyToMany', 'is_correct' => false]);
        Answer::create(['question_id' => $q12->id, 'answer_text' => 'belongsToMany', 'is_correct' => false]);

        $q13 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Что делает метод `$model->load(\'relation\')`?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'Загружает отношение "на лету"', 'is_correct' => true]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'Сохраняет модель в БД', 'is_correct' => false]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'Удаляет модель', 'is_correct' => false]);
        Answer::create(['question_id' => $q13->id, 'answer_text' => 'Создает новое отношение', 'is_correct' => false]);

        $q14 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод используется для пагинации в Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::paginate()', 'is_correct' => true]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::page()', 'is_correct' => false]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::limit()', 'is_correct' => false]);
        Answer::create(['question_id' => $q14->id, 'answer_text' => 'Model::offset()', 'is_correct' => false]);

        $q15 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Что такое `$fillable` в модели Eloquent?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'Поля, разрешенные для массового заполнения', 'is_correct' => true]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'Поля, запрещенные для массового заполнения', 'is_correct' => false]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'Поля, которые никогда не сохраняются', 'is_correct' => false]);
        Answer::create(['question_id' => $q15->id, 'answer_text' => 'Первичный ключ модели', 'is_correct' => false]);

        $q16 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какая связь используется для "многие ко многим"?',
            'type' => 'single',
            'points' => 1,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'belongsToMany', 'is_correct' => true]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'hasMany', 'is_correct' => false]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'hasOne', 'is_correct' => false]);
        Answer::create(['question_id' => $q16->id, 'answer_text' => 'morphMany', 'is_correct' => false]);

        $q17 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Что делает метод `$model->delete()`?',
            'type' => 'single',
            'points' => 1,
            'order' => 7,
        ]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'Удаляет модель из БД', 'is_correct' => true]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'Софт-удаление модели', 'is_correct' => false]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'Удаляет все модели', 'is_correct' => false]);
        Answer::create(['question_id' => $q17->id, 'answer_text' => 'Удаляет только связь', 'is_correct' => false]);

        $q18 = Question::create([
            'test_id' => $test2->id,
            'question_text' => 'Какой метод возвращает первый найденный результат?',
            'type' => 'single',
            'points' => 1,
            'order' => 8,
        ]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'Model::first()', 'is_correct' => true]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'Model::find()', 'is_correct' => false]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'Model::take(1)', 'is_correct' => false]);
        Answer::create(['question_id' => $q18->id, 'answer_text' => 'Model::one()', 'is_correct' => false]);


        $test3 = Test::create([
            'title' => 'Blade и Frontend в Laravel',
            'description' => 'Тест по Blade-шаблонизатору и работе с фронтендом в Laravel.',
            'time_limit' => 10,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $q19 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой синтаксис Blade для условного оператора `if`?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => '@if / @endif', 'is_correct' => true]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => '{{ if }} / {{ endif }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => '<?php if ?> / <?php endif ?>', 'is_correct' => false]);
        Answer::create(['question_id' => $q19->id, 'answer_text' => '<if> / </if>', 'is_correct' => false]);

        $q20 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой метод Blade используется для создания компонентов?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => '<x-component-name />', 'is_correct' => true]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => '@component(\'name\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => '{{ component(\'name\') }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q20->id, 'answer_text' => '@include(\'component\')', 'is_correct' => false]);

        $q21 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Как вывести HTML без экранирования в Blade?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => '{!! $html !!}', 'is_correct' => true]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => '{{ $html }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => '{{ $html | raw }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q21->id, 'answer_text' => '@html($html)', 'is_correct' => false]);

        $q22 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой синтаксис для цикла `foreach` в Blade?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => '@foreach / @endforeach', 'is_correct' => true]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => '{{ foreach }} / {{ endforeach }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => '@for / @endfor', 'is_correct' => false]);
        Answer::create(['question_id' => $q22->id, 'answer_text' => '@each / @endeach', 'is_correct' => false]);

        $q23 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой метод используется для включения другого шаблона?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => '@include(\'view.name\')', 'is_correct' => true]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => '@import(\'view.name\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => '@require(\'view.name\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q23->id, 'answer_text' => '@extends(\'view.name\')', 'is_correct' => false]);

        $q24 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой синтаксис для вывода CSRF-токена в форме?',
            'type' => 'single',
            'points' => 1,
            'order' => 6,
        ]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '@csrf', 'is_correct' => true]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '{{ csrf_token() }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '@token', 'is_correct' => false]);
        Answer::create(['question_id' => $q24->id, 'answer_text' => '{{ token() }}', 'is_correct' => false]);

        $q25 = Question::create([
            'test_id' => $test3->id,
            'question_text' => 'Какой метод используется для создания URL в Blade?',
            'type' => 'single',
            'points' => 1,
            'order' => 7,
        ]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '{{ url(\'path\') }}', 'is_correct' => true]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '{{ route(\'name\') }}', 'is_correct' => false]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '@url(\'path\')', 'is_correct' => false]);
        Answer::create(['question_id' => $q25->id, 'answer_text' => '{{ path(\'path\') }}', 'is_correct' => false]);


        $test4 = Test::create([
            'title' => 'API в Laravel',
            'description' => 'Тест по созданию REST API, аутентификации через Sanctum и работе с JSON.',
            'time_limit' => 8,
            'passing_score' => 80,
            'is_published' => true,
        ]);

        $q26 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой файл используется для API-маршрутов?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => 'routes/api.php', 'is_correct' => true]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => 'routes/web.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => 'routes/console.php', 'is_correct' => false]);
        Answer::create(['question_id' => $q26->id, 'answer_text' => 'routes/channels.php', 'is_correct' => false]);

        $q27 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой пакет используется для API-аутентификации в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => 'Laravel Sanctum', 'is_correct' => true]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => 'Laravel Passport', 'is_correct' => false]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => 'JWT Auth', 'is_correct' => false]);
        Answer::create(['question_id' => $q27->id, 'answer_text' => 'Laravel Fortify', 'is_correct' => false]);

        $q28 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой метод используется для создания токена в Sanctum?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => '$user->createToken(\'name\')', 'is_correct' => true]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => 'Token::create($user)', 'is_correct' => false]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => 'Sanctum::createToken()', 'is_correct' => false]);
        Answer::create(['question_id' => $q28->id, 'answer_text' => '$user->generateToken()', 'is_correct' => false]);

        $q29 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой middleware защищает API-маршруты в Sanctum?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => 'auth:sanctum', 'is_correct' => true]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => 'auth:api', 'is_correct' => false]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => 'sanctum:auth', 'is_correct' => false]);
        Answer::create(['question_id' => $q29->id, 'answer_text' => 'api:auth', 'is_correct' => false]);

        $q30 = Question::create([
            'test_id' => $test4->id,
            'question_text' => 'Какой метод возвращает JSON-ответ в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => 'response()->json()', 'is_correct' => true]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => 'json_response()', 'is_correct' => false]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => '$request->json()', 'is_correct' => false]);
        Answer::create(['question_id' => $q30->id, 'answer_text' => 'Response::json()', 'is_correct' => false]);


        $test5 = Test::create([
            'title' => 'Миграции и Базы данных',
            'description' => 'Тест по работе с миграциями, схемами БД и командами Artisan.',
            'time_limit' => 8,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $q31 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какая команда откатывает последнюю миграцию?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => 'php artisan migrate:rollback', 'is_correct' => true]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => 'php artisan migrate:down', 'is_correct' => false]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => 'php artisan migrate:reset', 'is_correct' => false]);
        Answer::create(['question_id' => $q31->id, 'answer_text' => 'php artisan db:rollback', 'is_correct' => false]);

        $q32 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Что делает команда `php artisan migrate:fresh`?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => 'Удаляет все таблицы и запускает миграции заново', 'is_correct' => true]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => 'Обновляет только новые миграции', 'is_correct' => false]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => 'Показывает список миграций', 'is_correct' => false]);
        Answer::create(['question_id' => $q32->id, 'answer_text' => 'Создает новую миграцию', 'is_correct' => false]);

        $q33 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'В какой папке хранятся миграции?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'database/migrations', 'is_correct' => true]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'app/migrations', 'is_correct' => false]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'storage/migrations', 'is_correct' => false]);
        Answer::create(['question_id' => $q33->id, 'answer_text' => 'resources/migrations', 'is_correct' => false]);

        $q34 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какой метод Schema используется для создания новой таблицы?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'Schema::create()', 'is_correct' => true]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'Schema::make()', 'is_correct' => false]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'Schema::new()', 'is_correct' => false]);
        Answer::create(['question_id' => $q34->id, 'answer_text' => 'Schema::table()', 'is_correct' => false]);

        $q35 = Question::create([
            'test_id' => $test5->id,
            'question_text' => 'Какая команда показывает статус миграций?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => 'php artisan migrate:status', 'is_correct' => true]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => 'php artisan status:migrate', 'is_correct' => false]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => 'php artisan db:status', 'is_correct' => false]);
        Answer::create(['question_id' => $q35->id, 'answer_text' => 'php artisan show:migrations', 'is_correct' => false]);


        $test6 = Test::create([
            'title' => 'Продвинутый Laravel',
            'description' => 'Сложные вопросы по Laravel: события, очереди, кеширование и тестирование.',
            'time_limit' => 10,
            'passing_score' => 80,
            'is_published' => true,
        ]);

        $q36 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какая команда создает событие в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'php artisan make:event', 'is_correct' => true]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'php artisan event:create', 'is_correct' => false]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'php artisan new:event', 'is_correct' => false]);
        Answer::create(['question_id' => $q36->id, 'answer_text' => 'php artisan generate:event', 'is_correct' => false]);

        $q37 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какой драйвер очереди используется по умолчанию в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 2,
        ]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => 'sync', 'is_correct' => true]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => 'redis', 'is_correct' => false]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => 'database', 'is_correct' => false]);
        Answer::create(['question_id' => $q37->id, 'answer_text' => 'beanstalkd', 'is_correct' => false]);

        $q38 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какой метод используется для кеширования данных на 60 минут?',
            'type' => 'single',
            'points' => 1,
            'order' => 3,
        ]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'Cache::put($key, $data, 3600)', 'is_correct' => true]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'Cache::store($key, $data, 60)', 'is_correct' => false]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'Cache::set($key, $data, 60)', 'is_correct' => false]);
        Answer::create(['question_id' => $q38->id, 'answer_text' => 'Cache::add($key, $data, 3600)', 'is_correct' => false]);

        $q39 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какой пакет используется для тестирования в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 4,
        ]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'PHPUnit', 'is_correct' => true]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'Pest', 'is_correct' => false]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'Codeception', 'is_correct' => false]);
        Answer::create(['question_id' => $q39->id, 'answer_text' => 'Behat', 'is_correct' => false]);

        $q40 = Question::create([
            'test_id' => $test6->id,
            'question_text' => 'Какой метод используется для отправки email в Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 5,
        ]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'Mail::to()->send()', 'is_correct' => true]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'Email::send()', 'is_correct' => false]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'Mailer::send()', 'is_correct' => false]);
        Answer::create(['question_id' => $q40->id, 'answer_text' => 'SendMail::to()', 'is_correct' => false]);

        $this->command->info('Тесты успешно созданы!');
        $this->command->info('Всего создано: ' . Test::count() . ' тестов');
        $this->command->info('Всего вопросов: ' . Question::count());
        $this->command->info('Всего ответов: ' . Answer::count());
    }
}
