<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Sale::today()->completed();
        $todayRevenue = (clone $todaySales)->sum('total');
        $todayCount = (clone $todaySales)->count();

        $monthRevenue = Sale::where('status', 'completed')
            ->whereMonth('created_at', now())
            ->whereYear('created_at', now())
            ->sum('total');

        $recentTransactions = Sale::with(['user', 'owner'])
            ->latest()
            ->limit(10)
            ->get();

        $lowStockProducts = Product::lowStock()->active()->limit(10)->get();

        $expiringProducts = Product::expiring(30)->active()->limit(10)->get();

        $paymentBreakdown = Sale::today()->completed()
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.dashboard', compact(
            'todayRevenue',
            'todayCount',
            'monthRevenue',
            'recentTransactions',
            'lowStockProducts',
            'expiringProducts',
            'paymentBreakdown',
        ));
    }
}
