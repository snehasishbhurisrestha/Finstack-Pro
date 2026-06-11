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
    PattiCheckController,
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
    Route::put('/game-entry/{id}', [GameEntryController::class, 'update'])->name('game-entry.edit');
    Route::delete('/game-entry/{id}', [GameEntryController::class, 'destroy'])->name('game-entry.destroy');

    Route::get('/game-entry/bulk-list', [GameEntryController::class, 'bulkList'])->name('game-entry.bulk-list');
    Route::post('/game-entry/bulk-update', [GameEntryController::class, 'bulkUpdate'])->name('game-entry.bulk-update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::match(['get','post'], '/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results-history', [ResultController::class, 'history'])->name('results.history');
    Route::get('/results-history/show/{result}', [ResultController::class, 'show'])->name('results.show');

    Route::get('/reports/single', [AnalyticsController::class, 'singleReport'])->name('reports.single');
    Route::get('/reports/single/agent-details',[AnalyticsController::class, 'singleAgentDetails'])->name('reports.single.agent.details');

    Route::get('/reports/patti', [AnalyticsController::class, 'pattiReport'])->name('reports.patti');
    Route::get('/reports/patti/agent-details',[AnalyticsController::class, 'pattiAgentDetails'])->name('reports.patti.agent.details');

    Route::get('/patti-check', [PattiCheckController::class, 'index'])->name('patti-check.index');
    Route::get('/patti-check/details',[PattiCheckController::class, 'details'])->name('patti-check.details');
    Route::get('/patti-check/create', [PattiCheckController::class, 'store_index'])->name('patti-check.create');
    Route::post('/patti-check/store', [PattiCheckController::class, 'store'])->name('patti-check.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});



require __DIR__.'/auth.php';