<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $pets = Pet::query()
            ->search($request->search)
            ->species($request->species)
            ->when($request->owner_id, fn ($q) => $q->where('owner_id', $request->owner_id))
            ->with('owner')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $owners = Owner::orderBy('full_name')->get();
        $species = Pet::distinct()->pluck('species')->filter()->values();

        return view('admin.pets.index', compact('pets', 'owners', 'species'));
    }

    public function create()
    {
        $owners = Owner::orderBy('full_name')->get();

        return view('admin.pets.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:100',
            'breed' => 'nullable|string|max:100',
            'sex' => 'nullable|string|in:male,female',
            'birthdate' => 'nullable|date|before:today',
            'color' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'microchip_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $pet = Pet::create($validated);
            AuditLogHelper::log('created', $pet, null, $validated);
        });

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet created successfully.');
    }

    public function show(Pet $pet)
    {
        $pet->load(['owner', 'sales' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.pets.show', compact('pet'));
    }

    public function edit(Pet $pet)
    {
        $owners = Owner::orderBy('full_name')->get();

        return view('admin.pets.edit', compact('pet', 'owners'));
    }

    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:100',
            'breed' => 'nullable|string|max:100',
            'sex' => 'nullable|string|in:male,female',
            'birthdate' => 'nullable|date|before:today',
            'color' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'microchip_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($pet, $validated) {
            $old = $pet->only(array_keys($validated));
            $pet->update($validated);
            AuditLogHelper::log('updated', $pet, $old, $validated);
        });

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet updated successfully.');
    }

    public function destroy(Pet $pet)
    {
        DB::transaction(function () use ($pet) {
            AuditLogHelper::log('deleted', $pet, $pet->toArray(), null);
            $pet->delete();
        });

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet deleted successfully.');
    }
}
