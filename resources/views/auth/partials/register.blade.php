
<div class="mb-4">
    <h1 class="auth-title fw-bold mb-2">Buat akun</h1>

    <p class="text-secondary mb-0">
        Daftar untuk mulai reservasi.
    </p>
</div>

<form action="{{ route('register.process') }}" method="POST">

    @csrf

    <div class="mb-3">
        <label for="name" class="form-label fw-medium">
            Nama lengkap
        </label>

        <input type="text"
            id="name"
            name="name"
            class="form-control"
            placeholder="Nama lengkap Anda"
            value="{{ $mode === 'register' ? old('name') : '' }}"
            autocomplete="name"
            required>
    </div>

    <div class="mb-3">
        <label for="register-email" class="form-label fw-medium">
            Email
        </label>

        <input type="email"
            id="register-email"
            name="email"
            class="form-control"
            placeholder="nama@email.com"
            value="{{ $mode === 'register' ? old('email') : '' }}"
            autocomplete="email"
            required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-medium">
            Password
        </label>

        <div class="position-relative">
            <input type="password"
                id="password"
                name="password"
                class="form-control password-input"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                minlength="8"
                required>

            <button type="button"
                class="password-toggle"
                data-target="password"
                aria-label="Tampilkan password">

                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label fw-medium">
            Konfirmasi password
        </label>

        <div class="position-relative">
            <input type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control password-input"
                placeholder="Ulangi password"
                autocomplete="new-password"
                required>

            <button type="button"
                class="password-toggle"
                data-target="password_confirmation"
                aria-label="Tampilkan password">

                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit"
        id="register-btn"
        class="btn btn-primary auth-submit w-100"
        disabled>

        Daftar
    </button>

</form>
