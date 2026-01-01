<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdoptionRequest;
use App\Models\AdoptionRequest;
use Inertia\Inertia;

class AdoptionRequestController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/AdoptionRequests/Index', [
            'adoption_requests' => AdoptionRequest::with('pet')->latest()->paginate(),
        ]);
    }

    public function update(UpdateAdoptionRequest $request, AdoptionRequest $adoptionRequest)
    {
        $adoptionRequest->update($request->validated());

        return redirect()->back()->with('success', 'Solicitud actualizada.');
    }
}
