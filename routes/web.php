<?php

use App\Http\Controllers\ClientController;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
    return view('welcome');
});

Route::middleware(
    [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
    ])->group(function() {

    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/phpinfo', function() {
        phpinfo();
        return response('', 200);
    });

    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])
         ->name('calendar');

    Route::get('/calendar/test', [App\Http\Controllers\CalendarController::class, 'test'])
         ->name('calendar-test');

    Route::get('/rental-history', [App\Http\Controllers\RentalController::class, 'index'])
         ->name('rental-history');

    Route::get('/vehicles', [App\Http\Controllers\VehicleController::class, 'index'])
         ->name('vehicles');

    Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])
         ->name('clients');

    Route::get('/clients/{id}', [App\Http\Controllers\ClientController::class, 'show'])
         ->name('clients.show')
         ->where('id', '[0-9]+');

    Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])
         ->name('tasks');

    /*
     * Merge
     */
    // step 1
    Route::get('/merge-clients', [ClientController::class, 'merge'])->name('merge-clients');
    // step 2
    Route::any('/merge-clients-edit', [ClientController::class, 'mergeEdit'])->name('merge-clients-edit');
    // step 3
    Route::post('/merge-run', [ClientController::class, 'mergeRun'])->name('merge-run');
    // step complete
    Route::get('/merge-complete', [ClientController::class, 'mergeComplete'])->name('merge-complete');

    /*
     * Text Messages
     */
    Route::get('/text-messages', [App\Http\Controllers\TextMessageController::class, 'index'])
         ->name('text-messages');
    Route::get('/text-messages/select/{clientId}', [App\Http\Controllers\TextMessageController::class, 'select'])
         ->name('text-messages.select')
         ->where('clientId', '[0-9]+');

    /*
     * Tests
     */
    Route::get('/test-session-alerts', [App\Http\Controllers\TestController::class, 'testSessionAlerts']);
    Route::get('/test-logs', [App\Http\Controllers\TestController::class, 'testLogs']);
    Route::get('/test-save-file/{disk}/{path?}', [App\Http\Controllers\TestController::class, 'testSaveFile'])
         ->where('disk', '[a-z]+')
         ->where('path', '[a-zA-Z_\-0-9]+');
    Route::get('/test-create-image', [App\Http\Controllers\TestController::class, 'createImage']);
    Route::get('/test-hammerjs-image-mobile', [App\Http\Controllers\TestController::class, 'testHammerJsImageMobile']);
    Route::get('/test/scrolling', [App\Http\Controllers\TestController::class, 'scrolling']);

    /*
     * Developer
     */
    Route::get('/dev-generate-thumbnail', [App\Http\Controllers\DevController::class, 'generateThumbnail'])
         ->name('dev-generate-thumbnail');
    Route::get('/cache-clear', [App\Http\Controllers\DevController::class, 'cacheClear']);

    /*
     * Web-API
     */
    Route::get('/web-api/clients.json', function() {
        return new ClientResource(\App\Models\Client::select(
            ['name', 'address', 'phone_number', 'rating']
        )->get());
    })->name('web-api-clients-json');
});

/*
 * File Load
 */
// Chris D. 1-Mar-2024
// Moved because this fails on the Cloudways server
// It responds with 302 redirects to login -- I cannot figure out why :( as it doesn't happen on local.
// Anyway, this is an opportunity to improve the speed. Load the file with security token instead
Route::get('/file-load/{disk}', [App\Http\Controllers\FileLoadController::class, 'loadFile'])
     ->name('file-load');
// Page with own layout to view image (should be opened in a new tab)
Route::get('/view-image/{disk}', [App\Http\Controllers\FileLoadController::class, 'viewImage'])
     ->name('view-image');

Route::get('/file-load-inline/{mediaUUID}/{securityToken}', [App\Http\Controllers\FileLoadController::class, 'inline'])
     ->where('mediaUUID', '[a-z0-9\-]+')
     ->where('securityToken', '[a-zA-Z0-9_\-]+')
     ->name('file-load-inline');
