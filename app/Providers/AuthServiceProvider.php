<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\BloodBag;
use App\Policies\BloodBagPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        BloodBag::class => BloodBagPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
