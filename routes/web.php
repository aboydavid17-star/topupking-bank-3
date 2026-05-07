<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return view('welcome');
});

// Wallet route - no auth for now, make we test first
Route::get('/wallet', [WalletController::class, 'index']);

// TEMPORARY MIGRATION ROUTE - DELETE AFTER USE!!!
Route::get('/run-migrations-now-12345', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        return "<html><body style='font-family: monospace; padding: 20px;'><h2 style='color: green;'>✅ MIGRATION SUCCESS</h2><pre style='background: #f4f4f4; padding: 15px;'>{$output}</pre><br><a href='/wallet'>Go to Wallet Now</a></body></html>";
    } catch (\Exception $e) {
        return "<html><body style='font-family: monospace; padding: 20px;'><h2 style='color: red;'>❌ MIGRATION FAILED</h2><pre style='background: #f4f4f4; padding: 15px; color: red;'>Error: ". $e->getMessage() ."</pre></body></html>";
    }
});
