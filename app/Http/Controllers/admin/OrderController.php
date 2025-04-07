<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {

        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email');
        $orders = $orders->leftJoin('users', 'users.id', 'orders.user_id');

        if ($request->get('keyword') != "") {
            $orders = $orders->where('users.name', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('users.email', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('orders.id', 'like', '%' . $request->keyword . '%');
        }

        $orders = $orders->paginate(10);

//        $data['orders'] = $orders;

        return view('admin.orders.list', [
            'orders' => $orders
        ]);
    }

    public function detail($orderId) {

        $order = Order::where('orders.id', $orderId)->first();

        $orderItems = OrderItem::where('order_id', $orderId)->get();

        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function changeOrderStatus(Request $request, $orderId) {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->save();

        $message = 'Order status updated successfully';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
