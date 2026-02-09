<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/collection/{collectionUrl}', \App\Http\Controllers\CollectionController::class)->name('collection.show');
Route::post('/collection/{collectionUrl}/submit', [\App\Http\Controllers\CollectionController::class, 'submit'])->name('collection.submit');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
    Route::post('/testimonials/{testimonial}/approve', [\App\Http\Controllers\TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{testimonial}/reject', [\App\Http\Controllers\TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::delete('/testimonials/{testimonial}', [\App\Http\Controllers\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    Route::get('/creator/settings', [\App\Http\Controllers\CreatorSettingsController::class, 'index'])->name('creator.settings');
    Route::put('/creator/settings', [\App\Http\Controllers\CreatorSettingsController::class, 'update'])->name('creator.settings.update');
    Route::get('/widget/settings', [\App\Http\Controllers\WidgetSettingsController::class, 'index'])->name('widget.settings');
    Route::put('/widget/settings', [\App\Http\Controllers\WidgetSettingsController::class, 'update'])->name('widget.settings.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Widget script endpoint (public) - placed at top to ensure it matches first
Route::get('/embed/{collectionUrl}', [\App\Http\Controllers\WidgetController::class, 'script'])
    ->name('widget.script');

Route::get('/test/widget', function () {
    return view('widget-test');
})->name('widget.test');

// Subscription routes (public checkout, authenticated success)
Route::post('/subscription/checkout', [\App\Http\Controllers\SubscriptionController::class, 'checkout'])
    ->middleware(['auth', 'verified'])
    ->name('subscription.checkout');
Route::get('/subscription/success', [\App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscription.success');
Route::get('/subscription/cancel', [\App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('subscription.cancel');
Route::get('/subscription', [\App\Http\Controllers\SubscriptionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('subscription.index');
Route::get('/subscription/portal', [\App\Http\Controllers\SubscriptionController::class, 'manage'])
    ->middleware(['auth', 'verified'])
    ->name('subscription.manage');
Route::post('/webhook/stripe', [\App\Http\Controllers\SubscriptionController::class, 'webhook'])->name('stripe.webhook');

require __DIR__.'/auth.php';
