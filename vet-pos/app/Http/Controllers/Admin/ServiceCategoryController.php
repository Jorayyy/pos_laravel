<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ServiceCategory::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->withCount('services')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.service-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.service-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        DB::transaction(function () use ($validated) {
            $category = ServiceCategory::create($validated);
            AuditLogHelper::log('created', $category, null, $validated);
        });

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category created successfully.');
    }

    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->load('services');

        return view('admin.service-categories.show', compact('serviceCategory'));
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin.service-categories.edit', compact('serviceCategory'));
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        DB::transaction(function () use ($serviceCategory, $validated) {
            $old = $serviceCategory->only(array_keys($validated));
            $serviceCategory->update($validated);
            AuditLogHelper::log('updated', $serviceCategory, $old, $validated);
        });

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        DB::transaction(function () use ($serviceCategory) {
            AuditLogHelper::log('deleted', $serviceCategory, $serviceCategory->toArray(), null);
            $serviceCategory->delete();
        });

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }
}
