
<div class="mb-4">
    <h1 class="auth-title fw-bold mb-2">Masuk</h1>

    <p class="text-secondary mb-0">
        Selamat datang kembali.
    </p>
</div>

<form action="{{ route('login.process') }}" method="POST">

    @csrf

    <div class="mb-3">
        <label for="login-email" class="form-label fw-medium">
            Email
        </label>

        <input type="email"
            id="login-email"
            name="email"
            class="form-control"
            placeholder="nama@email.com"
            value="{{ $mode === 'login' ? old('email') : '' }}"
            autocomplete="email"
            required>
    </div>

    <div class="mb-4">
        <label for="login-password" class="form-label fw-medium">
            Password
        </label>

        <div class="position-relative">
            <input type="password"
                id="login-password"
                name="password"
                class="form-control password-input"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required>

            <button type="button"
                class="password-toggle"
                data-target="login-password"
                aria-label="Tampilkan password">

                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit"
        class="btn btn-primary auth-submit w-100">
        Masuk
    </button>

</form>
