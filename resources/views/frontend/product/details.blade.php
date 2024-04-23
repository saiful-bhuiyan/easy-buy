@extends('frontend.layout._master')
@section('body')

<!-- Style Section -->
@section('style')
<link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/plugins/nouislider/nouislider.css">
@endsection

<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container d-flex align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url($getSingleProduct->Category->slug) }}">{{ $getSingleProduct->Category->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url($getSingleProduct->Category->slug.'/'.$getSingleProduct->SubCategory->slug) }}">{{ $getSingleProduct->SubCategory->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $getSingleProduct->title }}</li>
            </ol>
        </div>
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="product-details-top mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-gallery">
                            <figure class="product-main-image">
                                @if($getSingleProduct->ProductImage->count() > 0)
                                {{-- Check if there are images --}}
                                <img id="product-zoom" style="max-height: 500px; object-fit: cover;" src="{{ asset('upload/products/' . $getSingleProduct->ProductImage->sortBy('order_by')->first()->image_name) }}" data-zoom-image="{{ asset('upload/products/' . $getSingleProduct->ProductImage->sortBy('order_by')->first()->image_name) }}" alt="product image">
                                @else
                                <img id="product-zoom" src="{{ asset('frontend/image/' . 'no_image.jpg') }}" data-zoom-image="{{ asset('frontend/image/' . 'no_image.jpg') }}" alt="product image">
                                @endif
                                <a href="#" id="btn-product-gallery" class="btn-product-gallery">
                                    <i class="icon-arrows"></i>
                                </a>
                            </figure><!-- End .product-main-image -->

                            @if($getSingleProduct->ProductImage->count() > 1)
                            <div id="product-zoom-gallery" class="product-image-gallery">
                                @foreach($getSingleProduct->ProductImage as $product_image)
                                <a class="product-gallery-item" href="#" data-image="{{ asset('upload/products/' . $product_image->image_name) }}" data-zoom-image="{{ asset('upload/products/' . $product_image->image_name) }}">
                                    <img src="{{ asset('upload/products/' . $product_image->image_name) }}" alt="product side">
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div><!-- End .product-gallery -->
                    </div><!-- End .col-md-6 -->

                    <div class="col-md-6">
                        <div class="product-details">
                            <h1 class="product-title">{{ $getSingleProduct->title }}</h1>

                            <div class="ratings-container">
                                <div class="ratings">
                                    <div class="ratings-val" style="width: 80%;"></div>
                                </div>
                                <a class="ratings-text" href="#product-review-link" id="review-link">( 2 Reviews )</a>
                            </div>

                            <div class="product-price">
                            {{ $getSingleProduct->price }} ৳
                            </div>
                            <div class="product-content">
                                <div>{!! $getSingleProduct->short_description !!}</div>
                            </div>

                            <form id="productForm">
                            <input type="hidden" name="product_id" value="{{ $getSingleProduct->id }}">
                            @if(!empty($getSingleProduct->ProductColor))
                            <div class="details-filter-row details-row-size">
                                <label for="color">Color:</label>
                                <div class="select-custom">
                                    <select name="color_id" id="color" class="form-control">
                                        <option value="" selected>Select a color</option>
                                        @foreach($getSingleProduct->ProductColor as $p_color)
                                        <option value="{{ $p_color->Color->id }}">{{ $p_color->Color->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            @if(!empty($getSingleProduct->ProductSize))
                            <div class="details-filter-row details-row-size">
                                <label for="size">Size:</label>
                                <div class="select-custom">
                                    <select name="size_id" id="size" class="form-control" >
                                        <option value="" data-price="0" selected>Select a size</option>
                                        @foreach($getSingleProduct->ProductSize as $p_size)
                                        <option value="{{ $p_size->id }}" data-price="{{ $p_size->price ? $p_size->price : 0 }}">{{ $p_size->name }} @if(!empty($p_size->price)) +({{ $p_size->price }}৳) @endif</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="details-filter-row details-row-size">
                                <label for="qty">Qty:</label>
                                <div class="product-details-quantity">
                                    <input type="number" id="qty" name="qty" class="form-control" value="1" min="1" max="10" step="1" data-decimals="0" required>
                                </div>
                            </div>

                            <div class="product-details-action">
                                <button type="submit" class="btn-product btn-cart"><span>add to cart</span></button>

                                <div class="details-action-wrapper">
                                    <a href="#" class="btn-product btn-wishlist" title="Wishlist"><span>Add to Wishlist</span></a>
                                </div>
                            </div>
                            </form>

                            <div class="product-details-footer">
                                <div class="product-cat">
                                    <span>Category:</span>
                                    <a href="{{ url($getSingleProduct->Category->slug) }}">{{ $getSingleProduct->Category->title }}</a>,
                                    <a href="{{ url($getSingleProduct->Category->slug.'/'.$getSingleProduct->SubCategory->slug) }}">{{ $getSingleProduct->SubCategory->title }}</a>
                                </div><!-- End .product-cat -->

                                <div class="social-icons social-icons-sm">
                                    <span class="social-label">Share:</span>
                                    <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                    <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                    <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                    <a href="#" class="social-icon" title="Pinterest" target="_blank"><i class="icon-pinterest"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-details-tab product-details-extended">
            <div class="container">
                <ul class="nav nav-pills justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-desc-link" data-toggle="tab" href="#product-desc-tab" role="tab" aria-controls="product-desc-tab" aria-selected="true">Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-info-link" data-toggle="tab" href="#product-info-tab" role="tab" aria-controls="product-info-tab" aria-selected="false">Additional information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-shipping-link" data-toggle="tab" href="#product-shipping-tab" role="tab" aria-controls="product-shipping-tab" aria-selected="false">Shipping & Returns</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-review-link" data-toggle="tab" href="#product-review-tab" role="tab" aria-controls="product-review-tab" aria-selected="false">Reviews (2)</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="product-desc-tab" role="tabpanel" aria-labelledby="product-desc-link">
                    <div class="product-desc-content">
                        <div class="container mt-4">
                            {!! $getSingleProduct->description !!}
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="product-info-tab" role="tabpanel" aria-labelledby="product-info-link">
                    <div class="product-desc-content">
                        <div class="container mt-4">
                        {!! $getSingleProduct->additional_information !!}
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="product-shipping-tab" role="tabpanel" aria-labelledby="product-shipping-link">
                    <div class="product-desc-content">
                        <div class="container mt-4">
                        {!! $getSingleProduct->shipping_returns !!}
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="product-review-tab" role="tabpanel" aria-labelledby="product-review-link">
                    <div class="reviews">
                        <div class="container mt-4">
                            <h3>Reviews (2)</h3>
                            <div class="review">
                                <div class="row no-gutters">
                                    <div class="col-auto">
                                        <h4><a href="#">Samanta J.</a></h4>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-val" style="width: 80%;"></div>
                                            </div>
                                        </div>
                                        <span class="review-date">6 days ago</span>
                                    </div>
                                    <div class="col">
                                        <h4>Good, perfect size</h4>

                                        <div class="review-content">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus cum dolores assumenda asperiores facilis porro reprehenderit animi culpa atque blanditiis commodi perspiciatis doloremque, possimus, explicabo, autem fugit beatae quae voluptas!</p>
                                        </div>

                                        <div class="review-action">
                                            <a href="#"><i class="icon-thumbs-up"></i>Helpful (2)</a>
                                            <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="review">
                                <div class="row no-gutters">
                                    <div class="col-auto">
                                        <h4><a href="#">John Doe</a></h4>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-val" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <span class="review-date">5 days ago</span>
                                    </div>
                                    <div class="col">
                                        <h4>Very good</h4>

                                        <div class="review-content">
                                            <p>Sed, molestias, tempore? Ex dolor esse iure hic veniam laborum blanditiis laudantium iste amet. Cum non voluptate eos enim, ab cumque nam, modi, quas iure illum repellendus, blanditiis perspiciatis beatae!</p>
                                        </div>

                                        <div class="review-action">
                                            <a href="#"><i class="icon-thumbs-up"></i>Helpful (0)</a>
                                            <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                        </div><!-- End .review-action -->
                                    </div><!-- End .col-auto -->
                                </div><!-- End .row -->
                            </div><!-- End .review -->
                        </div><!-- End .container -->
                    </div><!-- End .reviews -->
                </div><!-- .End .tab-pane -->
            </div><!-- End .tab-content -->
        </div><!-- End .product-details-tab -->

        <div class="container">
            <h2 class="title text-center mb-4">You May Also Like</h2>
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" data-owl-options='{
                            "nav": false, 
                            "dots": true,
                            "margin": 20,
                            "loop": false,
                            "responsive": {
                                "0": {
                                    "items":1
                                },
                                "480": {
                                    "items":2
                                },
                                "768": {
                                    "items":3
                                },
                                "992": {
                                    "items":4
                                },
                                "1200": {
                                    "items":4,
                                    "nav": true,
                                    "dots": false
                                }
                            }
                        }'>
                @foreach($getRelatedProduct as $product)
                <div class="product product-7">
                    <figure class="product-media">
                        <span class="product-label label-new">New</span>
                        <a href="{{ url($product->slug) }}" style="height: 270px;">
                            @if($product->ProductImage->count() > 0)
                            {{-- Check if there are images --}}
                            <img src="{{ asset('upload/products/' . $product->ProductImage->sortBy('order_by')->first()->image_name) }}" alt="Product image" class="product-image">
                            @else 
                            <img src="{{ asset('frontend/image/' . 'no_image.jpg') }}" alt="Product image" class="product-image">
                            @endif
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                        </div>

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div>
                    </figure>

                    <div class="product-body">
                        <div class="product-cat">
                        @if(!empty($product->SubCategory) && $product->Category)
                        <a href="{{ url($product->SubCategory->Category->slug.'/'.$product->SubCategory->slug) }}">{{ $product->SubCategory->title }}</a>
                        @endif
                        </div>
                        <h3 class="product-title"><a href="{{ url($product->slug) }}">{{ $product->title }}</a></h3>
                        <div class="product-price">
                            {{ $product->price }} ৳
                        </div>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 20%;"></div>
                            </div>
                            <span class="ratings-text">( 2 Reviews )</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

<!-- Script Section -->
@section('script')
<script src="{{ asset('frontend') }}/assets/js/jquery.elevateZoom.min.js"></script>
<script src="{{ asset('frontend') }}/assets/js/bootstrap-input-spinner.js"></script>
<script>
    $('#size').on('change',function(){
        var price = $(this).find('option:selected').data('price');
        var prdocut_price = '{{ $getSingleProduct->price ? $getSingleProduct->price : 0 }}';
        var total_price = parseInt(price) + parseInt(prdocut_price);
        total_price = total_price + ' ৳';
        $('.product-price').html(total_price);
    })
</script>

<script>
    $('#productForm').on('submit',function(event){
        event.preventDefault();
        $.ajax({
            type : 'POST',
            url : '{{ url("product/add-to-cart") }}',
            data : $('#productForm').serialize(),
            beforeSend : function()
            {
                console.log('please wait....')
            },
            success : function(response)
            {
                toastr.success(response.msg);
                $('#load-cart').html(response.html);
            }

        })
    })
</script>
@endsection
<!-- Body Section -->
@endsection