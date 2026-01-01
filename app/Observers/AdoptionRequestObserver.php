<?php

namespace App\Observers;

use App\Models\AdoptionRequest;
use App\Notifications\AdoptionWaitlistClosedNotification;

class AdoptionRequestObserver
{
    /**
     * Handle the AdoptionRequest "updated" event.
     */
    public function updated(AdoptionRequest $adoptionRequest): void
    {
        // Check if the status was changed to 'Aprobado'
        if ($adoptionRequest->isDirty('status') && $adoptionRequest->status === 'Aprobado') {
            $pet = $adoptionRequest->pet;

            // 1. Update pet status to 'Adoptado'
            $pet->status = 'Adoptado';
            $pet->save();

            // 2. Get all other pending requests for this pet
            $waitlistRequests = $pet->adoptionRequests()
                ->where('status', 'Pendiente')
                ->where('id', '!=', $adoptionRequest->id)
                ->get();

            foreach ($waitlistRequests as $request) {
                // 3. Change their status to 'Cerrada'
                $request->status = 'Cerrada';
                $request->save();

                // 4. Send notification
                $request->notify(new AdoptionWaitlistClosedNotification($pet));
            }
        }
    }
}
