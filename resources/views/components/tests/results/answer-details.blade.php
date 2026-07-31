@props([
    'result',
    'test'
])

@if($result->answers)
    <div class="mt-5 text-start">
        <h6 class="mb-3"><i class="bi bi-list-check"></i> Детали ответов:</h6>
        <div class="bg-light p-3 rounded" style="font-size: 13px; max-height: 500px; overflow: auto;">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width: 50px;">№</th>
                    <th>Вопрос</th>
                    <th style="width: 70px;">Баллы</th>
                    <th style="width: 150px;">Ответ пользователя</th>
                    <th style="width: 150px;">Правильный ответ</th>
                    <th style="width: 50px;">Статус</th>
                </tr>
                </thead>
                <tbody>
                @php $totalEarned = 0; @endphp
                @foreach($test->questions as $q)
                    @php
                        $correctTexts = $q->answers->where('is_correct', true)->pluck('answer_text')->sort()->values()->toArray();

                        $userAnswer = $result->answers[$q->id] ?? null;
                        if (!is_array($userAnswer) && !is_null($userAnswer)) {
                            $userAnswer = [$userAnswer];
                        }
                        $userIds = is_array($userAnswer) ? $userAnswer : [];
                        sort($userIds);

                        $userTexts = [];
                        if (!empty($userIds)) {
                            $userTexts = $q->answers->whereIn('id', $userIds)->pluck('answer_text')->sort()->values()->toArray();
                        }

                        $isCorrect = $correctTexts === $userTexts;
                        if ($isCorrect) {
                            $totalEarned += $q->points;
                        }

                        $correctText = implode(', ', $correctTexts);
                        $userText = !empty($userTexts) ? implode(', ', $userTexts) : '(не выбран)';
                    @endphp
                    <tr class="{{ $isCorrect ? 'table-success' : 'table-danger' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start">{{ Str::limit($q->question_text, 60) }}</td>
                        <td class="text-center">{{ $q->points }}</td>
                        <td class="text-start">{{ $userText }}</td>
                        <td class="text-start">{{ $correctText }}</td>
                        <td class="text-center">
                            {!! $isCorrect ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>' !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="2" class="text-end">Итого:</td>
                    <td class="text-center">{{ $test->questions->sum('points') }}</td>
                    <td colspan="2" class="text-center">{{ $totalEarned }} баллов</td>
                    <td class="text-center">{{ round(($totalEarned / max($test->questions->sum('points'), 1)) * 100) }}%</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endif
