<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        return view('booking.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'string', 'email', 'max:255'],
            'booking_date' => ['required', 'date'],
            'booking_type' => ['required', 'in:full_day,half_day,custom'],
            'booking_slot' => ['nullable', 'in:first_half,second_half'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
        ]);

        $type = $validated['booking_type'];
        $date = $validated['booking_date'];

        if ($type === 'half_day' && empty($validated['booking_slot'])) {
            return back()->withErrors(['booking_slot' => 'Booking slot is required for half day booking.'])->withInput();
        }

        if ($type === 'custom') {
            if (empty($validated['start_time']) || empty($validated['end_time'])) {
                return back()->withErrors(['start_time' => 'Start and end time are required for custom booking.'])->withInput();
            }

            if ($validated['start_time'] >= $validated['end_time']) {
                return back()->withErrors(['start_time' => 'Start time must be before end time.'])->withInput();
            }
        }

        if ($this->hasConflict($validated)) {
            return back()->withErrors(['booking_date' => 'The selected booking overlaps with an existing booking.'])->withInput();
        }

        Booking::create([
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'booking_date' => $validated['booking_date'],
            'booking_type' => $validated['booking_type'],
            'booking_slot' => $validated['booking_slot'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
        ]);

        return redirect()->route('booking.form')->with('status', 'Booking created successfully.');
    }

    protected function hasConflict(array $data): bool
    {
        $date = $data['booking_date'];
        $type = $data['booking_type'];
        $slot = $data['booking_slot'] ?? null;
        $start = $data['start_time'] ?? null;
        $end = $data['end_time'] ?? null;

        $firstHalfStart = '09:00';
        $firstHalfEnd = '13:00';
        $secondHalfStart = '14:00';
        $secondHalfEnd = '18:00';

        if (Booking::whereDate('booking_date', $date)->where('booking_type', 'full_day')->exists()) {
            return true;
        }

        if ($type === 'full_day') {
            return Booking::whereDate('booking_date', $date)->exists();
        }

        if ($type === 'half_day') {
            $slotRange = $slot === 'first_half'
                ? [$firstHalfStart, $firstHalfEnd]
                : [$secondHalfStart, $secondHalfEnd];

            $conflict = Booking::whereDate('booking_date', $date)
                ->where(function ($q) use ($slot, $slotRange) {
                    $q->where('booking_type', 'full_day')
                      ->orWhere(function ($q2) use ($slot) {
                          $q2->where('booking_type', 'half_day')
                             ->where('booking_slot', $slot);
                      })
                      ->orWhere(function ($q3) use ($slotRange) {
                          $q3->where('booking_type', 'custom')
                             ->where('end_time', '>', $slotRange[0])
                             ->where('start_time', '<', $slotRange[1]);
                      });
                })
                ->exists();

            return $conflict;
        }

        if ($type === 'custom') {
            $conflict = Booking::whereDate('booking_date', $date)
                ->where(function ($q) use ($start, $end, $firstHalfStart, $firstHalfEnd, $secondHalfStart, $secondHalfEnd) {
                    $q->where('booking_type', 'full_day')
                      ->orWhere(function ($q2) use ($start, $end) {
                          $q2->where('booking_type', 'custom')
                             ->where('end_time', '>', $start)
                             ->where('start_time', '<', $end);
                      })
                      ->orWhere(function ($q3) use ($start, $end, $firstHalfStart, $firstHalfEnd, $secondHalfStart, $secondHalfEnd) {
                          $q3->where('booking_type', 'half_day')
                             ->where(function ($q4) use ($start, $end, $firstHalfStart, $firstHalfEnd, $secondHalfStart, $secondHalfEnd) {
                                 $q4->where(function ($qq) use ($start, $end, $firstHalfStart, $firstHalfEnd) {
                                     $qq->where('booking_slot', 'first_half')
                                        ->whereRaw('(? < ? AND ? > ?)', [$start, $firstHalfEnd, $end, $firstHalfStart]);
                                 })->orWhere(function ($qq2) use ($start, $end, $secondHalfStart, $secondHalfEnd) {
                                     $qq2->where('booking_slot', 'second_half')
                                         ->whereRaw('(? < ? AND ? > ?)', [$start, $secondHalfEnd, $end, $secondHalfStart]);
                                 });
                             });
                      });
                })
                ->exists();

            return $conflict;
        }

        return false;
    }
}
