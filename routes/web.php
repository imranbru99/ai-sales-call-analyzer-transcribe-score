<?php

use App\Http\Controllers\DemoLoginController;
use App\Http\Controllers\PublicWebsiteController;
use App\Models\Call;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Public Marketing & Product Routes
|--------------------------------------------------------------------------
*/
Route::controller(PublicWebsiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/features', 'features')->name('features');
    Route::get('/features/{module}', 'featureDetail')->name('features.detail');
    Route::get('/solutions/industry/{industry?}', 'solutionsIndustry')->name('solutions.industry');
    Route::get('/solutions/role/{role?}', 'solutionsRole')->name('solutions.role');
    Route::get('/pricing', 'pricing')->name('pricing');
    Route::get('/module-explorer', 'moduleExplorer')->name('module-explorer');
    Route::get('/live-demo', 'liveDemo')->name('live-demo');
    Route::get('/integrations', 'integrations')->name('integrations');
    Route::get('/security', 'security')->name('security');
    Route::get('/case-studies', 'caseStudies')->name('case-studies');
    Route::get('/case-studies/{slug}', 'caseStudyDetail')->name('case-studies.detail');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{slug}', 'blogDetail')->name('blog.detail');
    Route::get('/compare', 'compare')->name('compare');
    Route::get('/roi-calculator', 'roiCalculator')->name('roi-calculator');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/legal/{page}', 'legal')->name('legal');
});

/*
|--------------------------------------------------------------------------
| Frictionless One-Click Demo Logins for All 12 Roles
|--------------------------------------------------------------------------
*/
Route::get('/demo-login/{role}', [DemoLoginController::class, 'loginAs'])->name('demo.login');

/*
|--------------------------------------------------------------------------
| Signed Audio Streaming Route
|--------------------------------------------------------------------------
*/
Route::get('/calls/{call}/stream', function (Call $call) {
    if (! request()->hasValidSignature()) {
        abort(403, 'Invalid or expired audio stream link.');
    }

    if ($call->audio_path && Storage::disk('local')->exists($call->audio_path)) {
        return Storage::disk('local')->response($call->audio_path);
    }

    // Default synthetic speech audio response if disk file missing
    return response('', 204);
})->name('calls.stream-audio');
