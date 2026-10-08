<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Models\Donation;
use App\Models\Pet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'totalPets' => Pet::count(),
            'pendingRequests' => AdoptionRequest::where('status', 'Pendiente')->count(),
            'pendingDonations' => Donation::count(),
        ]);
    }
}
