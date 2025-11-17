@extends('layouts.app')

@section('title', 'Login')
@section('heading', 'Welcome back')
@section('subtitle', 'Access your booking dashboard')

@section('content')
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="field" style="display:flex;align-items:center;gap:.5rem;">
            <input id="remember" type="checkbox" name="remember" style="width:auto;">
            <label for="remember" style="margin:0;font-weight:500;">Remember me</label>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>

    <div class="link-row">
        <span>Don't have an account?</span>
        <a href="{{ route('register') }}">Create one</a>
    </div>
@endsection
