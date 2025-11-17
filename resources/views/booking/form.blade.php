@extends('layouts.app')

@section('title', 'New Booking')
@section('heading', 'Create a booking')
@section('subtitle', 'Capture customer details and reserve a slot')

@section('content')
    <form method="POST" action="{{ route('booking.store') }}">
        @csrf
        <div class="field">
            <label for="customer_name">Customer Name</label>
            <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}" required>
        </div>
        <div class="field">
            <label for="customer_email">Customer Email</label>
            <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email') }}" required>
        </div>
        <div class="field">
            <label for="booking_date">Booking Date</label>
            <input id="booking_date" type="date" name="booking_date" value="{{ old('booking_date') }}" required>
        </div>
        <div class="field">
            <label for="booking_type">Booking Type</label>
            <select name="booking_type" id="booking_type" required>
                <option value="">-- Select --</option>
                <option value="full_day" {{ old('booking_type') === 'full_day' ? 'selected' : '' }}>Full Day</option>
                <option value="half_day" {{ old('booking_type') === 'half_day' ? 'selected' : '' }}>Half Day</option>
                <option value="custom" {{ old('booking_type') === 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </div>
        <div class="field" id="half_day_slot" style="display:none;">
            <label for="booking_slot">Booking Slot</label>
            <select name="booking_slot" id="booking_slot">
                <option value="">-- Select --</option>
                <option value="first_half" {{ old('booking_slot') === 'first_half' ? 'selected' : '' }}>First Half</option>
                <option value="second_half" {{ old('booking_slot') === 'second_half' ? 'selected' : '' }}>Second Half</option>
            </select>
        </div>
        <div class="field" id="custom_time" style="display:none;">
            <label for="start_time">From Time</label>
            <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}">
            <label for="end_time" style="margin-top:.4rem;">To Time</label>
            <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}">
        </div>
        <button type="submit" class="btn">Create booking</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:1rem;">
        @csrf
        <button type="submit" class="btn" style="background:#f3f4f6;color:#111827;box-shadow:none;">Logout</button>
    </form>

    <script>
        function updateVisibility() {
            const type = document.getElementById('booking_type').value;
            document.getElementById('half_day_slot').style.display = type === 'half_day' ? 'block' : 'none';
            document.getElementById('custom_time').style.display = type === 'custom' ? 'block' : 'none';
        }
        document.getElementById('booking_type').addEventListener('change', updateVisibility);
        window.addEventListener('load', updateVisibility);
    </script>
@endsection
