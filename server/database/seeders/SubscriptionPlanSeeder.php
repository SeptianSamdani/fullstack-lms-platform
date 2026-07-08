<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name'          => 'Starter',
                'price'         => 49000,
                'duration_days' => 30,
            ],
            [
                'name'          => 'Pro',
                'price'         => 129000,
                'duration_days' => 90,
            ],
            [
                'name'          => 'Premium',
                'price'         => 399000,
                'duration_days' => 365,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
