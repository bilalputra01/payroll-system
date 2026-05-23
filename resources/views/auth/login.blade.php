<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — HRIS & Payroll</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garant:wght@400;600;700&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <canvas id="bg-canvas"></canvas>

    <div class="layout">

        <!-- LEFT PANEL -->
        <div class="panel-left">
            <div class="logo-mark">
                <div class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="logo-text">Divisi HRD</span>
            </div>

            <div class="panel-left-main">
                <h1>PT. Teknologi <br><em>Masa Depan</em></h1>
                <p class="subtitle">Gunakan kredensial resmi Anda untuk mengakses portal HRIS & Payroll perusahaan
                    dengan aman.</p>
            </div>

            <div class="stats-row">
                <div>
                    <div class="stat-value">100%</div>
                    <div class="stat-label">Terenkripsi</div>
                </div>
                <div class="stat-divider"></div>
                <div>
                    <div class="stat-value">Realtime</div>
                    <div class="stat-label">Data Absensi</div>
                </div>
                <div class="stat-divider"></div>
                <div>
                    <div class="stat-value">24/7</div>
                    <div class="stat-label">Akses Portal</div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL / FORM -->
        <div class="panel-right">
            <div class="form-card">
                <p class="card-label">Autentikasi Akun</p>
                <h2 class="card-title">Selamat datang</h2>
                <p class="card-desc">Masuk dengan username dan password yang telah didaftarkan.</p>
                <div class="divider"></div>

                <!-- Session Status -->
                <x-auth-session-status class="session-status" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Username -->
                    <div class="field">
                        <x-input-label for="username" class="field-label" :value="__('Username')" />
                        <x-text-input id="username" class="field-input" type="text" name="username"
                            :value="old('username')" placeholder="Masukkan username Anda" required autofocus
                            autocomplete="off" />
                        <x-input-error :messages="$errors->get('username')" class="field-error" />
                    </div>

                    <!-- Password -->
                    <div class="field">
                        <x-input-label for="password" class="field-label" :value="__('Password')" />
                        <x-text-input id="password" class="field-input" type="password" name="password"
                            placeholder="••••••••" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="field-error" />
                    </div>

                    <!-- Remember me + Forgot password -->
                    <div class="row-bottom">
                        <label for="remember_me" class="remember-label">
                            <input id="remember_me" type="checkbox" class="remember-check" name="remember">
                            <span class="remember-text">{{ __('Ingat saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-login">
                        <span>{{ __('Masuk ke Sistem') }}</span>
                        <span class="btn-arrow">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </button>

                    <div class="security-note">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Akses terproteksi · Khusus akun terdaftar</span>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/welcome.js') }}"></script>

</body>

</html>
