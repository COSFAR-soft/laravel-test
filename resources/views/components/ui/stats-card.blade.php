@props(['title', 'value', 'color' => 'primary'])

<div class="card bg-{{ $color }} text-white">
    <div class="card-body text-center">
        <div class="display-6">{{ $value }}</div>
        <p class="mb-0">{{ $title }}</p>
    </div>
</div>
