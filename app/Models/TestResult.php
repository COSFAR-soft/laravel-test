<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Процент правильных ответов
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
     * Пройден ли тест
     */
    public function getIsPassedAttribute()
    {
        return $this->score_percentage >= $this->test->passing_score;
    }

    /**
     * Затраченное время в минутах
     */
    public function getTimeSpentAttribute()
    {
        if (!$this->completed_at) {
            return null;
        }
        return $this->started_at->diffInMinutes($this->completed_at);
    }
}
