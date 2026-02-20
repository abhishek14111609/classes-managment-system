<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expired — Classes Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .expired-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            padding: 50px 40px;
            max-width: 540px;
            width: 100%;
            text-align: center;
        }

        .expired-icon-ring {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 48px;
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.2);
        }

        .divider {
            border-top: 1px dashed #dee2e6;
            margin: 28px 0;
        }

        .contact-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 18px 20px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="expired-card">

        <div class="expired-icon-ring">⏰</div>

        <h2 class="fw-bold mb-2">Subscription Expired</h2>
        <p class="text-muted mb-0">
            Your school's subscription plan has expired. All features are currently
            restricted until the subscription is renewed.
        </p>

        <div class="divider"></div>

        {{-- School info if available --}}
        @auth
            <div class="contact-info mb-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-building text-primary fs-5"></i>
                    <strong>{{ auth()->user()->school->name ?? 'Your School' }}</strong>
                </div>
                <p class="mb-0 small text-muted">
                    Logged in as <strong>{{ auth()->user()->name }}</strong>
                    ({{ auth()->user()->email }})
                </p>
            </div>
        @endauth

        <div class="alert alert-warning text-start border-0" style="border-radius:12px;">
            <div class="d-flex gap-2">
                <i class="bi bi-info-circle-fill text-warning fs-5 mt-1"></i>
                <div>
                    <strong>What to do next?</strong>
                    <ul class="mb-0 mt-1 small">
                        <li>Contact your <strong>Super Administrator</strong> to renew the plan.</li>
                        <li>All your data is safely preserved — it will be accessible once renewed.</li>
                        <li>No data has been deleted due to subscription expiry.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-2 mt-3">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-left me-2"></i> Back to Login
                </a>
            @endauth
        </div>

        <p class="text-muted small mt-4 mb-0">
            <i class="bi bi-shield-check me-1"></i>
            Classes Management System &copy; {{ date('Y') }}
        </p>
    </div>
</body>

</html>