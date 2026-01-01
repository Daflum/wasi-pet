<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePaymentMethodRequest;
use App\Models\PaymentMethod;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PaymentMethodController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SavePaymentMethodRequest $request)
    {
        PaymentMethod::create($request->getValidatedData());

        return redirect()->back()->with('success', 'Método de pago creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SavePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        // The getValidatedData method in the form request handles the file upload and old file deletion.
        // However, if a new file is NOT uploaded, the old qr_code_path will be missing from the validated data.
        // We need to merge the existing qr_code_path if no new file is present.
        $validatedData = $request->getValidatedData();

        if (!$request->hasFile('qr_code_path')) {
            $validatedData['qr_code_path'] = $paymentMethod->qr_code_path;
        }

        $paymentMethod->update($validatedData);

        return redirect()->back()->with('success', 'Método de pago actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Delete the associated QR code from Cloudinary if it exists
        if ($paymentMethod->qr_code_path) {
            $publicId = 'qrs/' . basename(parse_url($paymentMethod->qr_code_path, PHP_URL_PATH), '.' . pathinfo(parse_url($paymentMethod->qr_code_path, PHP_URL_PATH), PATHINFO_EXTENSION));
            Cloudinary::uploadApi()->destroy($publicId);
        }

        $paymentMethod->delete();

        return redirect()->back()->with('success', 'Método de pago eliminado correctamente.');
    }
}
