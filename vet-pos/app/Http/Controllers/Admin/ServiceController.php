<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->search($request->search)
            ->when($request->category_id, fn ($q) => $q->where('service_category_id', $request->category_id))
            ->when($request->has('active'), fn ($q) => $q->active())
            ->with('category')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = ServiceCategory::orderBy('name')->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('name')->get();

        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            $service = Service::create($validated);
            AuditLogHelper::log('created', $service, null, $validated);
        });

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        $service->load('category');

        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::orderBy('name')->get();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($service, $validated) {
            $old = $service->only(array_keys($validated));
            $service->update($validated);
            AuditLogHelper::log('updated', $service, $old, $validated);
        });

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        DB::transaction(function () use ($service) {
            AuditLogHelper::log('deleted', $service, $service->toArray(), null);
            $service->delete();
        });

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
