<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Pet;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class PetController extends Controller
{
    public function welcome()
    {
        return Inertia::render('Public/Welcome', [
            'featuredPets' => Pet::available()->latest()->take(3)->get()->toArray(),
        ]);
    }

    public function index(Request $request)
    {
        $query = Pet::query()->available();

        // Filter by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by species (column is 'type')
        if ($request->filled('species')) {
            $query->where('type', $request->species);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by size
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        // Filter by age range
        if ($request->filled('ageRange') && is_array($request->ageRange) && count($request->ageRange) === 2) {
            $minAge = (int) $request->ageRange[0];
            $maxAge = (int) $request->ageRange[1];

            if ($maxAge > 0) {
                $latestBirthDate = Carbon::today()->subYears($minAge);
                $earliestBirthDate = Carbon::today()->subYears($maxAge + 1)->addDay();
                $query->whereBetween('birth_date', [$earliestBirthDate, $latestBirthDate]);
            }
        }

        return Inertia::render('Public/Pets/Index', [
            'pets' => $query->latest()->paginate(9)->withQueryString(),
            'filters' => $request->only(['search', 'species', 'gender', 'size', 'ageRange']),
        ]);
    }

    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->firstOrFail();

        return Inertia::render('Public/Pets/Show', [
            'pet' => $pet->toArray(),
            'settings' => Setting::all()->pluck('value', 'key'),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        ]);
    }
}
