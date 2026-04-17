<?php

use Illuminate\Support\Facades\Route;
use Streats\Atlas\Http\Controllers\PagePreviewController;
use Streats\Atlas\Livewire\AtlasDashboard;
use Streats\Atlas\Livewire\LivePageEditor;
use Streats\Atlas\Livewire\PageBuilder;
use Streats\Atlas\Livewire\SetupWizard;
use Streats\Atlas\Services\SystemDetection;

Route::get('/setup', SetupWizard::class)->name('atlas.setup');

$snap = app(SystemDetection::class)->snapshot();
$dashboardEnabled = (bool) config('atlas.dashboard.enabled', true);
$onlyWhenNoAdmin = (bool) config('atlas.dashboard.show_when_no_filament_or_nova', true);
$noFilamentNova = empty($snap['filament']['installed']) && empty($snap['nova']['installed']);

if ($dashboardEnabled && (! $onlyWhenNoAdmin || $noFilamentNova)) {
    Route::get('/dashboard', AtlasDashboard::class)->name('atlas.dashboard');
}

Route::get('/pages/{page}/builder', PageBuilder::class)
    ->name('atlas.page.builder');

Route::middleware((array) config('atlas.frontend_editor.middleware', ['web']))
    ->get('/pages/{page}/live', LivePageEditor::class)
    ->name('atlas.page.live');

Route::get('/pages/{page}/preview', PagePreviewController::class)
    ->middleware('signed')
    ->name('atlas.page.preview');
