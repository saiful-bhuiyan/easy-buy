<div class="products mb-3">
    <div class="row justify-content-center">
        @if(!empty($getProduct))
        @foreach($getProduct as $product)
        <div class="col-6 col-md-4 col-lg-4">
            <div class="product product-7 text-center">
                <figure class="product-media">
                    <span class="product-label label-new">New</span>
                    <a href="{{ url($product->slug) }}">
                        @if($product->ProductImage->count() > 0)
                        {{-- Check if there are images --}}
                        <img src="{{ asset('upload/products/' . $product->ProductImage->sortBy('order_by')->first()->image_name) }}" alt="Product image" class="product-image" style=" height:250px; object-fit: cover;">
                        @else
                        {{-- If there are no images, show a placeholder image --}}
                        <img src="{{ asset('frontend') }}/image/no_image.jpg" alt="No Image" class="product-image" style="height:250px; object-fit: cover;">
                        @endif
                    </a>

                    <div class="product-action-vertical">
                        <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                    </div><!-- End .product-action-vertical -->

                    <div class="product-action">
                        <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                    </div><!-- End .product-action -->
                </figure><!-- End .product-media -->

                <div class="product-body">
                    <div class="product-cat">
                        <a href="{{ url($product->SubCategory->Category->slug.'/'.$product->SubCategory->slug) }}">{{ $product->SubCategory->title }}</a>
                    </div><!-- End .product-cat -->
                    <h3 class="product-title"><a href="javascript:;">{{ $product->title }}</a></h3><!-- End .product-title -->
                    <div class="product-price">
                        {{ $product->price }} ৳
                    </div><!-- End .product-price -->
                    <div class="ratings-container">
                        <div class="ratings">
                            <div class="ratings-val" style="width: 20%;"></div><!-- End .ratings-val -->
                        </div><!-- End .ratings -->
                        <span class="ratings-text">( 0 Reviews )</span>
                    </div><!-- End .rating-container -->

                    <div class="product-nav product-nav-thumbs">
                        @foreach($product->maxImages as $image)
                        <a href="#" class="active">
                            <img src="{{ asset('upload') }}/products/{{$image->image_name}}" alt="product desc">
                        </a>
                        @endforeach
                    </div><!-- End .product-nav -->
                </div><!-- End .product-body -->
            </div><!-- End .product -->
        </div><!-- End .col-sm-6 col-lg-4 -->
        @endforeach
        @else
        <h1>No product Found</h1>
        @endif

    </div><!-- End .row -->
</div><!-- End .products -->