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

        // Test → Questions
        $this->assertTrue($test->questions->contains($question));
        $this->assertCount(1, $test->questions);

        // Question → Answers
        $this->assertTrue($question->answers->contains($answer));
        $this->assertCount(1, $question->answers);

        // Question → Test
        $this->assertEquals($test->id, $question->test->id);
        $this->assertInstanceOf(Test::class, $question->test);

        // Answer → Question
        $this->assertEquals($question->id, $answer->question->id);
        $this->assertInstanceOf(Question::class, $answer->question);
    }

    /** @test */
    public function test_has_many_questions()
    {
        $test = Test::factory()
            ->has(Question::factory()->count(5))
            ->create();

        $this->assertCount(5, $test->questions);
        $this->assertInstanceOf(Question::class, $test->questions->first());
    }

    /** @test */
    public function test_has_many_results()
    {
        $test = Test::factory()
            ->has(TestResult::factory()->count(3), 'results')
            ->create();

        $this->assertCount(3, $test->results);
        $this->assertInstanceOf(TestResult::class, $test->results->first());
    }

    /** @test */
    public function user_has_many_results()
    {
        $user = User::factory()
            ->has(TestResult::factory()->count(4), 'results')
            ->create();

        $this->assertCount(4, $user->results);
        $this->assertInstanceOf(TestResult::class, $user->results->first());
    }

    /** @test */
    public function question_has_many_answers()
    {
        $question = Question::factory()
            ->has(Answer::factory()->count(3))
            ->create();

        $this->assertCount(3, $question->answers);
        $this->assertInstanceOf(Answer::class, $question->answers->first());
    }

    /** @test */
    public function question_belongs_to_test()
    {
        $question = Question::factory()->create();
        $this->assertInstanceOf(Test::class, $question->test);
    }

    /** @test */
    public function answer_belongs_to_question()
    {
        $answer = Answer::factory()->create();
        $this->assertInstanceOf(Question::class, $answer->question);
    }

    /** @test */
    public function test_calculated_attributes()
    {
        $test = Test::factory()->create();
        $question1 = Question::factory()->create([
            'test_id' => $test->id,
            'points' => 2,
        ]);
        $question2 = Question::factory()->create([
            'test_id' => $test->id,
            'points' => 3,
        ]);

        $this->assertEquals(5, $test->total_points);
        $this->assertEquals(2, $test->questions_count);
    }

    /** @test */
    public function question_calculated_attributes()
    {
        $question = Question::factory()->create(['type' => 'multiple']);
        $correct = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        $this->assertCount(1, $question->correct_answers);
        $this->assertTrue($question->correct_answers->contains($correct));
        $this->assertTrue($question->is_multiple);  // ← используем is_multiple как свойство
    }

    /** @test */
    public function test_result_calculations()
    {
        $user = User::factory()->create();
        $test = Test::factory()->create(['passing_score' => 70]);
        $question = Question::factory()->create(['test_id' => $test->id]);

        $result = TestResult::factory()->create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'total_questions' => 10,
            'correct_answers' => 8,
            'score' => 80,
            'started_at' => now()->subMinutes(15),
            'completed_at' => now(),
        ]);

        // percentage
        $this->assertEquals(80, $result->percentage);

        // score_percentage
        $this->assertEquals(80, $result->score_percentage);

        // is_passed
        $this->assertTrue($result->is_passed);

        // time_spent
        $this->assertEquals(15, $result->time_spent);
        $this->assertNotNull($result->time_spent);
    }

    /** @test */
    public function test_result_is_not_passed_when_score_below_passing()
    {
        $test = Test::factory()->create(['passing_score' => 70]);
        $result = TestResult::factory()->create([
            'test_id' => $test->id,
            'score' => 50,
            'completed_at' => now(),
        ]);

        $this->assertFalse($result->is_passed);
    }

    /** @test */
    public function test_result_handles_zero_questions()
    {
        $result = TestResult::factory()->create([
            'total_questions' => 0,
            'correct_answers' => 0,
            'score' => 0,
            'completed_at' => now(),
        ]);

        $this->assertEquals(0, $result->percentage);
        $this->assertEquals(0, $result->score_percentage);
    }

    /** @test */
    public function test_result_time_spent_null_when_not_completed()
    {
        $result = TestResult::factory()->create([
            'completed_at' => null,
        ]);

        $this->assertNull($result->time_spent);
    }

    /** @test */
    public function test_published_scope()
    {
        Test::factory()->count(3)->create(['is_published' => true]);
        Test::factory()->count(2)->create(['is_published' => false]);

        $published = Test::published()->get();

        $this->assertCount(3, $published);
        $this->assertTrue($published->every(fn($t) => $t->is_published === true));
    }

    /** @test */
    public function test_is_published_cast_to_boolean()
    {
        $test = Test::factory()->create(['is_published' => 1]);
        $this->assertTrue($test->is_published);

        $test2 = Test::factory()->create(['is_published' => 0]);
        $this->assertFalse($test2->is_published);
    }

    /** @test */
    public function test_answers_cast_to_array()
    {
        $result = TestResult::factory()->create([
            'answers' => ['question_1' => 'answer_1', 'question_2' => 'answer_2'],
        ]);

        $this->assertIsArray($result->answers);
        $this->assertArrayHasKey('question_1', $result->answers);
        $this->assertEquals('answer_2', $result->answers['question_2']);
    }
}
