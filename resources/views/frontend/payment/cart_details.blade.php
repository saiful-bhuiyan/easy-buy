<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <table class="table table-cart table-mobile">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @if(Cart::count())
                    @foreach(Cart::content() as $cart)
                    @if($cart->id)
                    <tr>
                        <td class="product-col">
                            <div class="product">
                                <figure class="product-media">
                                    <a href="#">
                                        @if($cart->id->ProductImage->count() > 0)
                                        {{-- Check if there are images --}}
                                        <img src="{{ asset('upload/products/' . $cart->id->ProductImage->sortBy('order_by')->first()->image_name) }}" alt="product">
                                        @else
                                        <img id="product-zoom" src="{{ asset('frontend/image/' . 'no_image.jpg') }}" alt="product image">
                                        @endif
                                    </a>
                                </figure>

                                <h3 class="product-title">
                                    <a href="{{ url($cart->id->slug) }}">
                                        @if($cart->options->size_id !== null)
                                        @php
                                        $productSize = \App\Models\ProductSize::where('id',$cart->options->size_id)->first();
                                        @endphp
                                        @if($productSize->price == null)
                                        {{ $cart->id->title }} ( {{ $productSize->name }} )
                                        @else
                                        {{ $cart->id->title }} ( {{ $productSize->name }} +{{ $productSize->price }}৳ )
                                        @endif

                                        @else
                                        {{ $cart->id->title }}
                                        @endif
                                    </a>
                                </h3><!-- End .product-title -->
                            </div><!-- End .product -->
                        </td>



                        @if($cart->options->size_id !== null)
                        @if($productSize->price == null)
                        <td class="price-col">
                            {{ $cart->id->price }}৳
                        </td>
                        @else
                        <td class="price-col">
                            {{ $cart->id->price +  $productSize->price }}৳
                        </td>
                        @endif
                        @else
                        <td class="price-col">
                            {{ $cart->id->price }}৳
                        </td>
                        @endif
                        <td class="quantity-col">
                            <div class="cart-product-quantity">
                                <input type="number" class="form-control" id="product-qty" data-cartid="{{ $cart->rowId }}" value="{{ $cart->qty }}" min="1" max="10" step="1" data-decimals="0" required>
                            </div>
                        </td>
                        <td class="total-col">{{ $cart->total() }}৳</td>
                        <td class="remove-col"><button class="btn-remove remove-cart" data-cartid="{{ $cart->rowId }}"><i class="icon-close"></i></button></td>
                    </tr>
                    @endif
                    @endforeach

                    @else
                    <tr class="product-col text-center">No Product found</tr>
                    @endif
                </tbody>
            </table><!-- End .table table-wishlist -->

            <div class="cart-bottom">
                <div class="cart-discount">
                    <form action="#">
                        <div class="input-group">
                            <input type="text" class="form-control" required placeholder="coupon code">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary-2" type="submit"><i class="icon-long-arrow-right"></i></button>
                            </div><!-- .End .input-group-append -->
                        </div><!-- End .input-group -->
                    </form>
                </div><!-- End .cart-discount -->

                <a href="#" class="btn btn-outline-dark-2"><span>UPDATE CART</span><i class="icon-refresh"></i></a>
            </div><!-- End .cart-bottom -->
        </div><!-- End .col-lg-9 -->
        <aside class="col-lg-3">
            <div class="summary summary-cart">
                <h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->

                <table class="table table-summary">
                    <tbody>
                        <tr class="summary-subtotal">
                            <td>Subtotal:</td>
                            <td>{{ Cart::total() }}৳</td>
                        </tr><!-- End .summary-subtotal -->
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

                        <tr class="summary-total">
                            <td>Total:</td>
                            <td>{{ Cart::total() }}৳</td>
                        </tr><!-- End .summary-total -->

                        <div id="loader" class="spinner-border text-primary" role="status" style="display: none;">
                            <span class="visually-hidden"></span>
                        </div>
                    </tbody>
                </table><!-- End .table table-summary -->

                <a href="{{ url('checkout') }}" class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO CHECKOUT</a>
            </div><!-- End .summary -->

            <a href="{{ url('/') }}" class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE SHOPPING</span><i class="icon-refresh"></i></a>
        </aside><!-- End .col-lg-3 -->
    </div><!-- End .row -->
</div><!-- End .container -->