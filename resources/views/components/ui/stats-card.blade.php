@props([
    'label',
    'value',
    'color' => 'primary'
])

<div class="col-md-3 col-6 mb-3">
    <div class="card bg-{{ $color }} text-white">
        <div class="card-body text-center">
            <div class="display-6">{{ $value }}</div>
            <p class="mb-0">{{ $label }}</p>
        </div>
    </div>
</div>
