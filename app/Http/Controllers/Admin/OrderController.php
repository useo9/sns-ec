<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['user', 'items'])
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }
}
