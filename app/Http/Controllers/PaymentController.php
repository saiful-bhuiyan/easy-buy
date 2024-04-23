<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Color;
use App\Models\Cuppon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ShippingCharge;
use App\Models\Order;
use App\Models\OrderItem;
use Brian2694\Toastr\Toastr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function add_to_cart(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'product_id' => 'exists:products,id',
            'color_id' => 'exists:colors,id',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Add Cart Failed',
                'html' => view('frontend.product.cart')->render(),
            ]);
        }

        $product = Product::findOrFail($request->product_id);
        $price = $product->price;
        if (!empty($request->size_id)) {
            $productSize = ProductSize::select('price')->where('product_id', $product->id)
                ->where('id', $request->size_id)->first();

            if ($productSize->price !== null) {
                $price += $productSize->price;
            }
        }


        Cart::add([
            'id' => $product, // id == Product Model
            'name' => 'Product',
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'price' => $price,
            'options' => [
                'color_id' => $request->color_id,
                'size_id' => $request->has('size_id') ? $request->size_id : null,
            ],
        ]);

        return response()->json([
            'msg' => 'Add Cart Successfull',
            'html' => view('frontend.product.cart')->render(),
        ]);
    }

    public function show_cart()
    {
        dd(Cart::content());
    }

    public function shopping_cart()
    {
        $shipping = ShippingCharge::where('status', 1)->get();
        return view('frontend.payment.shopping_cart', compact('shipping'));
    }

    public function cart_update($qty, $rowId)
    {
        $shipping = ShippingCharge::where('status', 1)->get();
        $validator = Validator::make([
            'qty' => $qty,
            'rowId' => $rowId
        ], [
            'qty' => 'min:1',
            'rowId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Enter Valid Input'
            ], 404);
        }

        Cart::update($rowId, ['qty' => $qty]);

        return response()->json([
            'msg' => 'Product Has Updated',
            'html' => view('frontend.payment.cart_details', compact('shipping'))->render(),
            'cart' => view('frontend.product.cart')->render(),
        ], 200);
    }

    public function cart_delete($rowId)
    {
        Cart::remove($rowId);
        return response()->json([
            'msg' => 'Product Has Deleted',
            'html' => view('frontend.payment.cart_details', compact('shipping'))->render(),
            'cart' => view('frontend.product.cart')->render(),
        ], 200);
    }

    public function checkout()
    {
        $shipping = ShippingCharge::where('status', 1)->get();
        return view('frontend.payment.checkout', compact('shipping'));
    }

    public function apply_cuppon(Request $request)
    {
        $payable_amount = 0;
        $getDiscount = Cuppon::where('name', $request->discount_code)->first();
        if (!empty($getDiscount)) {
            $total = Cart::subtotal();
            if ($getDiscount->type == 'Percent') {
                $discount_amount = ($total * $getDiscount->percent_amount) / 100;
                $payable_amount = $total - $discount_amount;
            } else {
                $discount_amount = $getDiscount->percent_amount;
                $payable_amount = $total - $discount_amount;
            }

            $json['status'] = true;
            $json['discount_amount'] = number_format($discount_amount, 2);
            $json['payable_amount'] = number_format($payable_amount, 2);
            $json['message'] = "success";
        } else {
            $json['status'] = false;
            $json['discount_amount'] = number_format(0, 2);
            $json['message'] = "failed";
        }


        $getShipping = ShippingCharge::where('status', 1)->find($request->shipping_id);
        if (!empty($getShipping)) {
            if (empty($getDiscount)) {
                $payable_amount = Cart::subtotal();
            }

            $charge_amount = $getShipping->price;
            $payable_amount = $payable_amount + $charge_amount;


            $json['status'] = true;
            $json['charge_amount'] = $charge_amount;
            $json['payable_amount'] = $payable_amount;
            $json['message'] = "success";
        } else {
            $json['status'] = true;
            $json['message'] = "success";
        }

        return response()->json($json);
    }

    public function apply_shipping($id)
    {
        $getShipping = ShippingCharge::where('status', 1)->find($id);
        if (!empty($getShipping)) {
            $total = Cart::subtotal();

            $charge_amount = $getShipping->price;
            $payable_amount = $total + $charge_amount;


            $json['status'] = true;
            $json['charge_amount'] = $charge_amount;
            $json['payable_amount'] = $payable_amount;
            $json['message'] = "success";
        } else {
            $json['status'] = true;
            $json['message'] = "success";
        }

        return response()->json($json);
    }

    public function place_order(Request $request)
    {
        if (Cart::count() == 0) {
            $toastr = app(Toastr::class);
            $toastr->error('Error', 'Add Product to cart First !!');
            return redirect()->back();
        } else {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required',
                'email' => 'required',
                'city' => 'required|string',
                'state' => 'required|string',
                'shipping' => 'required|numeric',
                'payment' => 'required|string',
            ]);

            if ($validator->fails()) {

                $toastr = app(Toastr::class);
                $toastr->error('Error', 'Please Enter Valid Information');
                return redirect()->back();
            }

            try {
                DB::beginTransaction();

                $payable_amount = Cart::subtotal();
                $discount_amount = 0;
                $shipping_charge = 0;

                $order = new Order();
                if (Auth::check()) {
                    $order->user_id = Auth::user()->id;
                }

                if (!empty($request->discount_code)) {
                    $getDiscount = Cuppon::where('name', $request->discount_code)->first();
                    if (!empty($getDiscount)) {
                        $total = Cart::subtotal();
                        if ($getDiscount->type == 'Percent') {
                            $discount_amount = ($total * $getDiscount->percent_amount) / 100;
                            $payable_amount = $total - $discount_amount;
                        } else {
                            $discount_amount = $getDiscount->percent_amount;
                            $payable_amount = $total - $discount_amount;
                        }
                    }
                }

                $getShipping = ShippingCharge::where('status', 1)->find($request->shipping);
                if (!empty($getShipping)) {
                    if (empty($getDiscount)) {
                        $payable_amount = Cart::subtotal();
                    }

                    $charge_amount = $getShipping->price;
                    $shipping_charge = $charge_amount;
                    $payable_amount = $payable_amount + $charge_amount;
                }

                $order->first_name = trim($request->first_name);
                $order->last_name = trim($request->last_name);
                $order->email = trim($request->email);
                $order->phone = trim($request->phone);
                $order->company_name = trim($request->company_name);
                $order->address = trim($request->address);
                $order->city = trim($request->city);
                $order->state = trim($request->state);
                $order->post_code = trim($request->post_code);
                $order->zip_code = trim($request->zip_code);
                $order->order_notes = trim($request->order_notes);
                $order->cuppon_code = trim($request->discount_code);
                $order->discount_amount = $discount_amount;
                $order->shipping_charge = $shipping_charge;
                $order->payment_method = trim($request->payment);
                $order->total_amount = $payable_amount;
                $order->save();

                foreach (Cart::content() as $key => $cart) {
                    $order_item = new OrderItem();
                    $order_item->order_id = $order->id;
                    $order_item->product_id = $cart->id->id;
                    $order_item->quantity = $cart->qty;

                    $color = Color::find($cart->options->color_id);
                    if (!empty($color)) {
                        $order_item->color_name = $color->title;
                    }

                    $size = ProductSize::find($cart->options->size_id);
                    if (!empty($size)) {
                        $order_item->size_name = $size->name;
                        $order_item->size_amount = $size->price;
                    }

                    $order_item->total_price = $cart->price;
                    $order_item->save();
                }

                DB::commit();

                

                $toastr = app(Toastr::class);
                $toastr->success('Success', 'Order successful');

            } catch (Exception $e) {
                DB::rollBack();

                $toastr = app(Toastr::class);
                $toastr->error('Error', 'Order Failed');
                return redirect()->back();
            }

            Cart::destroy();

            $getOrder = Order::find($order->id);
            if($getOrder->payment_method == 'cash')
            {
                $getOrder->status = 1;
                $getOrder->save();

                Mail::to($getOrder->email)->send(new OrderInvoiceMail($getOrder));
            }
            else if($getOrder->payment_method == 'paypal')
            {
                return redirect()->route('payment.paypal', ['order_id' => $order->id]);
            }
            else if($getOrder->payment_method == 'stripe')
            {
                return redirect()->route('payment.stripe', ['order_id' => $order->id]);
            }
            return redirect('/');
        }
    }


    // This is for paypal payment

    public function paypal_payment($order_id)
    {
        $getOrder = Order::find($order_id);

        if(!empty($getOrder))
        {
             $query = array();
            $query['business'] = "vipulbusiness1@gmail.com";
            $query['cmd'] = '_xclick';
            $query['item_name'] = "E-commerce";
            $query['no_shipping'] = '1';
            $query['item_number'] = $getOrder->id;
            $query['amount'] = $getOrder->total_amount;
            $query['currency_code'] = 'USD';
            $query['cancel_return'] = url('checkout');
            $query['return'] = url('paypal/success-payment');
            $query_string = http_build_query($query);
            header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?' . $query_string);
            // header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string); I
            exit();
        }
        else
        {
            return redirect()->back();;
        }
       
    }

    public function paypal_success_payment(Request $request)
    {
        if(!empty($request->item_number) && !empty($request->st) && $request->st == 'Completed')
        {
        $getOrder = Order::where('id',$request->item_number)->first();
        if(!empty($getOrder))
        {
            $getOrder->is_payment = 1;
            $getOrder->transaction_id = $request->tx;
            $getOrder->payment_data = json_encode($request->all());
            $getOrder->save();
            Mail::to($getOrder->email)->send(new OrderInvoiceMail($getOrder));
            return redirect('cart')->with('success', "Order successfully placed");
        }
        else
        {
        abort (404);
        }
        }
        else
        {
        abort (404);
        }
    }

    public function stripe_payment($order_id)
    {
        $getOrder = Order::find($order_id);

        if(!empty($getOrder))
        {
            Stripe::setApiKey (env('STRIPE_SECRET'));
            $finalprice = $getOrder->total_amount * 100;
            $session =\Stripe\Checkout\Session::create([
            'customer_email' => $getOrder->email,
            'payment_method_types' => ['card'],
            'line_items' => [ [
            'price_data' => [
            'currency' => 'usd',
            'product_data' => [
            'name' => 'EazyBuy',
            ],
            'unit_amount' => intval($finalprice),
            ],
            'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('stripe/success-payment'),
            'cancel_url' => url('checkout'),
            ]);

            $getOrder->stripe_session_id = $session['id'];
            $getOrder->save();
            $data['session_id'] = $session['id'];
            Session::put('stripe_session_id', $session ['id']);
            $data['setPublicKey'] = env('STRIPE_KEY');
            Mail::to($getOrder->email)->send(new OrderInvoiceMail($getOrder));
            return view('frontend.payment.stripe_charge', $data);
        }
        else
        {
            return redirect()->back();;
        }
    }

    public function stripe_success_payment()
    {
        $trans_id = Session::get('stripe_session_id');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $getdata = \Stripe\Checkout\Session::retrieve($trans_id);
        $getOrder = Order::where('stripe_session_id', '=', $getdata->id)->first();
        if(!empty($getOrder) && !empty($getdata->id) && $getdata->id == $getOrder->stripe_session_id)
        {
            $getOrder->is_payment = 1;
            $getOrder->transaction_id = $getdata->id;
            $getOrder->payment_data = json_encode($getdata);
            $getOrder->save();
            $toastr = app(Toastr::class);
            $toastr->success('Success', 'Order successfully placed');
            return redirect('cart');
        }
        else
        {
            $toastr = app(Toastr::class);
            $toastr->error('Error', 'Due to some error please try again');
            return redirect('cart');
        }
    }
            
}
