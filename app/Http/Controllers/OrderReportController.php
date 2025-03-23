<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderReportController extends Controller
{
    public function report(Request $request)
    {
        $dataArray = [];
        $statuses = ['pending', 'shipped', 'processing', 'cancelled', 'delivered'];
        foreach ($statuses as $status){
            $orders = Order::where('archived', FALSE)
                                ->whereIn('order_type', ['pickup', 'exchange'])
                                ->whereRaw('YEAR(order_date) IN (2024, 2025)')
                                ->whereHas('trackings', function ($query) use ($status){
                                    $query->where('status', $status)
                                          ->whereRaw('YEAR(status_date) IN (2024, 2025)');
                                })
                                ->get();

            $dataArray[$status]['count'] = count($orders);
            $dataArray[$status]['total'] = 0;

            foreach($orders as $order){
                foreach($order->products as $product){
                    $dataArray[$status]['total'] += $product->price * $product->pivot->quantity;
                }
            }
        }

        return view('home', compact('statuses', 'dataArray'));
    }

    public function reportOptimizedEloquent()
    {
        $dataArray = [];
        $statuses = ['pending', 'shipped', 'processing', 'cancelled', 'delivered'];

        foreach($statuses as $status){
            $orders = Order::where('archived', FALSE)
                ->whereIn('order_type', ['pickup', 'exchange'])
                // ->whereRaw('YEAR(order_date) IN (2024, 2025)')
                ->whereDate('order_date', '>=', '2024-01-01')
                ->whereDate('order_date', '<=', '2025-12-31')
                ->whereHas('trackings', function ($query) use ($status){
                    $query->where('status', $status)
                          ->whereDate('status_date', '>=', '2024-01-01')
                          ->whereDate('status_date', '<=', '2025-12-31');
                })
                ->withSum('products as total_order_amount', DB::raw('products.price * order_product.quantity'))
                ->get();

                $dataArray[$status]['count'] = $orders->count();
                $dataArray[$status]['total'] = $orders->sum('total_order_amount');
        }
        
        return view('home', compact('statuses', 'dataArray'));
    }

    public function reportOptimizedQueryBuilder()
    {
        $dataArray = [];
        $statuses = ['pending', 'shipped', 'processing', 'cancelled', 'delivered'];

        $queryResults = DB::table('orders')
                            ->join('order_trackings', 'orders.id', '=', 'order_trackings.order_id')
                            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                            ->join('products', 'products.id', '=', 'order_product.product_id')
                            ->where('orders.archived', FALSE)
                            ->whereIn('orders.order_type', ['pickup', 'exchange'])
                            ->whereDate('orders.order_date', '>=', '2024-01-01')
                            ->whereDate('orders.order_date', '<=', '2025-12-31')
                            ->whereDate('order_trackings.status_date', '>=', '2024-01-01')
                            ->whereDate('order_trackings.status_date', '<=', '2025-12-31')
                            ->whereIn('order_trackings.status', $statuses)
                            ->select(
                                'order_trackings.status',
                                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                                DB::raw('SUM(products.price * order_product.quantity) AS total_order_amount')
                            )
                            ->groupBy('order_trackings.status')
                            ->get();        
        foreach($queryResults as $result){
            $dataArray[$result->status]['count'] = $result->order_count;
            $dataArray[$result->status]['total'] = $result->total_order_amount;
        }

        return view('home', compact('statuses', 'dataArray'));
    }

}
