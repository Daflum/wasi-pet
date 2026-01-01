<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Pet;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class PetController extends Controller
{
    public function welcome()
    {
        return Inertia::render('Public/Welcome', [
            'featuredPets' => Pet::where('status', '!=', 'Adoptado')->latest()->take(3)->get()->toArray(),
        ]);
    }

    public function index(Request $request)
    {
        $query = Pet::where('status', '!=', 'Adoptado');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type') && in_array($request->type, ['dog', 'cat'])) {
            $query->where('type', $request->type);
        }

        return Inertia::render('Public/Pets/Index', [
            'pets' => $query->latest()->paginate(9)->withQueryString(),
            'filters' => $request->only(['search', 'type']),
        ]);
    }

    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->firstOrFail();

        if($pet->status === 'Adoptado') {
            abort(404);
        }

        return Inertia::render('Public/Pets/Show', [
            'pet' => $pet->toArray(),
            'settings' => Setting::all()->pluck('value', 'key'),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        ]);
    }
}
