<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Startup;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Toggle the saved state of a startup.
     */
    public function toggle($id)
    {
        $startup = Startup::findOrFail($id);
        $userId = Auth::id();

        $bookmark = Bookmark::where('user_id', $userId)
                            ->where('startup_id', $startup->id)
                            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Startup removed from your saved list.');
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'startup_id' => $startup->id
            ]);
            return back()->with('success', 'Startup saved successfully!');
        }
    }

    /**
     * Display a list of saved startups.
     */
    public function index()
    {
        $bookmarks = Bookmark::with('startup')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        // Map bookmarks to get the actual startup collection
        // ->filter() removes any null values in case a startup was deleted
        $startups = $bookmarks->pluck('startup')->filter();

        return view('investor.bookmarks.index', compact('startups'));
    }
}
