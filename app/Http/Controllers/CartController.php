<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
               'status' => false,
               'message' => 'Product not found'
            ]);
        }

        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            $productAlreadyExist = false;
            foreach ($cartContent as $cartItem) {
                if ($cartItem->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->name, 1, $product->price,
                    ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
                $status = true;
                $message = '<strong>'.$product->name. ' </strong> added to cart successfully';

                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->name . ' already exists in your cart';
            }
        } else {
            Cart::add($product->id, $product->name, 1, $product->price,
                ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

            $status = true;
            $message = '<strong>'.$product->name. ' </strong> added to cart successfully';

            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart() {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;

        return view('front.cart', $data);
    }

    public function deleteItem(Request $request) {
        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Item not found in a cart';
            session()->flash('error', $errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item removed from cart successfully';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout() {

        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (!Auth::check()) {
            session(['redirect_after_login' => route('front.checkout')]);

            return redirect()->route('account.login');
        }

        $default_email = Auth::user()->email;

        session()->forget('url.intended');

        return view('front.checkout', [
            'default_email' => $default_email
        ]);
    }

    public function processCheckout(Request $request) {
        $validator = Validator::make(request()->all(), [
           'first_name' => 'required',
           'last_name' => 'required',
           'email' => 'required|email',
           'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
               'message' => 'Validation error, please try again',
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();

        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;
        $grandTotal = $subTotal - $discount;

        $order = new Order();

        $order->user_id = $user->id;
        $order->subtotal = $subTotal;
        $order->discount = $discount;
        $order->grand_total = $grandTotal;
        $order->payment_status = 'not paid';
        $order->status = 'pending';

        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->email = $request->email;
        $order->mobile = $request->mobile;

        $order->save();

        foreach (Cart::content() as $item) {
            $orderItem = new OrderItem();

            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->name = $item->name;
            $orderItem->price = $item->price;

            $orderItem->save();
        }

        session()->flash('success', 'You have successfully placed your order');

        Cart::destroy();

        return response()->json([
            'status' => true,
            'message' => 'Order saved successfully',
            'orderId' => $order->id
        ]);


    }

    public function thankyou($id) {
        return view('front.thanks', [
            'id' => $id
        ]);
    }
}
