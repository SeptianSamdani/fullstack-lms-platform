<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Http\Request;

class XenditWebhookController extends Controller
{
    /**
     * Terima callback/webhook dari Xendit saat status invoice berubah.
     * Endpoint ini PUBLIC (tidak pakai auth:sanctum) karena dipanggil server-to-server
     * oleh Xendit, bukan oleh user — makanya wajib verifikasi x-callback-token.
     */
    public function handleInvoice(Request $request)
    {
        $token = $request->header('x-callback-token');

        if (!$token || $token !== config('services.xendit.callback_token')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $status     = $request->input('status');
        $invoiceId  = $request->input('id');
        $externalId = $request->input('external_id'); // format: "payment-{id}"

        $payment = Payment::where('xendit_invoice_id', $invoiceId)->first();

        if (!$payment && $externalId) {
            $paymentId = str_replace('payment-', '', $externalId);
            $payment = Payment::find($paymentId);
        }

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Idempotency — cegah proses ganda kalau Xendit retry webhook
        if ($payment->status === 'success') {
            return response()->json(['message' => 'Already processed']);
        }

        if (in_array($status, ['PAID', 'SETTLED'])) {
            $payment->update([
                'status'         => 'success',
                'payment_method' => $request->input('payment_channel', 'xendit'),
                'xendit_invoice_id' => $invoiceId,
            ]);

            if ($payment->payable_type === Subscription::class) {
                $plan = $payment->payable->plan;
                $payment->payable()->update([
                    'status'     => 'active',
                    'start_date' => now(),
                    'end_date'   => now()->addDays($plan->duration_days),
                ]);

                $payment->user->notify(new PaymentSuccessNotification($payment->fresh()->load('payable.plan')));
            }
        } elseif ($status === 'EXPIRED') {
            $payment->update(['status' => 'failed']);
            if ($payment->payable_type === Subscription::class) {
                $payment->payable()->update(['status' => 'cancelled']);
            }
        }

        return response()->json(['message' => 'OK']);
    }
}