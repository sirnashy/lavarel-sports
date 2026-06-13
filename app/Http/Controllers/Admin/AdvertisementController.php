<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Advertisement;
use App\Services\AdManager;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    private array $slots = [
        'header' => 'Header',
        'sidebar' => 'Sidebar',
        'in-article' => 'In-Article',
        'video' => 'Video Page',
        'mobile' => 'Mobile',
        'footer' => 'Footer',
    ];

    public function index()
    {
        $ads = Advertisement::orderBy('slot_key')->orderBy('sort_order')->paginate(20);
        $slots = $this->slots;
        return view('admin.advertisements.index', compact('ads', 'slots'));
    }

    public function create()
    {
        $slots = $this->slots;
        return view('admin.advertisements.create', compact('slots'));
    }

    public function store(Request $request, AdManager $adManager)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slot_key' => 'required|in:' . implode(',', array_keys($this->slots)),
            'code' => 'required|string',
            'position' => 'in:desktop,mobile,any',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $ad = Advertisement::create($data);
        $adManager->clearCache($data['slot_key']);

        ActivityLog::record('created', 'Created advertisement: ' . $ad->name, $ad);
        return redirect()->route('admin.advertisements.index')->with('success', 'Advertisement created.');
    }

    public function edit(Advertisement $advertisement)
    {
        $slots = $this->slots;
        return view('admin.advertisements.edit', compact('advertisement', 'slots'));
    }

    public function update(Request $request, Advertisement $advertisement, AdManager $adManager)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slot_key' => 'required|in:' . implode(',', array_keys($this->slots)),
            'code' => 'required|string',
            'position' => 'in:desktop,mobile,any',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $advertisement->update($data);
        $adManager->clearCache($advertisement->slot_key);
        $adManager->clearCache($data['slot_key']);

        ActivityLog::record('updated', 'Updated advertisement: ' . $advertisement->name, $advertisement);
        return redirect()->route('admin.advertisements.index')->with('success', 'Advertisement updated.');
    }

    public function destroy(Advertisement $advertisement, AdManager $adManager)
    {
        $adManager->clearCache($advertisement->slot_key);
        ActivityLog::record('deleted', 'Deleted advertisement: ' . $advertisement->name, $advertisement);
        $advertisement->delete();
        return back()->with('success', 'Advertisement deleted.');
    }

    public function toggle(Advertisement $advertisement, AdManager $adManager)
    {
        $advertisement->update(['is_active' => !$advertisement->is_active]);
        $adManager->clearCache($advertisement->slot_key);
        return back()->with('success', 'Advertisement ' . ($advertisement->is_active ? 'enabled' : 'disabled') . '.');
    }
}