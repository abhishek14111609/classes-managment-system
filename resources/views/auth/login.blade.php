<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Classes Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/modern-ui.css') }}">
</head>

<body>

    <div class="login-split-page">
        <!-- Left Side: Branding & Art -->
        <div class="login-left">
            <div class="login-left-content">
                <div class="d-flex align-items-center gap-3 mb-5">
                    <i class="bi bi-mortarboard-fill fs-2 text-black"></i>
                    <h3 class="font-heading m-0 text-black">Webvibe</h3>
                </div>

                <div class="my-auto">
                    <h1 class="display-3 fw-bold mb-4 text-black">Manage your institute with <span
                            class="text-black">elegance.</span></h1>
                    <p class="h5 fw-light text-black-50 lh-lg mb-5">
                        Streamline attendance, fees, and student tracking in one unified, beautiful platform tailored
                        for modern education.
                    </p>

                    <div class="d-flex gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-white bg-opacity-10 p-2 rounded-circle">
                                <i class="bi bi-shield-check text-black"></i>
                            </div>
                            <span class="small fw-medium text-black">Secure</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-white bg-opacity-10 p-2 rounded-circle">
                                <i class="bi bi-lightning-charge text-black"></i>
                            </div>
                            <span class="small fw-medium text-black">Fast</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-white bg-opacity-10 p-2 rounded-circle">
                                <i class="bi bi-phone text-black"></i>
                            </div>
                            <span class="small fw-medium text-black">Mobile Ready</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <small class="text-b text-black-50">&copy; {{ date('Y') }} Classes Management System.</small>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-right">
            <div class="w-100" style="max-width: 400px;">
                <div class="mb-5">
                    <h2 class="fw-bold fs-2 mb-2 text-main">Welcome back</h2>
                    <p class="text-muted">Please enter your details to sign in.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger bg-danger-subtle text-danger border-0 d-flex align-items-center mb-4"
                        role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success bg-success-subtle text-success border-0 d-flex align-items-center mb-4"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="username" class="form-label fw-semibold small text-uppercase text-muted">Email or
                            Username</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0" id="username"
                            name="username" value="{{ old('username') }}" placeholder="name@example.com" required
                            autofocus>
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password"
                                class="form-label fw-semibold small text-uppercase text-muted">Password</label>
                            <!-- Password Recovery removed via Admin Directive -->
                        </div>
                        <input type="password" class="form-control form-control-lg bg-light border-0" id="password"
                            name="password" placeholder="••••••••" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small user-select-none" for="remember">Remember
                                for 30 days</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-lg mb-4">
                        Sign In
                    </button>

                    <div class="text-center">
                        <p class="text-muted small">Don't have an account? <span class="text-main fw-semibold">Contact
                                your administrator.</span></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>