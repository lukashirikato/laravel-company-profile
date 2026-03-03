<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class QRApiController extends Controller
{
    /**
     * Generate QR code for authenticated customer
     */
    public function generate(): JsonResponse
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Check if already has active QR
        if ($customer->qr_token && $customer->qr_active) {
            return response()->json([
                'success' => false,
                'message' => 'QR code already exists. Regenerate to create a new one.'
            ], 400);
        }

        // Generate new QR token
        $customer->generateQRToken();

        return response()->json([
            'success' => true,
            'message' => 'QR code generated successfully',
            'data' => [
                'qr_token' => $customer->qr_token,
                'qr_data' => $customer->getQRData(),
                'qr_active' => $customer->qr_active,
            ]
        ]);
    }

    /**
     * Regenerate QR code (invalidate old one)
     */
    public function regenerate(): JsonResponse
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Regenerate: creates new token and updates timestamps
        $customer->regenerateQRToken();

        return response()->json([
            'success' => true,
            'message' => 'QR code regenerated successfully',
            'data' => [
                'qr_token' => $customer->qr_token,
                'qr_data' => $customer->getQRData(),
                'qr_active' => $customer->qr_active,
                'qr_generated_at' => $customer->qr_generated_at,
            ]
        ]);
    }

    /**
     * Disable QR code
     */
    public function disable(): JsonResponse
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!$customer->qr_token) {
            return response()->json([
                'success' => false,
                'message' => 'No QR code to disable'
            ], 400);
        }

        $customer->disableQR();

        return response()->json([
            'success' => true,
            'message' => 'QR code disabled successfully',
            'data' => [
                'qr_active' => $customer->qr_active,
            ]
        ]);
    }

    /**
     * Enable QR code
     */
    public function enable(): JsonResponse
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!$customer->qr_token) {
            return response()->json([
                'success' => false,
                'message' => 'No QR code to enable'
            ], 400);
        }

        $customer->enableQR();

        return response()->json([
            'success' => true,
            'message' => 'QR code enabled successfully',
            'data' => [
                'qr_active' => $customer->qr_active,
            ]
        ]);
    }

    /**
     * Get QR code status for customer
     */
    public function status(): JsonResponse
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'has_qr' => $customer->qr_token ? true : false,
                'qr_active' => $customer->qr_active,
                'qr_token' => $customer->qr_token,
                'qr_generated_at' => $customer->qr_generated_at,
                'member_id' => str_pad($customer->id, 4, '0', STR_PAD_LEFT),
            ]
        ]);
    }
}
