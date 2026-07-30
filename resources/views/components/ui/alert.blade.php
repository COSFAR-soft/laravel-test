@props([
    'type' => 'info',
    'message' => null,
    'dismissible' => true
])

@php
    $types = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];

    $icons = [
        'success' => 'bi-check-circle-fill',
        'danger' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'info' => 'bi-info-circle-fill',
    ];
@endphp

@if($message)
    <div class="alert {{ $types[$type] ?? 'alert-info' }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi {{ $icons[$type] ?? 'bi-info-circle-fill' }} me-2"></i>
            <div>{{ $message }}</div>
        </div>
        @if($dismissible)
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        @endif
    </div>
@endif
