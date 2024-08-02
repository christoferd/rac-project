<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Rental;
use App\Models\Task;
use App\Models\Vehicle;
use App\Observers\ClientObserver;
use App\Observers\RentalObserver;
use App\Observers\TaskObserver;
use App\Observers\VehicleObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
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
        // https://laravel.com/docs/9.x/eloquent#configuring-eloquent-strictness
        Model::preventAccessingMissingAttributes();

        // Custom Directive
        Blade::directive('notEmpty', function($exp) {
            return "<?php if(!empty({$exp})): ?>";
        });
        Blade::directive('elseNotEmpty', function($exp) {
            return '<?php else: /* elseNotEmpty */ ?>';
        });
        Blade::directive('endNotEmpty', function($exp) {
            return '<?php endif; /* endNotEmpty */ ?>';
        });

        // Observers
        // https://laravel.com/docs/11.x/eloquent#observers
        Client::observe(ClientObserver::class);
        Rental::observe(RentalObserver::class);
        Vehicle::observe(VehicleObserver::class);
        Task::observe(TaskObserver::class);

        // Log-Viewer
        // https://log-viewer.opcodes.io/docs/3.x/configuration/access-to-log-viewer#authorizing-users
        LogViewer::auth(function($request) {
            return auth()->check();
        });
    }
}
