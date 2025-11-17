@extends('layouts.app')

@section('title', 'Verify Email')
@section('heading', 'Verify your email')
@section('subtitle', 'We sent you a secure link to confirm your account')

@section('content')
    @if (! session('status'))
        <p style="font-size:.9rem;color:#4b5563;margin-bottom:1rem;">
            Please check your inbox and click on the verification link we emailed you. Once verified, you can log in and start creating bookings.
        </p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn">Resend verification email</button>
    </form>

    <div class="link-row">
        <form method="POST" action="{{ route('logout') }}" style="margin-top:1rem;">
            @csrf
            <button type="submit" class="btn" style="background: #f3f4f6;color:#111827;box-shadow:none;">Logout</button>
        </form>
    </div>
@endsection
