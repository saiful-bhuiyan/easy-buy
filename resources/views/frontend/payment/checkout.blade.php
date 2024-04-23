@extends('frontend.layout._master')
@section('body')

<!-- Style Section -->
@section('style')

@endsection

<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('frontend') }}/assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Checkout<span>Shop</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="checkout">
            <div class="container">
                <!-- <div class="checkout-discount">
                    <form>
                      
                        <input type="text" class="form-control" required id="checkout-discount-input">
                        <label for="checkout-discount-input" class="text-truncate">Have a coupon? <span>Click here to enter your code</span></label>
                    </form>
                </div> -->
                <form action="{{ url('checkout/place_order') }}" method="POST">
                @csrf
                    <div class="row">
                        <div class="col-lg-9">
                            <h2 class="checkout-title">Billing Details</h2><!-- End .checkout-title -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>First Name *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label>Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <label>Company Name (Optional)</label>
                            <input type="text" name="company_name" class="form-control">

                            <label>Street address *</label>
                            <input type="text" class="form-control" name="address" placeholder="House number and Street name" required>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Town / City *</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>

                                <div class="col-sm-6">
                                    <label>State </label>
                                    <input type="text" name="state" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Post code</label>
                                    <input type="text" name="post_code" class="form-control" >
                                </div>

                                <div class="col-sm-6">
                                    <label>Zip code </label>
                                    <input type="text" name="zip_code" class="form-control" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Phone *</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <label>Email address *</label>
                            <input type="email" name="email" class="form-control" required>

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkout-create-acc">
                                <label class="custom-control-label" for="checkout-create-acc">Create an account?</label>
                            </div><!-- End .custom-checkbox -->

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkout-diff-address">
                                <label class="custom-control-label" for="checkout-diff-address">Ship to a different address?</label>
                            </div><!-- End .custom-checkbox -->

                            <label>Order notes (optional)</label>
                            <textarea class="form-control" name="order_notes" cols="30" rows="4" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                        </div><!-- End .col-lg-9 -->
                        <aside class="col-lg-3">
                            <div class="summary">
                                <h3 class="summary-title">Your Order</h3><!-- End .summary-title -->

                                <table class="table table-summary">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if(Cart::count())
                                        @foreach(Cart::content() as $cart)
                                        @if($cart->id)
                                        @php
                                        $productColor = \App\Models\Color::where('id',$cart->options->color_id)->first();
                                        @endphp
                                        <tr>
                                            <td><a href="{{ $cart->id->slug }}">
                                                    @if($cart->options->size_id !== null)
                                                    @php
                                                    $productSize = \App\Models\ProductSize::where('id',$cart->options->size_id)->first();
                                                    @endphp
                                                    @if($productSize->price == null)
                                                    {{ $cart->id->title }} ({{ $productSize->name }})/ {{ $productColor->title }}
                                                    @else
                                                    {{ $cart->id->title }} ( {{ $productSize->name }}/ {{ $productColor->title }} +{{ $productSize->price }}৳)
                                                    @endif
                                                    @else
                                                    {{ $cart->id->title }} / {{ $productColor->title }}
                                                    @endif
                                                </a></td>
                                            <td>{{ $cart->total() }}৳</td>
                                        </tr>
                                        @else

                                        @endif
                                        @endforeach
                                        @else

                                        @endif
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td>{{ Cart::total() }}৳</td>
                                        </tr>
                                        <tr class="summary-subtotal">
                                            <td>Discount:</td>
                                            <td class="DiscountAmount">0৳</td>
                                        </tr>
                                        <tr class="summary-shipping">
                                            <td>Shipping:</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        @foreach($shipping as $v)
                                        <tr class="summary-shipping-row">
                                            <td>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="free-shipping{{ $v->id }}" data-id="{{ $v->id }}" value="{{ $v->id }}" name="shipping" class="custom-control-input Shipping">
                                                    <label class="custom-control-label" for="free-shipping{{ $v->id }}">{{ $v->name }}</label>
                                                </div>
                                            </td>
                                            <td>{{ $v->price }}৳</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td>Shipping:</td>
                                            <td>Free shipping</td>
                                        </tr>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td class="Total">{{ Cart::total() }}৳</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="cart-discount">
                                    <form action="#">
                                        <div class="input-group">
                                            <input type="text" id="discount_code" name="discount_code" class="form-control bg-white"  placeholder="coupon code">
                                            <div class="input-group-append" style="height: 40px;">
                                                <button id="ApplyDiscount" class="btn btn-outline-primary-2" type="button"><i class="icon-long-arrow-right"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div><!-- End .cart-discount -->

                                <div class="accordion-summary" id="accordion-payment">

                                    <div class="custom-control custom-radio my-0">
                                        <input type="radio" id="cash" value="cash" name="payment" class="custom-control-input">
                                        <label class="custom-control-label" for="cash">Cash On Delivery</label>
                                    </div>

                                    <div class="custom-control custom-radio my-0">
                                        <input type="radio" id="paypal" value="paypal" name="payment" class="custom-control-input">
                                        <label class="custom-control-label" for="paypal">Paypal</label>
                                    </div>

                                    <div class="custom-control custom-radio my-0">
                                        <input type="radio" id="stripe" value="stripe" name="payment" class="custom-control-input">
                                        <label class="custom-control-label" for="stripe">Stripe</label>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-outline-primary-2 btn-order btn-block">
                                    <span class="btn-text">Place Order</span>
                                    <span class="btn-hover-text">Proceed to Checkout</span>
                                </button>
                            </div><!-- End .summary -->
                        </aside><!-- End .col-lg-3 -->
                    </div><!-- End .row -->
                </form>
            </div><!-- End .container -->
        </div><!-- End .checkout -->
    </div><!-- End .page-content -->
</main><!-- End .main -->

<!-- Script Section -->
@section('script')
<script>
    function updatePrice() {
        var discount_code = $('#discount_code').val();
        var shipping_id = $('.Shipping:checked').data('id');
    
            $.ajax({
                type: 'POST',
                url: '{{ url("checkout/apply_cuppon") }}',
                data: {
                    discount_code: discount_code,
                    shipping_id: shipping_id
                },
                beforeSend: function() {

                },
                success: function(response) {
                    toastr.success(response.message)
                    $('.Total').html(response.payable_amount + '৳')
                    $('.DiscountAmount').html(response.discount_amount + '৳')

                },
                error: function(xhr, response, error) {

                }
            })
    }
    $(document).ready(function(){
         $(document).on('click', '#ApplyDiscount', function() {
        updatePrice()
        })
        $('input[type=radio][name=shipping]').on('change', function() {
            updatePrice()
        })
    })
   
</script>
@endsection
<!-- Body Section -->
@endsection