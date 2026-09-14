<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Laundry</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="login-page">

    <div class="login-container">

        <!-- =========================
             BAGIAN KIRI
        ========================== -->

        <div class="login-left">

            <img
                src="{{ asset('image/laundry-login.png') }}"
                alt="Laundry"
                class="login-logo"
            >

        </div>


        <!-- =========================
             BAGIAN KANAN
        ========================== -->

        <div class="login-right">

            <div class="login-form-container">

                <h1>Login</h1>

                <p class="login-subtitle">
                    Silahkan login untuk melanjutkan
                </p>


                @if ($errors->any())

                    <div class="login-error">

                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach

                    </div>

                @endif


                <form method="POST" action="{{ route('login') }}">

                    @csrf


                    <!-- USERNAME -->

                    <div class="login-input-group">

                        <label for="email">
                            Username
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan username"
                            required
                            autocomplete="email"
                            autofocus
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="login-input-group">

                        <label for="password">
                            Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword()"
                            >
                                <span id="eye-icon">◉</span>
                            </button>

                        </div>

                    </div>


                    <!-- INGAT SAYA + LUPA PASSWORD -->

                    <div class="login-options">

                        <label class="remember-me">

                            <input
                                type="checkbox"
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >

                            <span>
                                Ingat saya
                            </span>

                        </label>


                        @if (Route::has('password.request'))

                            <a href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>

                        @endif

                    </div>


                    <!-- REGISTER -->

                    @if (Route::has('register'))

                        <div class="register-link">

                            <a href="{{ route('register') }}">
                                Register
                            </a>

                        </div>

                    @endif


                    <!-- LOGIN BUTTON -->

                    <button
                        type="submit"
                        class="login-button"
                    >
                        LOGIN
                    </button>

                </form>

            </div>

        </div>

    </div>


    <!-- =========================
         JAVASCRIPT PASSWORD
    ========================== -->

    <script>

        function togglePassword() {

            const password =
                document.getElementById('password');

            const icon =
                document.getElementById('eye-icon');


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