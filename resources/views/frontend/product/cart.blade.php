<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
    <i class="icon-shopping-cart"></i>
    <span class="cart-count">{{ Cart::count() }}</span>
</a>

<div class="dropdown-menu dropdown-menu-right">
    <div class="dropdown-cart-products">
        @if(Cart::count())
        @foreach(Cart::content() as $cart)
        @if($cart->id)
        <div class="product">
            <div class="product-cart-details">
                <h4 class="product-title">
                    <a href="{{ url($cart->id->slug) }}">{{ $cart->id->title }}</a>
                </h4>

                <span class="cart-product-info">
                    @if($cart->options->size_id !== null)
                    @php
                    $productSize = \App\Models\ProductSize::where('id',$cart->options->size_id)->first();
                    @endphp
                    @if($productSize->price == null)
                    <span class="cart-product-qty">{{ $cart->qty }}</span>
                    x {{ $cart->id->price }}৳ - {{ $productSize->name }}
                    @else
                    <span class="cart-product-qty">{{ $cart->qty }}</span>
                    x {{ $cart->id->price }}৳ - {{ $productSize->name }} +{{ $productSize->price }}৳
                    @endif
                    @else
                    <span class="cart-product-qty">{{ $cart->qty }} x {{ $cart->id->price }}৳</span>
                    @endif
                </span>
            </div>
            <figure class="product-image-container">
                <a href="{{ url($cart->id->slug) }}" class="product-image">
                    @if($cart->id->ProductImage->count() > 0)
                    {{-- Check if there are images --}}
                    <img src="{{ asset('upload/products/' . $cart->id->ProductImage->sortBy('order_by')->first()->image_name) }}" alt="product">
                    @else
                    <img id="product-zoom" src="{{ asset('frontend/image/' . 'no_image.jpg') }}" alt="product image">
                    @endif
                </a>
            </figure>
            <a href="#" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a>
        </div>
        @endif
        @endforeach
        @else

        @endif

    </div><!-- End .cart-product -->

    @if(Cart::count())
    <div class="dropdown-cart-total">
        <span>Total</span>
        <span class="cart-total-price">{{ Cart::total() }} ৳</span>
    </div>
    @else
    <div class="dropdown-cart-total">
        <h5>No Product in Cart</h5>
    </div>
    @endif
    <div class="dropdown-cart-action">
        <a href="{{ url('cart') }}" class="btn btn-primary">View Cart</a>
        <a href="{{ url('checkout') }}" class="btn btn-outline-primary-2"><span>Checkout</span><i class="icon-long-arrow-right"></i></a>
    </div>
</div><!-- End .dropdown-menu -->