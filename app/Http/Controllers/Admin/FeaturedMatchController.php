<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\FeaturedMatch;
use App\Services\SportSRC\MatchService;
use Illuminate\Http\Request;

class FeaturedMatchController extends Controller
{
    public function index()
    {
        $featured = FeaturedMatch::with('creator')->orderBy('sort_order')->paginate(20);
        return view('admin.featured.index', compact('featured'));
    }

    public function create()
    {
        return view('admin.featured.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'match_id' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'match_starts_at' => 'nullable|date',
        ]);

        $data['created_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);
        $featured = FeaturedMatch::create($data);

        ActivityLog::record('created', 'Added featured match: ' . $featured->match_id, $featured);
        return redirect()->route('admin.featured.index')->with('success', 'Featured match added.');
    }

    public function edit(FeaturedMatch $featured)
    {
        return view('admin.featured.edit', compact('featured'));
    }

    public function update(Request $request, FeaturedMatch $featured)
    {
        $data = $request->validate([
            'match_id' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'match_starts_at' => 'nullable|date',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $featured->update($data);

        ActivityLog::record('updated', 'Updated featured match: ' . $featured->match_id, $featured);
        return redirect()->route('admin.featured.index')->with('success', 'Featured match updated.');
    }

    public function destroy(FeaturedMatch $featured)
    {
        ActivityLog::record('deleted', 'Removed featured match: ' . $featured->match_id, $featured);
        $featured->delete();
        return back()->with('success', 'Featured match removed.');
    }
}