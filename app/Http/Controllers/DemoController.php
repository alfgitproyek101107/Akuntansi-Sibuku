<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Branch;
use Database\Seeders\DemoSeeder;

class DemoController extends Controller
{
    private const DEMO_BRANCH_ID = 999;
    private const DEMO_USER_EMAIL = 'demo@example.com';

    /**
     * Login as demo user
     */
    public function login()
    {
        // Find or create demo user
        $demoUser = User::where('email', self::DEMO_USER_EMAIL)->first();

        if (!$demoUser) {
            // Run demo seeder if demo user doesn't exist
            Artisan::call('db:seed', ['--class' => 'DemoSeeder']);
            $demoUser = User::where('email', self::DEMO_USER_EMAIL)->first();
        }

        // Login the demo user
        Auth::login($demoUser);

        // Set demo branch in session
        session(['active_branch' => self::DEMO_BRANCH_ID]);

        return redirect()->route('dashboard')->with('success', 'Selamat datang di mode demo!');
    }

    /**
     * Reset demo data
     */
    public function reset(Request $request)
    {
        // Only allow reset if user is authenticated and is demo user
        if (!Auth::check() || !Auth::user()->demo_mode) {
            abort(403, 'Unauthorized');
        }

        try {
            DB::beginTransaction();

            // Truncate demo data tables
            $this->truncateDemoData();

            // Re-seed demo data
            Artisan::call('db:seed', ['--class' => 'DemoSeeder']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Demo data berhasil direset'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset demo data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if current user is in demo mode
     */
    public function checkDemoMode()
    {
        $isDemo = Auth::check() && Auth::user()->demo_mode;

        return response()->json([
            'is_demo' => $isDemo,
            'branch_id' => $isDemo ? self::DEMO_BRANCH_ID : null
        ]);
    }

    /**
     * Truncate demo data tables
     */
    private function truncateDemoData()
    {
        $tables = [
            'transactions',
            'transfers',
            'stock_movements',
            'invoices',
            'invoice_items',
            'payments',
            'products',
            'product_categories',
            'customers',
            'categories',
            'accounts',
            'recurring_templates',
        ];

        foreach ($tables as $table) {
            DB::table($table)
                ->where('branch_id', self::DEMO_BRANCH_ID)
                ->delete();
        }
    }

    /**
     * Get demo statistics
     */
    public function stats()
    {
        if (!Auth::check() || !Auth::user()->demo_mode) {
            abort(403, 'Unauthorized');
        }

        $stats = [
            'total_products' => DB::table('products')->where('branch_id', self::DEMO_BRANCH_ID)->count(),
            'total_customers' => DB::table('customers')->where('branch_id', self::DEMO_BRANCH_ID)->count(),
            'total_transactions' => DB::table('transactions')->where('branch_id', self::DEMO_BRANCH_ID)->count(),
            'total_revenue' => DB::table('transactions')
                ->where('branch_id', self::DEMO_BRANCH_ID)
                ->where('type', 'income')
                ->sum('amount'),
            'total_expenses' => DB::table('transactions')
                ->where('branch_id', self::DEMO_BRANCH_ID)
                ->where('type', 'expense')
                ->sum('amount'),
        ];

        return response()->json($stats);
    }
}