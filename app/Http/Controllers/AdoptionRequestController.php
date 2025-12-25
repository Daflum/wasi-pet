<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdoptionRequestController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'dog_id' => 'required|exists:dogs,id',
            'message' => 'nullable|string',
        ];

        if (!Auth::check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_phone'] = 'required|string|max:255';
        }

        $request->validate($rules);

        AdoptionRequest::create([
            'user_id' => Auth::id(),
            'dog_id' => $request->dog_id,
            'message' => $request->message,
            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,
        ]);

        return back()->with('success', 'Solicitud de adopción enviada.');
    }
}
