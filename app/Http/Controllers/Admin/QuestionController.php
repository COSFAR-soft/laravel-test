<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function index(Test $test)
    {
        $questions = $test->questions()->with('answers')->orderBy('order')->get();
        return response()->json($questions);
    }

    public function store(Request $request, Test $test)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' => 'required|in:single,multiple,free,scale',
            'points' => 'required|integer|min:1',
            'answers' => 'array|required_if:type,single,multiple',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $question = Question::create([
            'test_id' => $test->id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'points' => $request->points,
            'order' => $test->questions()->max('order') + 1,
            'min_count' => $request->min_count ?? 0,
            'max_count' => $request->max_count ?? 0,
            'is_required' => $request->has('is_required'),
            'has_other' => $request->has('has_other'),
            'diapason_start' => $request->diapason_start ?? 0,
            'diapason_end' => $request->diapason_end ?? 10,
        ]);

        if (in_array($request->type, ['single', 'multiple'])) {
            foreach ($request->answers as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Вопрос добавлен!', 'question' => $question->load('answers')]);
    }

    public function update(Request $request, Question $question)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' => 'required|in:single,multiple,free,scale',
            'points' => 'required|integer|min:1',
            'answers' => 'array|required_if:type,single,multiple',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $question->update([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'points' => $request->points,
            'min_count' => $request->min_count ?? 0,
            'max_count' => $request->max_count ?? 0,
            'is_required' => $request->has('is_required'),
            'has_other' => $request->has('has_other'),
            'diapason_start' => $request->diapason_start ?? 0,
            'diapason_end' => $request->diapason_end ?? 10,
        ]);

        if (in_array($request->type, ['single', 'multiple'])) {
            $existingIds = $question->answers->pluck('id')->toArray();
            $newIds = [];
            foreach ($request->answers as $answerData) {
                if (isset($answerData['id']) && in_array($answerData['id'], $existingIds)) {
                    $answer = Answer::find($answerData['id']);
                    $answer->update(['answer_text' => $answerData['text'], 'is_correct' => $answerData['is_correct'] ?? false]);
                    $newIds[] = $answerData['id'];
                } else {
                    $answer = Answer::create(['question_id' => $question->id, 'answer_text' => $answerData['text'], 'is_correct' => $answerData['is_correct'] ?? false]);
                    $newIds[] = $answer->id;
                }
            }
            $toDelete = array_diff($existingIds, $newIds);
            Answer::whereIn('id', $toDelete)->delete();
        }

        return response()->json(['success' => true, 'message' => 'Вопрос обновлен!', 'question' => $question->fresh()->load('answers')]);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json(['success' => true, 'message' => 'Вопрос удален!']);
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        foreach ($request->questions as $item) {
            Question::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Порядок вопросов обновлен!']);
    }

    public function partial(Request $request)
    {
        $type = $request->input('type', 'single');
        $questionId = $request->input('question_id');
        $question = null;
        $answers = [];

        if ($questionId) {
            $question = Question::with('answers')->find($questionId);
            if ($question) $answers = $question->answers;
        }

        $view = match($type) {
            'single' => 'admin.questions.single-choice',
            'multiple' => 'admin.questions.multiple-choice',
            'free' => 'admin.questions.free-answer',
            'scale' => 'admin.questions.scale',
            default => 'admin.questions.single-choice',
        };

        $html = view($view, ['question' => $question, 'answers' => $answers])->render();
        return response()->json(['success' => true, 'html' => $html]);
    }
}
