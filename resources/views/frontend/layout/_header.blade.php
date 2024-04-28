<style>
     .ProductDropdown{
        max-height : 350px;
        padding : 10px;
        overflow: auto;
    }

    /* Custom scrollbar track */
    .ProductDropdown::-webkit-scrollbar-track {
        background-color: #f1f1f1; /* Color of the scrollbar track */
    }

    /* Custom scrollbar thumb */
    .ProductDropdown::-webkit-scrollbar-thumb {
        background-color: #888; /* Color of the scrollbar thumb */
        border-radius: 5px; /* Rounded corners */
    }

    /* Optional: Slimmer scrollbar */
    .ProductDropdown::-webkit-scrollbar {
        width: 4px; /* Width of the scrollbar */
    }

    /* Optional: Show scrollbar only on hover */
    .ProductDropdown:hover::-webkit-scrollbar-thumb {
        background-color: #555; /* Change thumb color on hover */
    }
    .search_item{
        height: 50px;
        display :flex;
        align-items :center;
        border-radius: 2px;
        margin-top: 5px;
        margin-bottom: 5px;
        position: relative;
    }
    .search_item img{
        border-radius: 2px;
        width: 40px;
        height: 50px;
    }

    .search_item p{
        font-weight: 500;
        padding-left: 15px;
    }
    .search_item:hover{
        background-color: #e8e8e8;
    }

    .search_item::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%; 
        height: .5px; 
        background-color: #ccc; 
    }
    .search_item .old-price
    {
        color: red;
        padding-left: 6px;
    }
    .search_item .price
    {
        color: #000000;
    }

   
</style>

<header class="header">
            <div class="header-top">
                <div class="container">
                    <div class="header-left">
                        <div class="header-dropdown">
                            <a href="#">Currency</a>
                            <div class="header-menu">
                                <ul>
                                    <li><a href="#">Taka</a></li>
                                </ul>
                            </div><!-- End .header-menu -->
                        </div><!-- End .header-dropdown -->

                        <div class="header-dropdown">
                            <a href="#">Language</a>
                            <div class="header-menu">
                                <ul>
                                    <li><a href="#">English</a></li>
                                </ul>
                            </div><!-- End .header-menu -->
                        </div><!-- End .header-dropdown -->
                    </div><!-- End .header-left -->

                    <div class="header-right">
                        <ul class="top-menu">
                            <li>
                                <a href="#">Links</a>
                                <ul>
                                    <li><a href="tel:#"><i class="icon-phone"></i>Call: +0123 456 789</a></li>
                                    <li><a href="{{ url('wishlist') }}"><i class="icon-heart-o"></i>My Wishlist <span>(3)</span></a></li>
                                    <li><a href="{{ url('about_us') }}">About Us</a></li>
                                    <li><a href="{{ url('contact_us') }}">Contact Us</a></li>
                                    @if(!empty(Auth::check()))
                                    <li><a href="{{ url('auth_logout') }}"><i class="icon-user"></i>Logout</a></li>
                                    @else
                                    <li><a href="#signin-modal" data-toggle="modal"><i class="icon-user"></i>Login</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul><!-- End .top-menu -->
                    </div><!-- End .header-right -->
                </div><!-- End .container -->
            </div><!-- End .header-top -->

            <div class="header-middle sticky-header">
                <div class="container">
                    <div class="header-left">
                        <button class="mobile-menu-toggler">
                            <span class="sr-only">Toggle mobile menu</span>
                            <i class="icon-bars"></i>
                        </button>

                        <a href="{{ url('/') }}" class="logo">
                            <img src="{{ asset('frontend') }}/image/eazybuy_logo.jpg" style="height: 45px;" alt="Easy Buy Logo" width="105">
                        </a>

                        <nav class="main-nav">
                            <ul class="menu sf-arrows">
                                <li class="megamenu-container active">
                                    <a href="{{ url('/') }}" class="">Home</a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="sf-with-ul">Shop</a>

                                    <div class="megamenu megamenu-md">
                                        <div class="row no-gutters">
                                            <div class="col-md-12">
                                                <div class="menu-col">
                                                    <div class="row">
                                                        @foreach($Category as $cat)
                                                        @if($cat->SubCategory->count() > 0)
                                                        <div class="col-md-4 mb-2">
                                                            <a href="{{ url($cat->slug) }}" class="menu-title">{{ $cat->title }}</a><!-- End .menu-title -->
                                                            <ul>
                                                            @foreach($cat->SubCategory as $sub_cat)
                                                                <li><a href="{{ url($cat->slug.'/'.$sub_cat->slug) }}">{{ $sub_cat->title }}</a></li>
                                                            @endforeach
                                                            </ul>
                                                        </div><!-- End .col-md-4 -->
                                                        @endif
                                                        @endforeach

                                                        <!-- <li><a href="category-market.html"><span>Shop Market<span class="tip tip-new">New</span></span></a></li> -->

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="header-right">
                        <div class="header-search" >
                            <a href="#" class="search-toggle" role="button" title="Search"><i class="icon-search"></i></a>
                            <form action="{{ url('search') }}" method="get">
                                @csrf
                                <div class="header-search-wrapper" style="min-width: 600px;display:block;">
                                    <label for="q" class="sr-only">Search</label>
                                    <input type="search" class="form-control typeahead" name="q" id="q" placeholder="Search in..." autocomplete="off" required>
                                    <div class="ProductDropdown d-none" for="q">
                                       
                                    </div>
                                </div>
                            </form>
                            
                        </div>

                        <div class="dropdown cart-dropdown" id="load-cart">
                            @include('frontend.product.cart')
                        </div>
                    </div>
                </div>
            </div>
        </header><!-- End .header -->