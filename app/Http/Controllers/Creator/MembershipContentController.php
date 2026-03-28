<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\MembershipContent;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MembershipContentController extends Controller
{
    public function index(MembershipPlan $membership)
    {
        $this->authorizeOwner($membership);

        $contents = $membership->contents()->orderBy('sort_order')->get();

        return view('creator.memberships.contents.index', compact('membership', 'contents'));
    }

    public function create(MembershipPlan $membership)
    {
        $this->authorizeOwner($membership);

        return view('creator.memberships.contents.create', compact('membership'));
    }

    public function store(Request $request, MembershipPlan $membership)
    {
        $this->authorizeOwner($membership);

        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'content_type' => 'required|in:file,video,link,text',
            'file'         => 'nullable|file|max:51200',
            'external_url' => 'nullable|url|max:500',
            'body'         => 'nullable|string',
        ]);

        $data = $request->only(['title', 'description', 'content_type', 'external_url', 'body']);
        $data['membership_plan_id'] = $membership->id;
        $data['sort_order'] = $membership->contents()->count();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file_path'] = $file->store('memberships/content');
            $data['file_name'] = $file->getClientOriginalName();
        }

        MembershipContent::create($data);

        return redirect()->route('creator.memberships.contents.index', $membership)
            ->with('success', 'Content added successfully.');
    }

    public function destroy(MembershipPlan $membership, MembershipContent $content)
    {
        $this->authorizeOwner($membership);

        if ($content->membership_plan_id !== $membership->id) {
            abort(404);
        }

        if ($content->file_path) {
            Storage::delete($content->file_path);
        }

        $content->delete();

        return back()->with('success', 'Content removed.');
    }

    protected function authorizeOwner(MembershipPlan $membership): void
    {
        if ($membership->user_id !== Auth::id()) {
            abort(403, 'You do not own this membership plan.');
        }
    }
}
