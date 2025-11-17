<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Booking System')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top, #eff6ff 0, #e5e7eb 45%, #f9fafb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
            padding: 2rem 2.25rem 2.25rem;
            border: 1px solid #e5e7eb;
        }
        .card-header {
            margin-bottom: 1.5rem;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 .25rem;
        }
        .card-subtitle {
            font-size: .875rem;
            color: #6b7280;
        }
        form { margin-top: 1rem; }
        label {
            display: block;
            font-size: .85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .25rem;
        }
        input, select {
            width: 100%;
            padding: .55rem .7rem;
            border-radius: .5rem;
            border: 1px solid #d1d5db;
            font-size: .9rem;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        input:focus, select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 1px #2563eb33;
        }
        .field {
            margin-bottom: .9rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            padding: .6rem .9rem;
            border-radius: .6rem;
            border: none;
            background: linear-gradient(to right, #2563eb, #4f46e5);
            color: #ffffff;
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            width: 100%;
            margin-top: .5rem;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.35);
            transition: transform .08s ease-out, box-shadow .08s ease-out, filter .08s;
        }
        .btn:hover {
            filter: brightness(1.03);
            transform: translateY(-1px);
            box-shadow: 0 14px 35px rgba(37, 99, 235, 0.45);
        }
        .btn:active {
            transform: translateY(0);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
        }
        .link-row {
            margin-top: 1rem;
            font-size: .85rem;
            color: #4b5563;
            text-align: center;
        }
        .link-row a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }
        .link-row a:hover {
            text-decoration: underline;
        }
        .errors, .status {
            font-size: .8rem;
            padding: .6rem .75rem;
            border-radius: .6rem;
            margin-bottom: .9rem;
        }
        .errors {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .status {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        ul { margin: 0; padding-left: 1.1rem; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">@yield('heading', 'Booking System')</h1>
        @hasSection('subtitle')
            <p class="card-subtitle">@yield('subtitle')</p>
        @endif
    </div>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
