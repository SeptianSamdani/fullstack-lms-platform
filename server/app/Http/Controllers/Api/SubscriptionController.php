<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\XenditSdkException;

class SubscriptionController extends Controller
{
    public function plans()
    {
        return response()->json(SubscriptionPlan::all());
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        return response()->json(SubscriptionPlan::create($validated), 201);
    }

    public function updatePlan(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'price'         => 'sometimes|numeric|min:0',
            'duration_days' => 'sometimes|integer|min:1',
        ]);

        $plan->update($validated);
        return response()->json($plan);
    }

    public function destroyPlan(SubscriptionPlan $plan)
    {
        $plan->delete();
        return response()->json(null, 204);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate(['plan_id' => 'required|exists:subscription_plans,id']);
        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);
        $user = $request->user();

        if ($user->hasActiveSubscription()) {
            return response()->json(['message' => 'Anda sudah memiliki subscription aktif.'], 409);
        }

        $subscription = Subscription::create([
            'user_id'    => $user->id,
            'plan_id'    => $plan->id,
            'start_date' => null,
            'end_date'   => null,
            'status'     => 'pending',
        ]);

        $payment = Payment::create([
            'user_id'        => $user->id,
            'payable_type'   => Subscription::class,
            'payable_id'     => $subscription->id,
            'amount'         => $plan->price,
            'status'         => 'pending',
            'payment_method' => null,
        ]);

        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $invoiceApi = new InvoiceApi();

        try {
            $invoiceRequest = new CreateInvoiceRequest([
                'external_id'          => 'payment-' . $payment->id,
                'description'          => "Pembayaran subscription: {$plan->name}",
                'amount'                => (float) $plan->price,
                'currency'              => 'IDR',
                'payer_email'           => $user->email,
                'invoice_duration'      => 86400, // 24 jam
                'success_redirect_url'  => env('FRONTEND_URL') . '/subscriptions/success',
                'failure_redirect_url'  => env('FRONTEND_URL') . '/subscriptions/failed',
            ]);

            $invoice = $invoiceApi->createInvoice($invoiceRequest);
        } catch (XenditSdkException $e) {
            $payment->update(['status' => 'failed']);
            $subscription->update(['status' => 'cancelled']);

            return response()->json([
                'message' => 'Gagal membuat invoice pembayaran.',
                'error'   => $e->getMessage(),
            ], 502);
        }

        $payment->update(['xendit_invoice_id' => $invoice->getId()]);

        return response()->json([
            'subscription' => $subscription,
            'payment'      => $payment->fresh(),
            'invoice_url'  => $invoice->getInvoiceUrl(),
            'message'      => 'Silakan selesaikan pembayaran melalui link berikut.',
        ], 201);
    }

    /**
     * Konfirmasi payment secara manual oleh ADMIN (interim, sebelum gateway asli aktif).
     * Route ini sudah dibatasi middleware 'role:admin' — user biasa tidak bisa
     * mengonfirmasi pembayarannya sendiri.
     *
     * TODO: ganti dengan webhook dari payment gateway (Midtrans/Xendit) +
     * verifikasi signature, lalu hapus endpoint manual confirm ini.
     */
    public function confirmPayment(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return response()->json(['message' => 'Payment sudah diproses sebelumnya.'], 422);
        }

        // Update payment menjadi success + catat admin yang mengonfirmasi (audit trail)
        $payment->update([
            'status'         => 'success',
            'payment_method' => 'manual_confirm',
            'confirmed_by'   => $request->user()->id,
        ]);

        // Aktifkan subscription terkait
        if ($payment->payable_type === Subscription::class) {
            $plan = $payment->payable->plan;
            $payment->payable()->update([
                'status'     => 'active',
                'start_date' => now(),
                'end_date'   => now()->addDays($plan->duration_days),
            ]);
        }

        return response()->json([
            'message'      => 'Payment berhasil dikonfirmasi, subscription sekarang aktif.',
            'payment'      => $payment->fresh()->load('confirmedBy:id,name'),
            'subscription' => $payment->payable()->first(),
        ]);
    }

    public function me(Request $request)
    {
        $subscription = $request->user()
            ->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest()
            ->first();

        return response()->json([
            'subscription' => $subscription,
            'is_active'    => $subscription !== null,
        ]);
    }
}
