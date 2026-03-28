<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CoachingBooking;
use App\Models\CoachingService;
use App\Models\CoachingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoachingController extends Controller
{
    public function index()
    {
        $services = Auth::user()->coachingServices()
            ->withCount('bookings')
            ->latest()
            ->paginate(10);

        return view('creator.coaching.index', compact('services'));
    }

    public function create()
    {
        return view('creator.coaching.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|in:' . implode(',', CoachingService::DURATION_OPTIONS),
            'meeting_platform' => 'required|in:' . implode(',', array_keys(CoachingService::MEETING_PLATFORMS)),
            'cover_image'      => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['title', 'description', 'price', 'duration_minutes', 'meeting_platform']);
        $data['user_id'] = Auth::id();
        $data['slug'] = CoachingService::generateSlug($data['title']);
        $data['status'] = 'published';
        $data['approval_status'] = 'pending';

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('coaching/covers', 'public');
        }

        $coaching = CoachingService::create($data);

        if (\App\Models\Setting::get('affiliate_enabled')) {
            $coaching->affiliateProduct()->updateOrCreate([], [
                'affiliate_enabled' => $request->boolean('affiliate_enabled'),
                'commission_percentage' => $request->input('affiliate_commission', 0),
            ]);
        }

        return redirect()->route('creator.coaching.index')
            ->with('success', 'Coaching service submitted for review! It will be visible once approved.');
    }

    public function edit(CoachingService $coaching)
    {
        $this->authorizeOwner($coaching);
        return view('creator.coaching.edit', compact('coaching'));
    }

    public function update(Request $request, CoachingService $coaching)
    {
        $this->authorizeOwner($coaching);

        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|in:' . implode(',', CoachingService::DURATION_OPTIONS),
            'meeting_platform' => 'required|in:' . implode(',', array_keys(CoachingService::MEETING_PLATFORMS)),
            'cover_image'      => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['title', 'description', 'price', 'duration_minutes', 'meeting_platform']);

        if ($coaching->approval_status === 'approved') {
            $data['approval_status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($coaching->cover_image) {
                Storage::disk('public')->delete($coaching->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('coaching/covers', 'public');
        }

        $coaching->update($data);

        if (\App\Models\Setting::get('affiliate_enabled')) {
            $coaching->affiliateProduct()->updateOrCreate([], [
                'affiliate_enabled' => $request->boolean('affiliate_enabled'),
                'commission_percentage' => $request->input('affiliate_commission', 0),
            ]);
        }

        return redirect()->route('creator.coaching.index')
            ->with('success', 'Coaching service updated.' . ($coaching->approval_status === 'pending' ? ' It will be re-reviewed.' : ''));
    }

    public function destroy(CoachingService $coaching)
    {
        $this->authorizeOwner($coaching);

        if ($coaching->bookings()->where('status', 'confirmed')->exists()) {
            return back()->with('error', 'Cannot delete a coaching service with confirmed bookings.');
        }

        if ($coaching->cover_image) {
            Storage::disk('public')->delete($coaching->cover_image);
        }

        $coaching->delete();

        return redirect()->route('creator.coaching.index')->with('success', 'Coaching service deleted.');
    }

    // Slot management
    public function slots(CoachingService $coaching)
    {
        $this->authorizeOwner($coaching);
        $slots = $coaching->slots()->orderBy('start_time')->get();
        return view('creator.coaching.slots', compact('coaching', 'slots'));
    }

    public function createSlot(CoachingService $coaching)
    {
        $this->authorizeOwner($coaching);
        return view('creator.coaching.create-slot', compact('coaching'));
    }

    public function storeSlot(Request $request, CoachingService $coaching)
    {
        $this->authorizeOwner($coaching);

        $request->validate([
            'start_time' => 'required|date|after:now',
        ]);

        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($coaching->duration_minutes);

        CoachingSlot::create([
            'coaching_service_id' => $coaching->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect()->route('creator.coaching.slots.index', $coaching)
            ->with('success', 'Slot added.');
    }

    public function destroySlot(CoachingService $coaching, CoachingSlot $slot)
    {
        $this->authorizeOwner($coaching);

        if ($slot->coaching_service_id !== $coaching->id) {
            abort(404);
        }

        if ($slot->is_booked) {
            return back()->with('error', 'Cannot delete a booked slot.');
        }

        $slot->delete();
        return back()->with('success', 'Slot removed.');
    }

    // Booking management
    public function bookings(CoachingService $coaching)
    {
        $this->authorizeOwner($coaching);

        $bookings = $coaching->bookings()
            ->with(['customer', 'slot'])
            ->latest()
            ->paginate(20);

        return view('creator.coaching.bookings', compact('coaching', 'bookings'));
    }

    public function updateMeetingLink(Request $request, CoachingBooking $booking)
    {
        $service = $booking->service;
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['meeting_link' => 'required|url|max:500']);
        $booking->update(['meeting_link' => $request->meeting_link]);

        return back()->with('success', 'Meeting link updated.');
    }

    public function markCompleted(CoachingBooking $booking)
    {
        $service = $booking->service;
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->update(['status' => 'completed']);
        return back()->with('success', 'Session marked as completed.');
    }

    protected function authorizeOwner(CoachingService $coaching): void
    {
        if ($coaching->user_id !== Auth::id()) {
            abort(403, 'You do not own this coaching service.');
        }
    }
}
