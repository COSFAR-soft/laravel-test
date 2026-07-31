<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'time_limit',
        'passing_score',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Связи
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }

    // Вычисляемые поля
    public function getQuestionsCountAttribute()
    {
        return $this->questions->count();
    }

    public function getTotalPointsAttribute()
    {
        return $this->questions->sum('points');
    }

    // Фильтрация
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
