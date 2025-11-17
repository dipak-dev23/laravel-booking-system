@extends('layouts.app')

@section('title', 'Register')
@section('heading', 'Create your account')
@section('subtitle', 'Sign up to manage and track bookings')

@section('content')
    <form method="POST" action="{{ url('/register') }}">
        @csrf
        <div class="field">
            <label for="first_name">First Name</label>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus>
        </div>
        <div class="field">
            <label for="last_name">Last Name</label>
            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn">Create account</button>
    </form>

    <div class="link-row">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}">Login instead</a>
    </div>
@endsection
