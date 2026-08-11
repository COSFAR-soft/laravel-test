<?php

namespace Tests\Unit;

use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_model_relations()
    {
        $test = Test::factory()->create();
        $question = Question::factory()->create(['test_id' => $test->id]);
        $answer = Answer::factory()->create(['question_id' => $question->id]);

        $this->assertTrue($test->questions->contains($question));
        $this->assertTrue($question->answers->contains($answer));
        $this->assertEquals($test->id, $question->test->id);
    }

    /** @test */
    public function test_calculated_attributes()
    {
        $test = Test::factory()->create();
        $question = Question::factory()->create([
            'test_id' => $test->id,
            'points' => 2,
        ]);

        $this->assertEquals(2, $test->total_points);
        $this->assertEquals(1, $test->questions_count);
    }

    /** @test */
    public function test_result_calculations()
    {
        $user = User::factory()->create();
        $test = Test::factory()->create();
        $question = Question::factory()->create(['test_id' => $test->id]);

        $result = TestResult::factory()->create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'total_questions' => 1,
            'correct_answers' => 1,
            'score' => 100,
            'completed_at' => now(),
        ]);

        $this->assertEquals(100, $result->percentage);
        $this->assertTrue($result->is_passed);
        $this->assertNotNull($result->time_spent);
    }
}
