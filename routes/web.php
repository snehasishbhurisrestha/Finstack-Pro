<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    ProfileController,
    Dashboard,
    RoleController,
    PermissionController,
    EmployeeController,
    AgentController,
    GameEntryController,
    ReportController,
    ResultController,
    AnalyticsController,
};

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('agents', AgentController::class);

    Route::get('/game-entry', [GameEntryController::class, 'index'])->name('game-entry.index');
    Route::post('/game-entry', [GameEntryController::class, 'store'])->name('game-entry.store');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::match(['get','post'], '/results', [ResultController::class, 'index'])->name('results.index');

    Route::get('/reports/single', [AnalyticsController::class, 'singleReport'])->name('reports.single');
    Route::get('/reports/single/agent-details',[AnalyticsController::class, 'singleAgentDetails'])->name('reports.single.agent.details');

    Route::get('/reports/patti', [AnalyticsController::class, 'pattiReport'])->name('reports.patti');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});



require __DIR__.'/auth.php';