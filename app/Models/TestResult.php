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

    // Аксессоры
    public function getPercentageAttribute()
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        return round(($this->correct_answers / $this->total_questions) * 100);
    }

    public function getIsPassedAttribute()
    {
        return $this->percentage >= $this->test->passing_score;
    }

    public function getTimeSpentAttribute()
    {
        if (!$this->completed_at) {
            return null;
        }
        return $this->started_at->diffInMinutes($this->completed_at);
    }
}
