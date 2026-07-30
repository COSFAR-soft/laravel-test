<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>{{ __('Доступные тесты по Laravel') }}</span>
        </div>
    </x-slot>

    @if(session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @endif

    <div class="row">
        @forelse($tests as $test)
            @php
                $userResult = $test->results->where('user_id', auth()->id())->whereNotNull('completed_at')->first();
            @endphp
            <div class="col-md-4 mb-4">
                <x-tests.test-card :test="$test" :userResult="$userResult" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-info-circle display-4 d-block mb-3"></i>
                    <h4>{{ __('Нет доступных тестов') }}</h4>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
