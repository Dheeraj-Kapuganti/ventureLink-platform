<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Startup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StartupController extends Controller
{
    /**
     * Show all startups belonging to the logged-in founder with Search and Filtering.
     */
    public function index(Request $request)
    {
        $query = Startup::where('founder_id', Auth::id());

        // 1. Search by Title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 2. Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 3. Sort logic
        $sort = $request->get('sort', 'latest'); // default to latest
        if ($sort === 'funding_highest') {
            $query->orderBy('current_funding', 'desc');
        } elseif ($sort === 'funding_lowest') {
            $query->orderBy('current_funding', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // latest
        }

        $startups = $query->get();

        return view('founder.startups.index', compact('startups'));
    }

    /**
     * Show the form for creating a new startup.
     */
    public function create()
    {
        return view('founder.startups.create');
    }

    /**
     * Store a newly created startup in MongoDB.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'full_description' => 'required|string',
            'funding_goal' => 'required|numeric|min:0',
            'equity_offered' => 'required|numeric|min:0.1|max:100',
            'category' => 'required|string|max:100',
            'startup_stage' => 'required|string|max:100',
            'deadline' => 'required|date|after:today',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $startup = new Startup();
        $startup->fill($validated);
        $startup->short_description = $validated['short_description'];
        $startup->description = $validated['full_description'];
        $startup->equity_offered = (float) $validated['equity_offered'];
        $startup->founder_id = Auth::id();
        $startup->status = 'pending';

        // --- IMAGE UPLOAD HANDLING ---
        // If the user uploaded a logo, store it in the 'public/startups/logos' folder
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('startups/logos', 'public');
            $startup->logo = $path; 
        }

        // If the user uploaded a banner, store it in the 'public/startups/banners' folder
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('startups/banners', 'public');
            $startup->banner = $path;
        }

        $startup->save();

        // Notify all administrators in the system
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewStartupSubmittedNotification($startup));
        }

        return redirect()->route('founder.panel')->with('success', 'Startup created successfully!');
    }

    /**
     * View a specific startup's details.
     */
    public function show($id)
    {
        // Find the specific startup by its MongoDB _id with relationships
        $startup = Startup::with(['founder', 'comments.user'])->findOrFail($id);

        // Security: ensure the logged-in founder owns this startup before showing it
        // Or if you want it public to everyone, you can remove this check!
        if ($startup->founder_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // You would normally create a 'founder.startups.show' blade file for this
        return view('founder.startups.show', compact('startup'));
    }

    /**
     * Show the form for editing an existing startup.
     */
    public function edit($id)
    {
        $startup = Startup::findOrFail($id);

        if ($startup->founder_id !== Auth::id()) {
            abort(403);
        }

        // You would normally create a 'founder.startups.edit' blade file for this
        return view('founder.startups.edit', compact('startup'));
    }

    /**
     * Update the startup in MongoDB.
     */
    public function update(Request $request, $id)
    {
        $startup = Startup::findOrFail($id);

        if ($startup->founder_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'full_description' => 'required|string',
            'funding_goal' => 'required|numeric|min:0',
            'equity_offered' => 'required|numeric|min:0.1|max:100',
            'category' => 'required|string|max:100',
            'startup_stage' => 'required|string|max:100',
            'deadline' => 'required|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $startup->fill($validated);
        $startup->short_description = $validated['short_description'];
        $startup->description = $validated['full_description'];
        $startup->equity_offered = (float) $validated['equity_offered'];

        // If the user uploaded a NEW logo, delete the old one and save the new one
        if ($request->hasFile('logo')) {
            if ($startup->logo) {
                Storage::disk('public')->delete($startup->logo);
            }
            $startup->logo = $request->file('logo')->store('startups/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($startup->banner) {
                Storage::disk('public')->delete($startup->banner);
            }
            $startup->banner = $request->file('banner')->store('startups/banners', 'public');
        }

        $startup->save();

        return redirect()->route('startups.show', $startup->id)->with('success', 'Startup updated successfully!');
    }

    /**
     * Delete a startup from MongoDB completely.
     */
    public function destroy($id)
    {
        $startup = Startup::findOrFail($id);

        if ($startup->founder_id !== Auth::id()) {
            abort(403);
        }

        // Clean up the uploaded images so they don't take up space!
        if ($startup->logo) {
            Storage::disk('public')->delete($startup->logo);
        }
        if ($startup->banner) {
            Storage::disk('public')->delete($startup->banner);
        }

        // Delete the document
        $startup->delete();

        return redirect()->route('founder.panel')->with('success', 'Startup deleted forever.');
    }

    /**
     * Display all pending startups for admin approval.
     */
    public function adminIndex()
    {
        $startups = Startup::with('founder')->where('status', 'pending')->latest()->get();
        
        // Live MongoDB database dynamic statistics
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_founders' => \App\Models\User::where('role', 'founder')->count(),
            'total_investors' => \App\Models\User::where('role', 'investor')->count(),
            'total_startups' => \App\Models\Startup::count(),
            'approved_startups' => \App\Models\Startup::where('status', 'active')->count(),
            'pending_startups' => \App\Models\Startup::where('status', 'pending')->count(),
            'rejected_startups' => \App\Models\Startup::where('status', 'rejected')->count(),
            'total_investments' => \App\Models\Investment::count(),
            'total_funding_amount' => \App\Models\Investment::sum('amount'),
        ];

        return view('admin.dashboard', compact('startups', 'stats'));
    }

    /**
     * Approve a pending startup.
     */
    public function approve($id)
    {
        $startup = Startup::findOrFail($id);
        $startup->status = 'active';
        $startup->save();

        // Notify the founder
        if ($startup->founder) {
            $startup->founder->notify(new \App\Notifications\StartupApprovedNotification($startup));
        }

        // Notify all admins in the system
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\StartupApprovedNotification($startup));
        }

        return back()->with('success', 'Startup approved and live!');
    }

    /**
     * Reject a pending startup.
     */
    public function reject(Request $request, $id)
    {
        $startup = Startup::findOrFail($id);
        $startup->status = 'rejected';
        $startup->save();

        // Validate optional rejection reason
        $reason = $request->input('reason', 'It does not meet our current listing criteria.');

        // Notify the founder
        if ($startup->founder) {
            $startup->founder->notify(new \App\Notifications\StartupRejectedNotification($startup, $reason));
        }

        // Notify all admins in the system
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\StartupRejectedNotification($startup, $reason));
        }

        return back()->with('success', 'Startup application has been rejected.');
    }

    /**
     * Display all startups with search and filter capabilities for admin.
     */
    public function adminStartupsIndex(Request $request)
    {
        $query = Startup::query();

        // 1. Search filter (by title, description, category)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // 2. Status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'approved') {
                $query->where('status', 'active');
            } else {
                $query->where('status', $status);
            }
        }

        // 3. Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $startups = $query->latest()->get();

        // Pluck unique categories dynamically from MongoDB for filtering dropdown
        $categories = Startup::pluck('category')->unique()->filter()->values()->all();

        return view('admin.startups.index', compact('startups', 'categories'));
    }

    /**
     * Delete a startup from the admin side.
     */
    public function adminDestroy($id)
    {
        $startup = Startup::findOrFail($id);

        // Delete associated uploaded files
        if ($startup->logo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($startup->logo);
        }
        if ($startup->banner) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($startup->banner);
        }

        $startup->delete();

        return redirect()->route('admin.startups.index')->with('success', 'Startup deleted successfully from the platform.');
    }
}
