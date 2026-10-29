
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Laundry</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="login-page">

    <div class="login-container">

        <!-- BAGIAN KIRI -->
        <div class="login-left">
            <img
                src="{{ asset('image/laundry-login.png') }}"
                alt="Laundry"
                class="login-logo"
            >
        </div>

        <!-- BAGIAN KANAN -->
        <div class="login-right">

            <div class="login-form-container">

                <h1>Register</h1>

                <p class="login-subtitle">
                    Buat akun untuk memulai usaha laundry
                </p>

                @if ($errors->any())
                    <div class="login-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NAMA LENGKAP -->
                    <div class="login-input-group">
                        <label for="name">Nama Lengkap</label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                            autocomplete="name"
                            autofocus
                        >
                    </div>

                    <!-- NAMA LAUNDRY -->
                    <div class="login-input-group">
                        <label for="nama_laundry">Nama Laundry</label>

                        <input
                            id="nama_laundry"
                            type="text"
                            name="nama_laundry"
                            value="{{ old('nama_laundry') }}"
                            placeholder="Contoh: Laundry Bersih"
                            maxlength="255"
                            required
                        >
                    </div>

                    <!-- EMAIL -->
                    <div class="login-input-group">
                        <label for="email">Email</label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email aktif"
                            required
                            autocomplete="email"
                        >
                    </div>

                    <!-- PASSWORD -->
                    <div class="login-input-group">
                        <label for="password">Password</label>

                        <div class="password-wrapper">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Buat password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password', 'eye-icon-password')"
                            >
                                <span id="eye-icon-password">◉</span>
                            </button>
                        </div>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="login-input-group">
                        <label for="password-confirm">
                            Konfirmasi Password
                        </label>

                        <div class="password-wrapper">
                            <input
                                id="password-confirm"
                                type="password"
                                name="password_confirmation"
                                placeholder="Ulangi password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password-confirm', 'eye-icon-confirm')"
                            >
                                <span id="eye-icon-confirm">◉</span>
                            </button>
                        </div>
                    </div>

                    <!-- LINK LOGIN -->
                    <div class="register-link">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Login</a>
                    </div>

                    <!-- TOMBOL DAFTAR -->
                    <button type="submit" class="login-button">
                        DAFTAR SEKARANG
                    </button>

                </form>

            </div>
        </div>

    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const password = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (password.type === 'password') {
                password.type = 'text';
                icon.textContent = '◎';
            } else {
                password.type = 'password';
                icon.textContent = '◉';
            }
        }
    </script>

</body>
</html>
