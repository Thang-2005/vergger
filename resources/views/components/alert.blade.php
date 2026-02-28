@props(['type' => 'info', 'message' => '', 'dismissible' => true, 'icon' => true])

@php
    $typeClass = match($type) {
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        default => 'alert-info'
    };
    
    $iconClass = match($type) {
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'danger' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
        default => 'fa-info-circle'
    };
@endphp

<div class="alert {{ $typeClass }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    @if ($icon)
        <i class="fa {{ $iconClass }} me-2"></i>
    @endif
    
    @if ($message)
        <strong>{{ $message }}</strong>
    @else
        {{ $slot }}
    @endif
    
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
