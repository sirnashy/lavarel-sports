<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SitePageController extends Controller
{
    public function index()
    {
        $pages = SitePage::with('creator')->orderBy('sort_order')->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:site_pages',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'show_in_nav' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['show_in_nav'] = $request->boolean('show_in_nav', false);
        $data['created_by'] = auth()->id();

        $page = SitePage::create($data);
        ActivityLog::record('created', 'Created page: ' . $page->title, $page);
        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(SitePage $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, SitePage $page)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:site_pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'show_in_nav' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['show_in_nav'] = $request->boolean('show_in_nav', false);
        $page->update($data);

        ActivityLog::record('updated', 'Updated page: ' . $page->title, $page);
        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(SitePage $page)
    {
        ActivityLog::record('deleted', 'Deleted page: ' . $page->title, $page);
        $page->delete();
        return back()->with('success', 'Page deleted.');
    }
}