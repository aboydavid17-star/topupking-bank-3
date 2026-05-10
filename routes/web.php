use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;

Route::get('/check-wallet', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    
    $exists = Schema::hasColumn('users', 'wallet_balance');
    $columns = Schema::getColumnListing('users');
    
    return response()->json([
        'wallet_balance_exists' => $exists,
        'all_user_columns' => $columns,
        'message' => $exists ? 'Column exists. Try /force-credit now' : 'Column missing'
    ]);
});
