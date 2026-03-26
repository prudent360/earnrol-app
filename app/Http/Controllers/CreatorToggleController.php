<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CreatorToggleController extends Controller
{
    public function toggle()
    {
        if (!Setting::get('creator_enabled')) {
            return back()->with('error', 'Creator signups are currently disabled.');
        }

        $user = Auth::user();

        if ($user->isCreator()) {
            return redirect()->route('creator.dashboard')->with('info', 'You are already a creator.');
        }

        $user->update(['is_creator' => true]);

        return redirect()->route('creator.dashboard')->with('success', 'Welcome to the Creator Program! You can now create and sell products and cohorts.');
    }
}
