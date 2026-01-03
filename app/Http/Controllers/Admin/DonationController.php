<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateDonationRequest;
use App\Models\Donation;
use Inertia\Inertia;

class DonationController extends Controller
{
    /**
     * Display the admin donation list.
     */
    public function index()
    {
        $donations = Donation::with(['user', 'pet'])->latest()->get();
        return Inertia::render('Admin/Donations/Index', [
            'donations' => $donations
        ]);
    }

    public function update(UpdateDonationRequest $request, Donation $donation)
    {
        $donation->update($request->validated());

        return redirect()->back()->with('success', 'Donación actualizada.');
    }
}
