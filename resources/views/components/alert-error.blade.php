@props(['messages'])
@if ($messages)
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <span class="alert-icon text-danger me-2">
            <i class="ti ti-ban ti-xs"></i>
        </span>
        @foreach ((array) $messages as $message)
            {{ $message }}
        @endforeach
    </div>
@endif
