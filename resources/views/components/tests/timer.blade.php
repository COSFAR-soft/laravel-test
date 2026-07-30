@props([
    'timeLeft'
])

<div id="timer" class="badge bg-warning fs-6 p-2">
    <i class="bi bi-clock"></i>
    <span class="ms-1">осталось </span>
    <span id="timeDisplay">{{ sprintf('%02d:%02d', floor($timeLeft / 60), $timeLeft % 60) }}</span>
</div>

<style>
    .animate-pulse {
        animation: pulse 1s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
</style>
