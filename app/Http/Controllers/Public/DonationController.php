<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\SaveDonationRequest;
use App\Models\Donation;
use App\Models\PaymentMethod;
use Inertia\Inertia;

class DonationController extends Controller
{
    /**
     * Display the public donation page.
     */
    public function create()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return Inertia::render('Public/Donations/Index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Store a newly created donation in storage.
     */
    public function store(SaveDonationRequest $request)
    {
        Donation::create($request->getValidatedData());

        return back()->with('success', 'Donación registrada correctamente. ¡Gracias!');
    }
}
