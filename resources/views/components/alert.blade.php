@props([
    'type' => 'info',
    'message' => '',
    'dismissible' => false,
])

@php
    $bgColors = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
    $bgClass = $bgColors[$type] ?? 'alert-info';
@endphp

<div class="alert {{ $bgClass }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    @if ($message)
        <div>{!! $message !!}</div>
    @else
        {{ $slot }}
    @endif
    
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
