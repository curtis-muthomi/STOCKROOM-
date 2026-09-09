@if (session('success'))
    <div class="toast toast-success" role="status">
        <span class="toast-icon">✓</span>
        <span>{{ session('success') }}</span>
        <button type="button" class="toast-close" aria-label="Dismiss notification">×</button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="toast toast-error" role="alert">
        <span class="toast-icon">!</span>
        <div>
            <strong>Please check the highlighted fields.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="toast-close" aria-label="Dismiss notification">×</button>
    </div>
@endif
