@extends('frontend.layout._master')
@section('body')

@section('style')
<link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/plugins/nouislider/nouislider.css">
<style>
    .active-color {
        border: 2px solid black !important;
        box-shadow: 1px 1px 1px 1px;
    }
</style>
@endsection
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('frontend') }}/assets/images/page-header-bg.jpg')">
        <div class="container">
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">Shop</a></li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <!-- Product Here -->
                    <div class="toolbox">
                        <div class="toolbox-left">
                            <div class="toolbox-info">
                              
                            </div><!-- End .toolbox-info -->
                        </div><!-- End .toolbox-left -->

                        <div class="toolbox-right">
                            <div class="toolbox-sort">
                                <label for="sortby">Sort by:</label>
                                <div class="select-custom">
                                    <select name="sortby" id="sortby" class="form-control">
                                        <option value="" selected>Select</option>
                                        <option value="popularity">Most Popular</option>
                                        <option value="rating">Most Rated</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                            </div><!-- End .toolbox-sort -->
                        </div><!-- End .toolbox-right -->
                    </div><!-- End .toolbox -->
                    <div id="getProductAjax">
                        @include('frontend.product.product_list')
                    </div>
                    <div class="text-center">
                        <a href="javascript:;" @if(empty($page)) style="display: none;" @endif data-page="{{ $page }}" class="btn btn-primary LoadMore">Load More</a>
                    </div>

                </div><!-- End .col-lg-9 -->
                <aside class="col-lg-3 order-lg-first">
                    <form id="filter_form" method="POST" hidden>
                        <input type="text" name="sub_category_list" id="sub_category_list" value="">
                        <input type="text" name="brand_list" id="brand_list">
                        <input type="text" name="color_list" id="color_list">
                        <input type="text" name="sort_list" id="sort_list">
                        <input type="text" name="get_start_price" id="get_start_price">
                        <input type="text" name="get_end_price" id="get_end_price">
                    </form>
                    <div class="sidebar sidebar-shop">
                        <div class="widget widget-clean">
                            <label>Filters:</label>
                            <a href="#" class="sidebar-filter-clear">Clean All</a>
                        </div><!-- End .widget widget-clean -->


                        @if($getColor)
                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-3" role="button" aria-expanded="true" aria-controls="widget-3">
                                    Colors
                                </a>
                            </h3><!-- End .widget-title -->

                            <div class="collapse show" id="widget-3">
                                <div class="widget-body">
                                    <div class="filter-colors">
                                        @foreach($getColor as $color)
                                        <a href="javascript:;" id="{{ $color->id }}" data-val="0" class="ChangeColor" style="background: {{ $color->code }};border: 1px solid black;"><span class="sr-only">{{ $color->title }}</span></a>
                                        @endforeach
                                    </div><!-- End .filter-colors -->
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->
                        @endif

                        @if($getBrand)
                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-4" role="button" aria-expanded="true" aria-controls="widget-4">
                                    Brand
                                </a>
                            </h3><!-- End .widget-title -->

                            <div class="collapse show" id="widget-4">
                                <div class="widget-body">
                                    <div class="filter-items">

                                        @foreach($getBrand as $brand)
                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input ChangeBrand" id="brand-{{ $brand->id }}" value="{{ $brand->id }}">
                                                <label class="custom-control-label" for="brand-{{ $brand->id }}">{{ $brand->title }}</label>
                                            </div><!-- End .custom-checkbox -->
                                        </div><!-- End .filter-item -->
                                        @endforeach

                                    </div><!-- End .filter-items -->
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->
                        @endif

                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true" aria-controls="widget-5">
                                    Price
                                </a>
                            </h3><!-- End .widget-title -->

                            <div class="collapse show" id="widget-5">
                                <div class="widget-body">
                                    <div class="filter-price">
                                        <div class="filter-price-text">
                                            Price Range:
                                            <span id="filter-price-range"></span>
                                        </div><!-- End .filter-price-text -->

                                        <div id="price-slider"></div><!-- End #price-slider -->
                                    </div><!-- End .filter-price -->
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->
                    </div><!-- End .sidebar sidebar-shop -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->

@section('script')
<script src="{{ asset('frontend') }}/assets/js/wNumb.js"></script>
<script src="{{ asset('frontend') }}/assets/js/bootstrap-input-spinner.js"></script>
<script src="{{ asset('frontend') }}/assets/js/nouislider.min.js"></script>

<script>
    var xhr;

    $('#sortby').change(function() {
        var sortby = $(this).val();
        $('#sort_list').val(sortby);
        FilterForm();
    })

    $('.ChangeCategory').change(function() {
        var sub_cat_id = $(this).val();
        var ids = '';
        $('.ChangeCategory').each(function() {
            if (this.checked) {
                var sub_cat_id = $(this).val();
                ids += sub_cat_id + ',';
            }
        })
        $('#sub_category_list').val(ids);
        FilterForm();
    })

    $('.ChangeBrand').change(function() {
        var brand_id = $(this).val();
        var ids = '';
        $('.ChangeBrand').each(function() {
            if (this.checked) {
                var brand_id = $(this).val();
                ids += brand_id + ',';
            }
            $('#brand_list').val(ids);
            FilterForm();
        })
    })

    $('.ChangeColor').click(function() {
        var color_id = $(this).attr('id');
        var status = $(this).attr('data-val');
        if (status == 0) {
            $(this).attr('data-val', 1);
            $(this).addClass('active-color')
        } else {
            $(this).attr('data-val', 0);
            $(this).removeClass('active-color')
        }

        var ids = '';
        $('.ChangeColor').each(function() {
            var status = $(this).attr('data-val');
            if (status == 1) {
                var color_id = $(this).attr('id');
                ids += color_id + ',';
            }
        })
        $('#color_list').val(ids);
        FilterForm();
    })

    function FilterForm() {
        $.ajax({
            type: 'POST',
            url: '{{ url("get_filter_product") }}',
            data: $('#filter_form').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#getProductAjax').html('<h1>Loading</h1>');
            },
            success: function(response, status, xhr) {
                if (response.status == true) {
                    $('#getProductAjax').html(response.success);
                    if(response.page == 0)
                    {
                        $('.LoadMore').hide(); 
                    }
                    else
                    {
                        $('.LoadMore').show(); 
                    }
                }
            },
            error: function(response, status, xhr) {
                // toastr.error(response.message)
            }
        })
    }

    $('body').delegate('.LoadMore', 'click', function() {
        var page = $(this).attr('data-page');
        if (xhr && xhr.readyState != 4) {
            xhr.abort();
        }

        xhr = $.ajax({
            type: 'POST',
            url: '{{ url("get_filter_product") }}?page=' + page,
            data: $('#filter_form').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('.LoadMore').html('Loading');
            },
            success: function(response, status, xhr) {
                $('#getProductAjax').append(response.success);
                $('.LoadMore').attr('data-page', response.page);
                $('.LoadMore').html('Load More');
                if(response.page == 0)
                {
                    $('.LoadMore').hide(); 
                }
            },
            error: function(response, status, xhr) {

            }
        })
    })

    // Price Slider

    $(document).ready(function() {
        // Initialize Price Slider
        if (typeof noUiSlider === 'object') {
            var priceSlider = document.getElementById('price-slider');

            noUiSlider.create(priceSlider, {
                start: [0, 1000],
                connect: true,
                step: 10,
                margin: 200,
                range: {
                    'min': 0,
                    'max': 1000
                },
                tooltips: true,
                format: wNumb({
                    decimals: 0,
                    prefix: ''
                })
            });

            var sliderInitialized = false;
            var debounceTimer;

            // Update Price Range
            priceSlider.noUiSlider.on('update', function(values, handle) {
                // Check if the slider has been initialized
                if (sliderInitialized) {
                    var start_price = values[0];
                    var end_price = values[1];
                    $('#get_start_price').val(start_price);
                    $('#get_end_price').val(end_price);
                    $('#filter-price-range').text(values.join(' - '));
                    // Call FilterForm with a delay using a debounce timer
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        FilterForm();
                    }, 500); // Adjust the delay time as needed
                }
            });

            // Set sliderInitialized to true after the initial load
            sliderInitialized = true;
        }
    })
</script>
@endsection


@endsection