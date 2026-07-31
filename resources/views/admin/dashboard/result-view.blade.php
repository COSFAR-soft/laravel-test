@extends('admin.layouts.admin')

@section('title', 'Результат теста')

@section('content')
    <x-tests.results.result-header :result="$result" :test="$test" :user="$user" />

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body py-5">
                    <x-tests.results.result-stats :result="$result" :test="$test" />
                    <x-tests.results.answer-details :result="$result" :test="$test" />
                </div>
            </div>
        </div>
    </div>
@endsection
