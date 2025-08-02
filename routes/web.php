<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LawEnforcement\LawEnforcementDashboardController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Volunteer\VolunteerDashboardController;
use App\Http\Middleware\CheckIfLoggedIn;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('login');
});

Route::middleware(CheckIfLoggedIn::class)->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::name('user.')->prefix('user')->middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('/profile', [HomeController::class, 'updateProfile']);

    Route::match(['get', 'post'], '/store-location', [HomeController::class, 'storeLocation'])->name('dashboard.location.store');
    Route::match(['get', 'post'], '/stop-location', [HomeController::class, 'stopLocation'])->name('dashboard.location.stop');
    Route::get('/get-helpers-coordinate', [HomeController::class, 'getHelpersCoordinate'])->name('dashboard.helpers-coordinates');

    Route::get('/incident-history', [HomeController::class, 'incidentHistory'])->name('history');
    Route::post('/get-incident-histories', [HomeController::class, 'getIncidentHistory'])->name('history.incident.histories');
});

Route::name('volunteer.')->prefix('volunteer')->middleware('auth')->group(function () {
    Route::get('/dashboard', [VolunteerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/documents', [VolunteerDashboardController::class, 'documents'])->name('documents');
    Route::post('/documents', [VolunteerDashboardController::class, 'uploadDocuments']);

    Route::get('/availability', [VolunteerDashboardController::class, 'updateAvailability'])->name('availability');
    Route::get('/get-latest-victims', [VolunteerDashboardController::class, 'getLatestVictims'])->name('dashboard.latest-victims');
    Route::post('/get-victim-coordinate', [VolunteerDashboardController::class, 'getVictimCoordinate'])->name('dashboard.victim-coordinate');
    Route::post('/update-self-location', [VolunteerDashboardController::class, 'updateSelfLocation'])->name('dashboard.update-self-location');
});

Route::name('law-enforcement.')->prefix('law-enforcement')->middleware('auth')->group(function () {
    Route::get('/dashboard', [LawEnforcementDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/documents', [LawEnforcementDashboardController::class, 'documents'])->name('documents');
    Route::post('/documents', [LawEnforcementDashboardController::class, 'uploadDocuments']);

    Route::get('/availability', [LawEnforcementDashboardController::class, 'updateAvailability'])->name('availability');
    Route::get('/get-latest-victims', [LawEnforcementDashboardController::class, 'getLatestVictims'])->name('dashboard.latest-victims');
    Route::post('/get-victim-coordinate', [LawEnforcementDashboardController::class, 'getVictimCoordinate'])->name('dashboard.victim-coordinate');
    Route::post('/update-self-location', [LawEnforcementDashboardController::class, 'updateSelfLocation'])->name('dashboard.update-self-location');
});

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/victims', [AdminDashboardController::class, 'victims'])->name('victims');

    Route::get('/volunteers', [AdminDashboardController::class, 'volunteers'])->name('volunteers');
    Route::get('/volunteers/{id}/approve', [AdminDashboardController::class, 'volunteerApprove'])->name('volunteer.approve');
    Route::get('/volunteers/{id}/cancel', [AdminDashboardController::class, 'volunteerCancel'])->name('volunteer.cancel');

    Route::get('/law-enforcement', [AdminDashboardController::class, 'lawEnforcements'])->name('law-enforcements');
    Route::get('/law-enforcement/{id}/approve', [AdminDashboardController::class, 'lawEnforcementsApprove'])->name('law-enforcements.approve');
    Route::get('/law-enforcement/{id}/cancel', [AdminDashboardController::class, 'lawEnforcementsCancel'])->name('law-enforcements.cancel');

    Route::get('upgrade-database', function () {
        Artisan::call('migrate --force');
        return 'Database upgraded';
    });
});

Route::get('cache-clear', function () {
    Artisan::call('optimize:clear');
    return 'Cache cleared';
});

Route::get('db-seed', function () {
    Artisan::call('db:seed --force');
    return 'Database seeded';
});
