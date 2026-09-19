<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailySales(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : today();

        $sales = Sale::whereDate('created_at', $date)
            ->with(['user', 'owner'])
            ->get();

        $total = $sales->where('status', 'completed')->sum('total');
        $count = $sales->where('status', 'completed')->count();

        return view('admin.reports.daily-sales', compact('sales', 'total', 'count', 'date'));
    }

    public function weeklySales(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : now()->startOfWeek();
        $to = $request->to ? Carbon::parse($request->to) : now()->endOfWeek();

        $dailyData = Sale::where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $total = $dailyData->sum('total');
        $count = $dailyData->sum('count');

        return view('admin.reports.weekly-sales', compact('dailyData', 'total', 'count', 'from', 'to'));
    }

    public function monthlySales(Request $request)
    {
        $month = $request->month ? Carbon::parse($request->month)->startOfMonth() : now()->startOfMonth();

        $dailyData = Sale::where('status', 'completed')
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $total = $dailyData->sum('total');
        $count = $dailyData->sum('count');

        return view('admin.reports.monthly-sales', compact('dailyData', 'total', 'count', 'month'));
    }

    public function salesByProduct(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $data = SaleItem::where('type', 'product')
            ->whereHas('sale', fn ($q) => $q->where('status', 'completed'))
            ->when($from, fn ($q) => $q->whereHas('sale', fn ($sq) => $sq->whereDate('created_at', '>=', $from)))
            ->when($to, fn ($q) => $q->whereHas('sale', fn ($sq) => $sq->whereDate('created_at', '<=', $to)))
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_amount')
            ->get();

        return view('admin.reports.sales-by-product', compact('data'));
    }

    public function salesByService(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $data = SaleItem::where('type', 'service')
            ->whereHas('sale', fn ($q) => $q->where('status', 'completed'))
            ->when($from, fn ($q) => $q->whereHas('sale', fn ($sq) => $sq->whereDate('created_at', '>=', $from)))
            ->when($to, fn ($q) => $q->whereHas('sale', fn ($sq) => $sq->whereDate('created_at', '<=', $to)))
            ->select('service_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->groupBy('service_id')
            ->with('service')
            ->orderByDesc('total_amount')
            ->get();

        return view('admin.reports.sales-by-service', compact('data'));
    }

    public function salesByCashier(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $data = Sale::where('status', 'completed')
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->select('user_id', DB::raw('SUM(total) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total_amount')
            ->get();

        return view('admin.reports.sales-by-cashier', compact('data'));
    }

    public function inventoryReport()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        $totalValue = $products->sum(fn ($p) => $p->stock * $p->cost_price);
        $totalRetail = $products->sum(fn ($p) => $p->stock * $p->selling_price);

        return view('admin.reports.inventory', compact('products', 'totalValue', 'totalRetail'));
    }

    public function lowStockReport()
    {
        $products = Product::lowStock()->active()->with('category')->orderBy('stock')->get();

        return view('admin.reports.low-stock', compact('products'));
    }

    public function expiringReport(Request $request)
    {
        $days = $request->days ?? 30;

        $products = Product::expiring($days)->active()->with('category')->orderBy('expiration_date')->get();

        return view('admin.reports.expiring', compact('products', 'days'));
    }

    public function veterinaryReport(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $sales = Sale::where('status', 'completed')
            ->whereHas('items', fn ($q) => $q->where('type', 'service'))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->with(['pet', 'owner', 'items.service'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalRevenue = $sales->where('status', 'completed')->sum('total');

        return view('admin.reports.veterinary', compact('sales', 'totalRevenue'));
    }
}
