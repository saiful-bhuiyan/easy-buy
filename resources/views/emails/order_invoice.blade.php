@component('mail::message')
<p>Dear {{ $order->first_name }},</p>
<p>Thank you for your recent purchase with <strong>EasyBuy</strong>. We are pleased to confirm
    your order and have attached the invoice for your records.</p>
<h3>Order Details:</h3>
@php
    use Carbon\Carbon;
    $count = 1;
    $createdAt = Carbon::parse($order->created_at);
    $order_date = $createdAt->format('d F Y');
@endphp
<ul>
    <li>Order Number: E-000{{ $order->id }}</li>
    <li>Date of Purchase: {{ $order_date }} </li>
    <li>Shipping Address: [{{ $order->adress }} {{ $order->city }} {{ $order->state }}]</li>
</ul>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr>
            <th style="border-bottom: 1px solid #ddd; padding: 8px; text-align: left;">Item</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px; text-align: left;">Quantity</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px; text-align: left;">Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->OrderItem as $v)
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $v->Product->title }} @if($v->color_name != null || $v->size_name != null) [@if($v->color_name !=null){{ $v->color_name }} @endif| @if($v->size_name !=null){{ $v->size_name }} @endif] @endif </td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $v->quantity }}</td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $v->total_price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p>Shipping Charge : {{ $order->shipping_charge }} </p>

@if($order->cuppon_code != null)
<p>Discount Code : {{ $order->cuppon_code }}</p>
<p>Discount Amount : {{ $order->discount_amount }}</p>
@endif
<p>Total Amount : {{ $order->total_amount }} </p>

<p>Payment Method : {{ $order->payment_method }} </p>

+9123323232
<p>Thank you for choosing <strong>E-Commerce</strong>. We appreciate your business.</p>
Thanks,<br>
{{config('app.name') }}

@endcomponent