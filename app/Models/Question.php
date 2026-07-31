<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'question_text',
        'type',
        'points',
        'order'
    ];

    // Связи
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    //Вычисляемые поля
    public function getCorrectAnswersAttribute()
    {
        return $this->answers->where('is_correct', true);
    }

    public function getIsMultipleAttribute()
    {
        return $this->type === 'multiple';
    }
}
