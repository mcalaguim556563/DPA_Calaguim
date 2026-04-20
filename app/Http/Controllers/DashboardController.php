<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\RiceItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRiceItems = RiceItem::count();
        $totalOrders = Order::count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount_paid');
        $pendingOrders = Order::where('status', 'pending')->count();
        $recentOrders = Order::with(['payment', 'orderItems.riceItem'])
            ->latest()
            ->take(5)
            ->get();
        $lowStockItems = RiceItem::where('stock_quantity', '<=', 10)->get();

        return view('dashboard', compact(
            'totalRiceItems',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'recentOrders',
            'lowStockItems'
        ));
    }
}
