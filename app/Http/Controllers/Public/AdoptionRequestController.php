<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\SaveAdoptionRequest;
use App\Models\AdoptionRequest;

class AdoptionRequestController extends Controller
{
    public function store(SaveAdoptionRequest $request)
    {
        AdoptionRequest::create($request->validated());

        return back()->with('success', 'Solicitud de adopción enviada.');
    }
}
