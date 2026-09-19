<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $owners = Owner::query()
            ->search($request->search)
            ->withCount('pets')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.owners.index', compact('owners'));
    }

    public function create()
    {
        return view('admin.owners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('owners', 'public');
        }

        DB::transaction(function () use ($validated) {
            $owner = Owner::create($validated);
            AuditLogHelper::log('created', $owner, null, $validated);
        });

        return redirect()->route('admin.owners.index')
            ->with('success', 'Owner created successfully.');
    }

    public function show(Owner $owner)
    {
        $owner->load(['pets', 'sales' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.owners.show', compact('owner'));
    }

    public function edit(Owner $owner)
    {
        return view('admin.owners.edit', compact('owner'));
    }

    public function update(Request $request, Owner $owner)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($owner->photo && \Storage::disk('public')->exists($owner->photo)) {
                \Storage::disk('public')->delete($owner->photo);
            }
            $validated['photo'] = $request->file('photo')->store('owners', 'public');
        }

        DB::transaction(function () use ($owner, $validated) {
            $old = $owner->only(array_keys($validated));
            $owner->update($validated);
            AuditLogHelper::log('updated', $owner, $old, $validated);
        });

        return redirect()->route('admin.owners.index')
            ->with('success', 'Owner updated successfully.');
    }

    public function destroy(Owner $owner)
    {
        DB::transaction(function () use ($owner) {
            if ($owner->photo && \Storage::disk('public')->exists($owner->photo)) {
                \Storage::disk('public')->delete($owner->photo);
            }
            AuditLogHelper::log('deleted', $owner, $owner->toArray(), null);
            $owner->delete();
        });

        return redirect()->route('admin.owners.index')
            ->with('success', 'Owner deleted successfully.');
    }
}
