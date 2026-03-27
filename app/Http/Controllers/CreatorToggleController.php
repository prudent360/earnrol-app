<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\CreatorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatorToggleController extends Controller
{
    /**
     * Show the creator application form.
     */
    public function showApplicationForm()
    {
        if (!Setting::get('creator_enabled')) {
            return back()->with('error', 'Creator applications are currently closed.');
        }

        $user = Auth::user();

        if ($user->isCreator()) {
            return redirect()->route('creator.dashboard');
        }

        $application = $user->creatorApplication;

        return view('creator.apply', compact('application'));
    }

    /**
     * Submit a creator application.
     */
    public function apply(Request $request)
    {
        if (!Setting::get('creator_enabled')) {
            return back()->with('error', 'Creator applications are currently closed.');
        }

        $user = Auth::user();

        if ($user->isCreator()) {
            return redirect()->route('creator.dashboard');
        }

        // Check for existing pending application
        $existing = $user->creatorApplication;
        if ($existing && $existing->isPending()) {
            return back()->with('error', 'You already have a pending application.');
        }

        $data = $request->validate([
            'expertise'     => ['required', 'string', 'max:500'],
            'experience'    => ['required', 'string', 'max:1000'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'reason'        => ['required', 'string', 'max:1000'],
        ]);

        CreatorApplication::create([
            'user_id'   => $user->id,
            ...$data,
        ]);

        return redirect()->route('creator.apply')->with('success', 'Your application has been submitted! We\'ll review it and get back to you soon.');
    }

    /**
     * Switch between student and creator modes (for approved creators only).
     */
    public function switchMode()
    {
        $user = Auth::user();

        if (!$user->isCreator()) {
            return back()->with('error', 'You are not a creator.');
        }

        $newMode = $user->active_mode === 'creator' ? 'student' : 'creator';
        $user->update(['active_mode' => $newMode]);

        if ($newMode === 'creator') {
            return redirect()->route('creator.dashboard')->with('success', 'Switched to Creator mode.');
        }

        return redirect()->route('dashboard')->with('success', 'Switched to Student mode.');
    }
}
