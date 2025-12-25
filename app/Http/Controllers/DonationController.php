<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    /**
     * Display the public donation page.
     */
    public function index()
    {
        return Inertia::render('Donations/Index');
    }

    /**
     * Store a newly created donation in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'message' => 'nullable|string',
            'proof' => 'required|file|image|max:2048',
        ];

        if (!Auth::check()) {
            $rules['guest_contact'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $path = null;
        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('donations', 'public');
        }

        Donation::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'message' => $request->message,
            'proof_path' => $path,
            'guest_contact' => $request->guest_contact,
        ]);

        return redirect()->route('donations.index')->with('success', 'Donación registrada correctamente. ¡Gracias!');
    }

    /**
     * Display the admin donation list.
     */
    public function indexAdmin()
    {
        $donations = Donation::with('user')->latest()->get();
        return Inertia::render('Admin/Donations/Index', [
            'donations' => $donations
        ]);
    }
}
