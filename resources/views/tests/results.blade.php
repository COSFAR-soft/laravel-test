<x-app-layout>
    <x-slot name="header">
        <x-tests.results.result-header :result="$result" :test="$test" />
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body py-5">
                    <x-tests.results.result-stats :result="$result" :test="$test" />
                    <x-tests.results.answer-details :result="$result" :test="$test" />
                    <hr class="my-4">
                    <x-tests.results.actions :test="$test" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
