<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

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

        // Buat subscription dengan status pending — aktif setelah payment dikonfirmasi
        $subscription = Subscription::create([
            'user_id'    => $user->id,
            'plan_id'    => $plan->id,
            'start_date' => null,   // Belum aktif
            'end_date'   => null,   // Belum dihitung
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

        return response()->json([
            'subscription' => $subscription,
            'payment'      => $payment,
            'message'      => 'Silakan selesaikan pembayaran untuk mengaktifkan subscription.',
        ], 201);
    }

    /**
     * Simulasi konfirmasi payment (untuk testing).
     * Di production, ini digantikan oleh webhook dari payment gateway.
     */
    public function confirmPayment(Request $request, Payment $payment)
    {
        abort_unless(auth()->user()->id === $payment->user_id, 403);

        if ($payment->status !== 'pending') {
            return response()->json(['message' => 'Payment sudah diproses sebelumnya.'], 422);
        }

        // Update payment menjadi success
        $payment->update(['status' => 'success', 'payment_method' => 'manual_confirm']);

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
            'payment'      => $payment->fresh(),
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
