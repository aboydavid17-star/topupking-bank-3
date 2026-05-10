use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

Route::get('/fix-wallet-db', function () {
    try {
        // Check if column exists
        if (Schema::hasColumn('users', 'wallet_balance')) {
            return '<h1>Column already exists ✅</h1><p>Go to /force-credit now</p>';
        }

        // Add the column
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('email');
        });

        // Verify it was added
        $columns = Schema::getColumnListing('users');
        if (in_array('wallet_balance', $columns)) {
            return '<h1>SUCCESS: wallet_balance added ✅</h1><p>Now tap /force-credit to fund ₦600</p>';
        } else {
            return '<h1>FAILED: Column not added ❌</h1>';
        }
        
    } catch (\Exception $e) {
        return '<h1>ERROR</h1><p>' . $e->getMessage() . '</p>';
    }
});
