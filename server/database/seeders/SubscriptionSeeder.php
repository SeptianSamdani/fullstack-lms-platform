<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $starterPlan = SubscriptionPlan::where('name', 'Starter')->first();
        $proPlan     = SubscriptionPlan::where('name', 'Pro')->first();
        $premiumPlan = SubscriptionPlan::where('name', 'Premium')->first();

        // ═══════════════════════════════════════════════════════════════
        // Budi — Subscription Pro aktif (mulai 20 hari lalu)
        // ═══════════════════════════════════════════════════════════════
        $budi = User::where('email', 'budi@lms.com')->first();
        $budiSub = Subscription::create([
            'user_id'    => $budi->id,
            'plan_id'    => $proPlan->id,
            'start_date' => Carbon::now()->subDays(20),
            'end_date'   => Carbon::now()->subDays(20)->addDays($proPlan->duration_days),
            'status'     => 'active',
        ]);
        Payment::create([
            'user_id'        => $budi->id,
            'payable_type'   => Subscription::class,
            'payable_id'     => $budiSub->id,
            'amount'         => $proPlan->price,
            'status'         => 'success',
            'payment_method' => 'bank_transfer',
        ]);

        // ═══════════════════════════════════════════════════════════════
        // Citra — Subscription Premium aktif (mulai 2 bulan lalu)
        // ═══════════════════════════════════════════════════════════════
        $citra = User::where('email', 'citra@lms.com')->first();
        $citraSub = Subscription::create([
            'user_id'    => $citra->id,
            'plan_id'    => $premiumPlan->id,
            'start_date' => Carbon::now()->subMonths(2),
            'end_date'   => Carbon::now()->subMonths(2)->addDays($premiumPlan->duration_days),
            'status'     => 'active',
        ]);
        Payment::create([
            'user_id'        => $citra->id,
            'payable_type'   => Subscription::class,
            'payable_id'     => $citraSub->id,
            'amount'         => $premiumPlan->price,
            'status'         => 'success',
            'payment_method' => 'e_wallet',
        ]);

        // ═══════════════════════════════════════════════════════════════
        // Gilang — Subscription Starter EXPIRED (30 hari lalu, sudah habis)
        // ═══════════════════════════════════════════════════════════════
        $gilang = User::where('email', 'gilang@lms.com')->first();
        $gilangSub = Subscription::create([
            'user_id'    => $gilang->id,
            'plan_id'    => $starterPlan->id,
            'start_date' => Carbon::now()->subDays(45),
            'end_date'   => Carbon::now()->subDays(15), // Sudah expired 15 hari lalu
            'status'     => 'expired',
        ]);
        Payment::create([
            'user_id'        => $gilang->id,
            'payable_type'   => Subscription::class,
            'payable_id'     => $gilangSub->id,
            'amount'         => $starterPlan->price,
            'status'         => 'success',
            'payment_method' => 'bank_transfer',
        ]);

        // ═══════════════════════════════════════════════════════════════
        // Hana — Subscription Starter aktif (baru mulai 3 hari lalu)
        // ═══════════════════════════════════════════════════════════════
        $hana = User::where('email', 'hana@lms.com')->first();
        $hanaSub = Subscription::create([
            'user_id'    => $hana->id,
            'plan_id'    => $starterPlan->id,
            'start_date' => Carbon::now()->subDays(3),
            'end_date'   => Carbon::now()->subDays(3)->addDays($starterPlan->duration_days),
            'status'     => 'active',
        ]);
        Payment::create([
            'user_id'        => $hana->id,
            'payable_type'   => Subscription::class,
            'payable_id'     => $hanaSub->id,
            'amount'         => $starterPlan->price,
            'status'         => 'success',
            'payment_method' => 'e_wallet',
        ]);

        // ═══════════════════════════════════════════════════════════════
        // Irfan — Subscription Pro aktif (mulai 1 bulan lalu)
        // ═══════════════════════════════════════════════════════════════
        $irfan = User::where('email', 'irfan@lms.com')->first();
        $irfanSub = Subscription::create([
            'user_id'    => $irfan->id,
            'plan_id'    => $proPlan->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date'   => Carbon::now()->subMonth()->addDays($proPlan->duration_days),
            'status'     => 'active',
        ]);
        Payment::create([
            'user_id'        => $irfan->id,
            'payable_type'   => Subscription::class,
            'payable_id'     => $irfanSub->id,
            'amount'         => $proPlan->price,
            'status'         => 'success',
            'payment_method' => 'credit_card',
        ]);
    }
}
