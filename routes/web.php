<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Auth::routes();

// put routes inside auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource("users", App\Http\Controllers\UserController::class);
    //getPermissions
    Route::post('users/get-permissions', [App\Http\Controllers\UserController::class, 'getPermissions'])->name('users.get-permissions');

    // if super admin
    Route::get('teams/select-team', [App\Http\Controllers\TeamController::class, 'selectTeam'])->name('teams.select-team');
    Route::get('teams/switch/{team}', [App\Http\Controllers\TeamController::class, 'switchTeam'])->name('teams.switch');
    Route::group(['middleware' => ['role:super-admin']], static function () {
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::post('teams/{team}/invite', [App\Http\Controllers\TeamController::class, 'inviteMember'])->name('teams.invite-member');
        Route::resource('teams', App\Http\Controllers\TeamController::class);
        Route::post('teams/{team}/remove-member/{user}', [App\Http\Controllers\TeamController::class, 'removeMember'])->name('teams.remove-member');
        //ActivityLogController
        Route::get('activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
        //ActivityLogController show
        Route::get('activity-logs/{activity}', [App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity-logs.show');
    });

    // teams.accept-invitation
    Route::post('teams/{team}/accept-invitation/{user}/{token}', [App\Http\Controllers\TeamController::class, 'acceptInvitation'])->name('teams.accept-invitation');
    //acceptInvitation
    Route::post('teams/{team}/reject-invitation/{user}/{token}', [App\Http\Controllers\TeamController::class, 'rejectInvitation'])->name('teams.reject-invitation');
    //showInvitationForm
    Route::get('teams/{team}/invitation/{user}/{token}', [App\Http\Controllers\TeamController::class, 'showInvitationForm'])->name('teams.show-invitation-form');
});
