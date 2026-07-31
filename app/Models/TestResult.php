<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TestResult
 *
 * Модель для хранения результатов тестирования.
 *
 * @property int $id
 * @property int $user_id
 * @property int $test_id
 * @property int $score
 * @property int $total_questions
 * @property int $correct_answers
 * @property array $answers
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon|null $completed_at
 *
 * @property-read float $percentage
 * @property-read float $score_percentage
 * @property-read bool $is_passed
 * @property-read int|null $time_spent
 */
class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'score',
        'total_questions',
        'correct_answers',
        'answers',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Связи

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    //вычисляемые поля

    /**
     * Процент правильных ответов (по количеству)
     */
    public function getPercentageAttribute()
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        return round(($this->correct_answers / $this->total_questions) * 100);
    }

    /**
     * Процент набранных баллов
     */
    public function getScorePercentageAttribute()
    {
        if (!$this->test) {
            return 0;
        }
        $totalPoints = $this->test->questions->sum('points');
        if ($totalPoints == 0) {
            return 0;
        }
        return round(($this->score / $totalPoints) * 100);
    }

    /**
     * Статус прохождения теста
     */
    public function getIsPassedAttribute()
    {
        return $this->score_percentage >= $this->test->passing_score;
    }

    /**
     * Время прохождения в минутах
     */
    public function getTimeSpentAttribute()
    {
        if (!$this->completed_at) {
            return null;
        }
        return $this->started_at->diffInMinutes($this->completed_at);
    }
}
