<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function(Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        // Chris D. 5-Oct-2023 Login with Username `name` or Email
        // https://www.itsolutionstuff.com/post/laravel-jetstream-login-with-username-or-email-exampleexample.html
        Fortify::authenticateUsing(function(LoginRequest $request) {

            $users = User::where('email', 'LIKE', $request->email)
                         ->orWhere('name', 'LIKE', $request->email)
                         ->get();

            if($users->count()) {
                $user = $users->first();
                if(Hash::check($request->password, $user->password)) {
                    return $user;
                }
            }

            return null;
        });

        RateLimiter::for('two-factor', function(Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
