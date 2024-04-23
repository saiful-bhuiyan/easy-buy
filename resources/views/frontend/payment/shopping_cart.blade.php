@extends('frontend.layout._master')
@section('body')

<!-- Style Section -->
@section('style')

@endsection

<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('frontend') }}/assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Shopping Cart<span>Shop</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="cart" id="cart-table">
            @include('frontend.payment.cart_details')
        </div><!-- End .cart -->
    </div><!-- End .page-content -->
</main><!-- End .main -->


<!-- Script Section -->
@section('script')
<script>
    $(document).on('click','.remove-cart',function(){
        var id = $(this).data('cartid');
        if(id != "")
        {
            $.ajax({
                type : 'GET',
                url : '{{ url("cart-delete") }}/'+id,
                beforeSend : function()
                {

                },
                success : function(response)
                {
                    toastr.success(response.msg)
                    $('#cart-table').html(response.html);
                    $('#load-cart').html(response.cart);
                },
                error : function(xhr,response,error)
                {

                }
            })
        }
    }) 

    $(document).on('change','#product-qty',function(){
        var qty = parseInt($(this).val());
        var rowId = $(this).data('cartid');
        if(qty > 0 && rowId != "")
        {
            $.ajax({
                type : 'GET',
                url : '{{ url("cart-update") }}/'+qty+'/'+rowId,
                beforeSend : function()
                {
                    $('#loader').show();
                    $('#cart-table').css('pointer-events', 'none').css('opacity', '0.5');
                    $('input').prop('disabled', true);
                },
                success : function(response)
                {
                    toastr.success(response.msg)
                    $('#cart-table').html(response.html);
                    $('#load-cart').html(response.cart);
                    $('#loader').hide();
                    $('#cart-table').css('pointer-events', 'auto').css('opacity', '1');
                    $('input').prop('disabled', false);
                    
                },
                error : function(xhr,response,error)
                {
                    toastr.success(error.msg)
                    $('#loader').hide();
                    $('#cart-table').css('pointer-events', 'auto').css('opacity', '1');
                    $('input').prop('disabled', false);
                }
            })
        }
    })
</script>
@endsection
<!-- Body Section -->
@endsection