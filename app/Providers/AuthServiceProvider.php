<?php

namespace App\Providers;

use App\Enums\Permission;
use App\Enums\PermissionBit;
use App\Policies\StaffPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Staff::class => StaffPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
//        Gate::define('edit-staff', function($user) {
//           $user->hasPermission('is_staff', PermissionBit::Edit);
//        });
//
//        Gate::define('view-staff', function($user) {
//            $user->hasPermission('is_staff', PermissionBit::View);
//        });
    }
}
