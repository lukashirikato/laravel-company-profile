<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

class InvoiceController extends Controller
{
    /**
     * Convert logo to base64 for PDF rendering
     * 
     * @return string
     */
    private function getLogoBase64()
    {
        $logoPath = public_path('icons/logo-ftm.jpg');
        
        if (File::exists($logoPath)) {
            try {
                $imageData = base64_encode(File::get($logoPath));
                $mimeType = File::mimeType($logoPath);
                return "data:{$mimeType};base64,{$imageData}";
            } catch (\Exception $e) {
                Log::error('Logo conversion error', [
                    'path' => $logoPath,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return null;
    }

    /**
     * Display the invoice in browser (HTML view)
     * 
     * @param int $id Order ID
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            // Load order dengan relasi yang dibutuhkan
            $order = Order::with(['customer', 'package', 'transaction'])
                ->findOrFail($id);
            
            // Authorization check - customer hanya bisa lihat invoice sendiri
            $this->authorizeInvoiceAccess($order);
            
            // Siapkan data untuk view
            $customer = $order->customer;
            $package = $order->package;
            $logoBase64 = $this->getLogoBase64();
            
            // Return view - sesuaikan dengan file yang ada
            return view('invoice.template', compact('order', 'customer', 'package', 'logoBase64'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Order tidak ditemukan
            abort(404, 'Invoice tidak ditemukan');
        } catch (\Exception $e) {
            // Error lainnya
            Log::error('Invoice Show Error', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Terjadi kesalahan saat menampilkan invoice');
        }
    }

    /**
     * Download invoice as PDF file
     * 
     * @param int $id Order ID
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function download($id)
    {
        try {
            // Load order dengan relasi
            $order = Order::with(['customer', 'package', 'transaction'])
                ->findOrFail($id);
            
            // Authorization check
            $this->authorizeInvoiceAccess($order);
            
            // Siapkan data
            $customer = $order->customer;
            $package = $order->package;
            $logoBase64 = $this->getLogoBase64();
            
            // Generate PDF dengan konfigurasi yang proper
            $pdf = Pdf::loadView('invoice.pdf', compact('order', 'customer', 'package', 'logoBase64'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'debugKeepTemp' => false,
                ]);
            
            // Buat filename yang descriptive dengan date
            $filename = 'Invoice-' . $order->order_code . '-' . now()->format('Ymd') . '.pdf';
            
            // Log download activity untuk audit trail
            Log::info('Invoice Downloaded', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'customer_id' => $order->customer_id,
                'customer_name' => $customer->name,
                'filename' => $filename,
                'downloaded_at' => now()->toDateTimeString()
            ]);
            
            // Return PDF download
            return $pdf->download($filename);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Invoice tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Invoice Download Error', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal mengunduh invoice. Silakan coba lagi.');
        }
    }

    /**
     * View PDF directly in browser (stream without download)
     * 
     * @param int $id Order ID
     * @return \Illuminate\Http\Response
     */
    public function viewPdf($id)
    {
        try {
            // Load order dengan relasi
            $order = Order::with(['customer', 'package', 'transaction'])
                ->findOrFail($id);
            
            // Authorization check
            $this->authorizeInvoiceAccess($order);
            
            // Siapkan data
            $customer = $order->customer;
            $package = $order->package;
            $logoBase64 = $this->getLogoBase64();
            
            // Cek apakah ada view khusus untuk PDF
            $viewName = view()->exists('invoice.pdf') 
                ? 'invoice.pdf' 
                : 'invoice.template';
            
            // Generate PDF
            $pdf = Pdf::loadView($viewName, compact('order', 'customer', 'package', 'logoBase64'))
                ->setPaper('a4', 'portrait');
            
            // Filename untuk stream
            $filename = 'Invoice-' . $order->order_code . '.pdf';
            
            // Log view activity
            Log::info('Invoice PDF Viewed', [
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'viewed_at' => now()->toDateTimeString()
            ]);
            
            // Stream PDF ke browser
            return $pdf->stream($filename);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Invoice tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Invoice PDF View Error', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Gagal menampilkan PDF invoice');
        }
    }

    /**
     * Print-friendly version of invoice
     * 
     * @param int $id Order ID
     * @return \Illuminate\View\View
     */
    public function print($id)
    {
        try {
            // Load order dengan relasi
            $order = Order::with(['customer', 'package', 'transaction'])
                ->findOrFail($id);
            
            // Authorization check
            $this->authorizeInvoiceAccess($order);
            
            // Siapkan data
            $customer = $order->customer;
            $package = $order->package;
            $logoBase64 = $this->getLogoBase64();
            
            // Cek apakah ada view khusus untuk print
            $viewName = view()->exists('invoice.print') 
                ? 'invoice.print' 
                : 'invoice.template';
            
            // Log print activity
            Log::info('Invoice Printed', [
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'printed_at' => now()->toDateTimeString()
            ]);
            
            return view($viewName, compact('order', 'customer', 'package', 'logoBase64'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Invoice tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Invoice Print Error', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Gagal menampilkan invoice untuk print');
        }
    }

    /**
     * Send invoice via email
     * 
     * @param int $id Order ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail($id, Request $request)
    {
        try {
            // Load order dengan relasi
            $order = Order::with(['customer', 'package', 'transaction'])
                ->findOrFail($id);
            
            // Authorization check
            $this->authorizeInvoiceAccess($order);
            
            // Validate email input (opsional, default ke email customer)
            $validated = $request->validate([
                'email' => 'nullable|email|max:255'
            ]);
            
            $email = $validated['email'] ?? $order->customer->email;
            
            // Siapkan data
            $customer = $order->customer;
            $package = $order->package;
            $logoBase64 = $this->getLogoBase64();
            
            // Generate PDF untuk attachment
            $pdf = Pdf::loadView('invoice.pdf', compact('order', 'customer', 'package', 'logoBase64'))
                ->setPaper('a4', 'portrait');
            
            // Send email dengan PDF attachment
            Mail::send('emails.invoice', compact('order', 'customer', 'package'), function ($message) use ($order, $email, $pdf) {
                $message->to($email)
                    ->subject('Invoice #' . $order->order_code . ' - FTM Society')
                    ->attachData(
                        $pdf->output(), 
                        'Invoice-' . $order->order_code . '.pdf',
                        ['mime' => 'application/pdf']
                    );
            });
            
            // Log email activity
            Log::info('Invoice Emailed', [
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'email_to' => $email,
                'sent_at' => now()->toDateTimeString()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil dikirim ke ' . $email
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Invoice Email Error', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim invoice. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Authorization check - memastikan customer hanya bisa akses invoice sendiri
     * 
     * @param Order $order
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    private function authorizeInvoiceAccess(Order $order)
    {
        // Cek apakah customer yang login adalah pemilik order ini
        if (auth('customer')->id() !== $order->customer_id) {
            Log::warning('Unauthorized Invoice Access Attempt', [
                'attempted_by' => auth('customer')->id(),
                'order_id' => $order->id,
                'order_owner' => $order->customer_id,
                'ip_address' => request()->ip(),
                'attempted_at' => now()->toDateTimeString()
            ]);
            
            abort(403, 'Anda tidak memiliki akses ke invoice ini');
        }
    }

    /**
     * Get invoice data as JSON (untuk API atau AJAX request)
     * 
     * @param int $id Order ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoiceData($id)
    {
        try {
            // Load order dengan relasi
            $order = Order::with(['customer', 'package', 'transaction'])
                ->findOrFail($id);
            
            // Authorization check
            $this->authorizeInvoiceAccess($order);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'invoice_number' => $order->order_code,
                    'invoice_date' => $order->created_at->format('d F Y'),
                    'customer' => [
                        'name' => $order->customer->name,
                        'email' => $order->customer->email,
                        'phone' => $order->customer->phone ?? '-'
                    ],
                    'package' => [
                        'name' => $order->package->name,
                        'description' => $order->package->description ?? '-',
                        'price' => $order->package->price
                    ],
                    'payment' => [
                        'method' => $order->payment_method ?? '-',
                        'status' => $order->status,
                        'amount' => $order->total_amount,
                        'paid_at' => $order->paid_at 
                            ? \Carbon\Carbon::parse($order->paid_at)->format('d F Y, H:i')
                            : null
                    ]
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Invoice Data Fetch Error', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data invoice'
            ], 500);
        }
    }
}