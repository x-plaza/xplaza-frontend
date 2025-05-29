<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>x-winkel</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('website_src/images/Asset 4.png')}}"/>

    @section('styles')
    @show

<!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//analytics.xplaza.shop/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->

</head>

<style type="text/css">
    .ui-autocomplete-row {
        padding: 8px;
        background-color: #f4f4f4;
        border-bottom: 1px solid #ccc;
        font-weight: bold;
    }

    .ui-autocomplete-row:hover {
        background-color: #ddd;
    }

    .dropbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropbtn:hover, .dropbtn:focus {
        background-color: #3e8e41;
    }

    #myInput {
        width: 100% !important;
        box-sizing: border-box;
        background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/Search_Icon.svg/1024px-Search_Icon.svg.png');
        background-position: 14px 12px;
        background-repeat: no-repeat;
        font-size: 14px;
        padding: 14px 20px 12px 45px;
        border: none;
        border-bottom: 1px solid #ddd;
        height: 46px;
    }
    #myInputMobile {
        width: 100% !important;
        box-sizing: border-box;
        background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/Search_Icon.svg/1024px-Search_Icon.svg.png');
        background-position: 14px 12px;
        background-repeat: no-repeat;
        font-size: 14px;
        padding: 14px 20px 12px 45px;
        border: none;
        border-bottom: 1px solid #ddd;
        height: 46px;
    }
    #myInputMobile:focus {outline: 3px solid #ddd;}
    #myInput:focus {outline: 3px solid #ddd;}

    .dropdown {
        margin-top: -49px;
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .dropdown-content {
        /*display: none;*/
        position: absolute;
        background-color: #f6f6f6;
        /*min-width: 230px;*/
        overflow: auto;
        border: 1px solid #ddd;
        z-index: 1;
        width: 100%;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown a:hover {background-color: #ddd;}

    .search_item_img{
        max-width: 100%;
        height: 50px;
        margin-right: 10px;
        border: 1px solid darkgrey;
        border-radius: 7px;
    }
    .show {display: block;}

</style>

<body id="top-page">
<?php
$searchableProductData = App\Libraries\HandleApi::searchProductData();
?>
<!--siteinfo Modal for Mobile View -->
<div class="modal fade" id="siteinfo1" tabindex="-1" aria-labelledby="siteinfo1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="header-top-action-dropdown">
                    <ul>
                        <li class="site-phone"><a href="https://wa.me/27846347530" target="_blank"><i class="fas fa-phone"></i> +27 84 634 7530 </a></li>
                        <li class="site-help"><a href="https://wa.me/27846347530" target="_blank"><i class="fas fa-question-circle"></i> Help & More</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- user Modal for mobile view-->
<div class="modal fade" id="useradmin1" tabindex="-1" aria-labelledby="useradmin1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="header-top-action-dropdown">
                    @php
                        $authUserId = Session::get( 'auth_user_id' );
                    @endphp
                    @if($authUserId != null)
                        <ul>
                            <li><a href="{{url('/my-dashboard')}}">Dashboard</a></li>
                            <li><a href="{{url('/sign-out')}}">Sign Out</a></li>
                        </ul>
                    @else
                        <ul>
                            <li class="signin-option"><a onclick="OpenSignUpForm()" href="#" data-dismiss="modal"><i class="fas fa-user mr-2"></i>Sign In</a></li>
                        </ul>
                    @endif
                    </div>
                </div>
            </div>
        </div>
</div>

@php
    $shopArray = Session::get( 'session_others_array' );
    $shopName = isset($shopArray['shop_name']) ? $shopArray['shop_name'] : null;
    $cityId = isset($shopArray['city_id']) ? $shopArray['city_id'] : null;
    $sessionShopId = isset($shopArray['shop_id']) ? $shopArray['shop_id'] : null;
    $sessionLocationId = isset($shopArray['location_id']) ? $shopArray['location_id'] : null;
@endphp
<input type="hidden" class="sessionShopId" value="{{$sessionShopId}}">
<input type="hidden" class="sessionLocationId" value="{{$sessionLocationId}}">
<!--shop Modal -->
<div class="modal fade" id="shop-modal-id" tabindex="-1" aria-labelledby="shop-select-id" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="select-search-option">
                    <div class="flux-custom-select2" style="margin-bottom: 15px;">
                        <select id="city-select-id" class="form-control select_city_dropdown_val">
                            <option value="">Select City</option>
                            @foreach($city_data as $city)
                                <option value="{{$city->id}}"
                                        @if($cityId == $city->id) Selected @endif>{{$city->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flux-custom-select2" style="margin-bottom: 15px;">
                        <select id="location-select-id"
                                class="form-control select_location_dropdown_val location_option">
                            <option value="">Select Location</option>
                            {{--                            @foreach($location_data as $location)--}}
                            {{--                                <option value="{{$location->id}}" @if($location->id == Session::get( 'selected_location_id' )) selected @endif>{{$location->name}}</option>--}}
                            {{--                            @endforeach--}}
                        </select>
                        <div class="location_loading"></div>
                    </div>
                    <div class="flux-custom-select2" style="margin-bottom: 15px;">
                        <select id="location-select-id" class="form-control select_shop_dropdown_val shop_option">
                            {{--                            <option value="">Select Shop</option>--}}
                            <option value="{{Session::get( 'selected_shop_id' )}}">{{isset($shopName) ? $shopName : 'Select Shop'}}</option>
                            {{--                            @foreach($shop_data as $shop)--}}
                            {{--                                <option value="{{$shop->id}}" @if($shop->id == Session::get( 'selected_shop_id' )) selected @endif>{{$shop->name}}</option>--}}
                            {{--                            @endforeach--}}
                        </select>
                        <div class="shop_loading"></div>
                    </div>
                    <div>
                        <ul class="site-action .d-block d-flex align-items-center justify-content-around  ml-auto">
                            <li class="signin-option ">
                                <button class="submit_shop_selection btn btn-success">Submit</button>
                            </li>
                            {{--                            <li class="signin-option"><a onclick="$('#shop-modal-id').modal('hide');"--}}
                            {{--                                                         href="#">Cancel</a></li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- menu modal -->

<!-- sidebar-cart -->
<div id="sitebar-cart" class="sitebar-cart sitebarItemData">

</div>

<!-- header section start -->
<header class="header">
    <div class="header-top">
        <!-- Mobile View -->
        <div class="mobile-header row align-items-center d-xl-none">
            <div class="logo-container col-8 col-sm-7">
                <a href="{{url('/')}}" class="logo"><img src="{{asset('website_src/images/logo.svg')}}" alt="logo"></a>
            </div>
            <div class="all-catagory-option col-4 col-sm-5 mobile-device">
                <a class="bar-btn"><i class="fas fa-bars"></i><span class="ml-2 d-none d-md-inline"></span></a>
                <a class="close-btn"><i class="fas fa-times"></i><span class="ml-2 d-none d-md-inline"></span></a>
            </div>
        </div>
        <!-- Desktop View -->
        <div class="d-none d-xl-flex row align-items-center">
            <div class="col-2 col-md-1 col-lg-5">
                <ul class="site-action d-none d-lg-flex justify-content-end align-items-center ml-auto">
                </ul>
            </div>
            <div class="col-5 col-md-2">
                <a href="{{url('/')}}" class="logo"><img src="{{asset('website_src/images/logo.svg')}}" alt="logo"></a>
            </div>
            <div class="col-5 col-md-9 col-lg-5">
                <ul class="site-action d-none d-lg-flex justify-content-start align-items-center topbar-info">
                    <li class="site-phone mr-4"><a href="https://wa.me/27846347530" target="_blank"><i class="fas fa-phone"></i> +27 84 634 7530</a></li>
                    <li class="site-help"><a href="#"><i class="fas fa-question-circle"></i> Help & More</a></li>
                </ul>
            </div>
        </div>
    </div>
    <hr>
    <div class="header-bottom">

    @php
        $shopArray = Session::get( 'session_others_array' );
        $shopName = isset($shopArray['shop_name']) ? $shopArray['shop_name'] : null;
    @endphp
    <!-- Mobile View -->
        @if(Request::is('home/*') || Request::is('/') || Request::is('website/item-details/*') || Request::is('product-by-category/sub/*') || Request::is('all-trending-products'))
            <div class="mobile-header row align-items-center d-xl-none">
                <div class="banner-search">
                    <div class="select-search-option d-md-flex">
                        <button id="btn-shop-id" type="button" data-toggle="modal" data-target="#shop-modal-id"
                                class="flux-custom-select select_shop_btn_name">{{isset($shopName) ? substr($shopName, 0, 8).'..' : 'Select Shop'}}
                            <i class="fas fa-angle-right"></i>
                        </button>
                        <!-- <div class="flux-custom-select">
                            <select>
                                <option value="0">Select Shop</option>
                            </select>
                        </div> -->
{{--                        <form action="#" class="search-form">--}}
{{--                            <input type="text" name="search" placeholder="Search Products...">--}}
{{--                            <button class="submit-btn"><i class="fas fa-search"></i></button>--}}
{{--                        </form>--}}
{{--                        <select class="search_product_section form-control" id="item_selector" onchange="getProductVal(this);">--}}
{{--                            @foreach($searchableProductData as $product)--}}
{{--                                <option value="{{$product['id']}}"> {{$product['name']}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
                        <div class="dropdown">
                            <div id="myDropdownMobile" class="dropdown-content">
                                <input type="text" placeholder="Search product.." id="myInputMobile" onkeyup="filterFunctionMobile()">
                                <div style="max-height: 250px; overflow-y: auto">
                                    @foreach($searchableProductData as $product)
                                        @if($product['quantity'] > 0)
                                            <a href="/website/item-details/{{$product['id']}}" class="searchable_item" style="display: none;">
                                                <img src="{{$product['img_url']}}" class="search_item_img">{{$product['name']}}
                                            </a>
                                        @else
                                            <a href="#" class="searchable_item" style="display: none;background-color: #efb7b7">
                                                <img src="{{$product['img_url']}}" class="search_item_img">{{$product['name']}} <b style="color: darkred">Stock out</b>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif

    <!-- Desktop View -->
        <div class="d-none d-xl-flex row align-items-center">
            <div class="col-md-2 p-0 d-none d-xl-block">
                <div class="all-catagory-option">
                    <a class="bar-btn"><i class="fas fa-bars"></i><span class="ml-2 d-none d-md-inline">All
                                categories</span></a>
                    <a class="close-btn"><i class="fas fa-times"></i><span class="ml-2 d-none d-md-inline">All
                                categories</span></a>
                </div>
            </div>
            <div class="col-md-8">
                @if(Request::is('home/*') || Request::is('/') || Request::is('website/item-details/*') || Request::is('product-by-category/sub/*') || Request::is('all-trending-products'))
                    <div class="banner-search">
                        <div class="select-search-option d-md-flex">
                            <button id="btn-shop-id" type="button" data-toggle="modal" data-target="#shop-modal-id"
                                    class="flux-custom-select select_shop_btn_name">{{isset($shopName) ? substr($shopName, 0, 8).'..' : 'Select Shop'}}
                                <i class="fas fa-angle-right"></i>
                            </button>
                            <!-- <div class="flux-custom-select">
                                <select id="myselect">
                                    <option value="">Select Shop</option>
                                    <option value="changeshop">Change Shop</option>
                                </select>
                            </div> -->
{{--                                                        <form action="#" class="search-form">--}}
{{--                                                            <input type="text" name="search" id="search_data" placeholder="Search Products.....">--}}
{{--                                                            <button class="submit-btn"><i class="fas fa-search"></i></button>--}}
{{--                                                        </form>--}}
{{--                            <select class="search_product_section form-control" id="item_selector" onchange="getProductVal(this);">--}}
{{--                                @foreach(App\Libraries\HandleApi::searchProductData() as $product)--}}
{{--                                    <option value="{{$product['id']}}"> {{$product['name']}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                            <div class="dropdown">
                                <div id="myDropdown" class="dropdown-content">
                                    <input type="text" placeholder="Search product.." id="myInput" onkeyup="filterFunction()">
                                    <div style="max-height: 250px; overflow-y: auto">
                                        @foreach($searchableProductData as $product)
                                            @if($product['quantity'] > 0)
                                            <a href="/website/item-details/{{$product['id']}}" class="searchable_item" style="display: none;">
{{--                                                <img src="{{$product['img_url']}}" class="search_item_img">{{$product['name']}}--}}
                                                {{$product['name']}}
                                            </a>
                                            @else
                                                <a href="#" class="searchable_item" style="display: none;background-color: #efb7b7">
{{--                                                    <img src="{{$product['img_url']}}" class="search_item_img">{{$product['name']}} <b style="color: darkred">Stock out</b>--}}
                                                    {{$product['name']}} <b style="color: darkred">Stock out</b>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-md-2 p-0 d-none d-xl-block">
                <div class="menu-area d-flex justify-content-end">
                    @php
                        $totalPrice = 0;
                        $allItem = Session::get( 'cart_item_array' );
                        $authUserId = Session::get( 'auth_user_id' );
                        $itemFromSession = isset($allItem) ? $allItem : [];
                    @endphp
                    @foreach($itemFromSession as $item)
                        @php
                            $totalPrice += $item['item_unit_price']*$item['quantity'];
                        @endphp
                    @endforeach
                    <ul class="menu-action d-none d-lg-block">
                        @if(Request::is('home/*') || Request::is('/') || Request::is('all-trending-products') || Request::is('website/item-details/*') || Request::is('product-by-category/sub/*'))
                            <li class="cart-option mr-4"><span class="cart-icon open_cart_from_topbar"
                                                               style="cursor: pointer"><i
                                            class="fas fa-shopping-cart"></i><span class="count cart_item_counter">
                                        {{count($itemFromSession)}}
                                    </span></span> <span
                                        class="cart-amount"></span>
                            </li>
                        @endif
                    </ul>
                    @if($authUserId != null)
                        <li class="my-account d-none d-lg-block"><a class="dropdown-toggle" href="#" role="button"
                                                                    id="myaccount"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"><i
                                        class="fas fa-user mr-1"></i> My Account</a>
                            <ul class="submenu dropdown-menu" aria-labelledby="myaccount">
                                <li><a href="{{url('/my-dashboard')}}">Dashboard</a></li>
                                <li><a href="{{url('/sign-out')}}">Sign Out</a></li>
                            </ul>
                        </li>
                    @else
                        <ul class="menu-action d-none d-lg-block">
                            <li class="signin-option"><a onclick="OpenSignUpForm()" href="#" data-dismiss="modal"><i
                                            class="fas fa-user mr-2"></i>Sign In</a></li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header section end -->

<div class="page-layout">
    <div class="catagory-sidebar-area">
        <div class="catagory-sidebar-area-inner">
            <div class="catagory-sidebar all-catagory-option">
                <ul class="catagory-submenu">

                    @foreach($category_data as $key=>$category)
                        {{--                        @if($category->parent_category_id == 0)--}}
                        {{--                            <li><a data-toggle="collapse" href="#catagory-widget{{$key+1}}" role="button" aria-expanded="false"--}}
                        {{--                                   aria-controls="catagory-widget{{$key+1}}">--}}
                        {{--                                    <div class="d-flex align-items-center"><span class="icon"><img--}}
                        {{--                                                    src="{{asset('website_src/images/svg/vegetables.svg')}}" alt="icon"></span>{{$category->name}}</div><i--}}
                        {{--                                            class="fas fa-angle-down"></i>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endif--}}
                        {{--                        @if($category->parent_category_id != 0)--}}
                        {{--                            <li><a data-toggle="collapse" href="#catagory-widget{{$key+1}}" role="button" aria-expanded="false"--}}
                        {{--                                   aria-controls="catagory-widget{{$key+1}}">--}}
                        {{--                                    <div class="d-flex align-items-center"><span class="icon"><img--}}
                        {{--                                                    src="{{asset('website_src/images/svg/vegetables.svg')}}" alt="icon"></span>{{App\Libraries\HandleApi::getParentCatName($category->parent_category_id)}}</div><i--}}
                        {{--                                            class="fas fa-angle-down"></i>--}}
                        {{--                                </a>--}}
                        {{--                                <ul class="catagory-submenu collapse show" id="catagory-widget{{$key+1}}">--}}
                        {{--                                    @foreach($cubCat as $data)--}}
                        {{--                                        @if($data['parent'] == $category->parent_category_id)--}}
                        {{--                                            <li><a href="#">{{$data['name']}}</a></li>--}}
                        {{--                                        @endif--}}
                        {{--                                    @endforeach--}}
                        {{--                                </ul>--}}
                        {{--                            </li>--}}
                        {{--                        @endif--}}

                        @if($category->parent_category_id == 0)
                            <li>
                                @if(count(App\Libraries\HandleApi::getSubCat($category->id)) > 0)
                                    <a data-toggle="collapse" href="#catagory-widget{{$key+1}}" role="button"
                                       aria-expanded="false"
                                       aria-controls="catagory-widget{{$key+1}}">
                                        <div class="d-flex align-items-center">
                                            {{--                                        <span class="icon"><img src="{{asset('website_src/images/svg/vegetables.svg')}}" alt="icon"></span>--}}
                                            {{$category->name}}
                                        </div>
                                        <i class="fas fa-angle-down"></i>
                                    </a>
                                @else
                                    <div class="d-flex align-items-center">
                                        {{--                                        <span class="icon"><img src="{{asset('website_src/images/svg/vegetables.svg')}}" alt="icon"></span>--}}
                                        <a href="{{url('/product-by-category/sub/'.$category->id)}}">{{$category->name}}</a>
                                    </div>
                                @endif
                                @if(count(App\Libraries\HandleApi::getSubCat($category->id)) > 0)
                                    <ul class="catagory-submenu collapse show" id="catagory-widget{{$key+1}}">
                                        @foreach(App\Libraries\HandleApi::getSubCat($category->id) as $data)
                                            <li>
                                                <a href="{{url('/product-by-category/sub/'.$data['id'])}}">{{$data['name']}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content-area">

    @section('content')
    @show

    <!-- footer section -->
        <footer class="footer">
            <div class="container">
                <div class="footer-top">
                    <div class="row">
                        <div class="col-md-3 col-lg-3">
                            <div class="footer-widget">
                                <a href="{{url('/')}}" class="footer-logo"><img
                                            src="{{asset('website_src/images/logo.svg')}}"
                                            alt="logo"></a>
                                <p>X-Plaza.shop is an online supermarket available in Jeffreys Bay, Aston Bay, Paradise Beach and St.Francis Bay. We believe time is valuable to our fellow residents and they should not waste hours in traffic, bad weather and wait in line just to buy basic necessities! This is why "X-winkel" delivers everything you need right at your door-step.</p>
                                <ul class="social-media-list d-flex flex-wrap">
                                    <li><a href="https://www.facebook.com/winkelx"><i class="fab fa-facebook-f"></i></a>
                                    <li><a href="https://wa.me/27846347530" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                    </li>
                                    {{--                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>--}}
                                    {{--                                    <li><a href="#"><i class="fab fa-vimeo-v"></i></a></li>--}}
                                    {{--                                    <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>--}}
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-auto col-lg-2">
                        </div>

                        <div class="col-md-6 col-lg-3">
                            {{--                            <div class="footer-widget">--}}
                            {{--                                <h5 class="footer-title">Useful Links</h5>--}}
                            {{--                                <div class="widget-wrapper">--}}
                            {{--                                    <ul>--}}
                            {{--                                        <li><a href="#">About Us</a></li>--}}
                            {{--                                        <li><a href="#">Featured Products</a></li>--}}
                            {{--                                        <li><a href="#">Offers</a></li>--}}
                            {{--                                        <li><a href="#">Blog</a></li>--}}
                            {{--                                        <li><a href="#">Faq</a></li>--}}
                            {{--                                        <li><a href="#">Careers</a></li>--}}
                            {{--                                        <li><a href="#">Contact Us</a></li>--}}
                            {{--                                    </ul>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>

                        <div class="col-md-auto col-lg-4">
                            {{--                            <div class="footer-widget">--}}
                            {{--                                <h5 class="footer-title">We accepts</h5>--}}
                            {{--                                <div class="widget-wrapper">--}}
                            {{--                                    <div class="apps-store">--}}
                            {{--                                        <a href=""><img src="{{asset('website_src/images/app-store/apple.png')}}" alt="app"></a>--}}
                            {{--                                        <a href=""><img src="{{asset('website_src/images/app-store/google.png')}}" alt="app"></a>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="payment-method d-flex flex-wrap">--}}
                            {{--                                        <a href="#"><img src="{{asset('website_src/images/payment/visa.png')}}" alt="payment"></a>--}}
                            {{--                                        <a href="#"><img src="{{asset('website_src/images/payment/paypal.png')}}" alt="payment"></a>--}}
                            {{--                                        <a href="#"><img src="{{asset('website_src/images/payment/master.png')}}" alt="payment"></a>--}}
                            {{--                                        <a href="#"><img src="{{asset('website_src/images/payment/discover.png')}}" alt="payment"></a>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="footer-widget">
                                <h5 class="footer-title">Useful Links</h5>
                                <div class="widget-wrapper">
                                    <ul>
                                        <li><a href="#">About Us</a></li>
                                        <li><a href="#">Featured Products</a></li>
                                        <li><a href="#">Offers</a></li>
                                        <li><a href="#">Blog</a></li>
                                        <li><a href="#">Faq</a></li>
                                        <li><a href="#">Careers</a></li>
                                        <li><a href="#">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <p class="copyright">Copyright &copy; 2021 <a href="">X-Winkel</a>. All Rights Reserved.
                            </p>
                        </div>

                        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                            <ul class="footer-menu d-flex flex-wrap">
                                <li><a href="#">Privacy policies</a></li>
                                <li><a href="#">Coockies</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer section -->
    </div>


</div>

<!-- login-area -->
<section id="login-area" class="login-area">
    <div onclick="CloseSignUpForm()" class="overlay"></div>
    <div class="login-body-wrapper">
        <div class="login-body">
            <div class="close-icon" onclick="CloseSignUpForm()">
                <i class="fas fa-times"></i>
            </div>
            <div class="login_content_section">
                <div class="login-header">
                    <h4>Login Your Account</h4>
                </div>
                <div class="login-content">
                    <form action="#" class="login-form">
                        <input type="email" name="email" class="login_email" placeholder="Email">
                        <input type="password" name="password" class="login_password" placeholder="Password">
                        <div class="login_message_section"></div>
                        <button type="button" class="submit login_up_button">Login</button>
                    </form>
                    {{--                <div class="text-center seperator">--}}
                    {{--                    <span>Or</span>--}}
                    {{--                </div>--}}
                    {{--                <div class="othersignup-option">--}}
                    {{--                    <a class="facebook" href="#"><i class="fab fa-facebook-square"></i>Continue with Facebook</a>--}}
                    {{--                    <a class="google" href="#"><i class="fab fa-google-plus"></i>Continue with Google</a>--}}
                    {{--                </div>--}}

                    <div class="text-center dont-account py-4">
                        <p class="mb-0">Don't have any account ? <a href="#" class="load_registration_form">Sign Up</a>
                        </p>
                        <p class="mb-0">Forgot password ? <a href="#" class="forgot_pass_form">Reset password</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="registration_content_section" style="display: none;">
                <div class="login-header">
                    <h4>Register an account</h4>
                </div>
                <div class="login-content">
                    <form action="#" class="login-form">
                        <label>Enter first name</label>
                        <input type="text" name="f_name" class="reg_f_name" placeholder="First name">
                        <label>Enter last name</label>
                        <input type="text" name="l_name" class="reg_l_name" placeholder="Last name">
{{--                        <label>Enter mobile</label>--}}
{{--                        <input type="text" name="mobile"  class="reg_mobile" placeholder="Mobile">--}}
{{--                        <label>Enter country name</label>--}}
{{--                        <input type="text" name="country"  class="reg_country" placeholder="Country">--}}
{{--                        <label>Enter area name</label>--}}
{{--                        <input type="text" name="area"  class="reg_area" placeholder="Area">--}}
{{--                        <label>Enter city</label>--}}
{{--                        <input type="text" name="city"  class="reg_city" placeholder="City">--}}
{{--                        <label>Enter state</label>--}}
{{--                        <input type="text" name="street_name"  class="reg_street_name" placeholder="Street name">--}}
{{--                        <label>Enter house</label>--}}
{{--                        <input type="text" name="house_no"  class="reg_house_no" placeholder="House No">--}}
{{--                        <label>Enter post code</label>--}}
{{--                        <input type="text" name="post_code"  class="reg_post_code" placeholder="Post code">--}}
{{--                        <label>Enter date of birth</label>--}}
{{--                        <input type="date" name="dob" class="reg_dob dob_val" placeholder="Date of birth">--}}
                        <label>Enter valid email</label>
                        <input type="email" name="email" class="reg_email" placeholder="Email" style="width: 75%;">
                        <span><button class="btn btn-primary btn-md otp_sending_btn"
                                      style="padding-top: 4%;padding-bottom: 3%;">Get OTP </button></span>
                        <div class="reg_otp_message_section"></div>
                        <label>Enter OTP</label>
                        <input type="text" name="otp" class="reg_otp" placeholder="OTP">
                        <label>Enter password</label>
                        <input type="password" name="password" class="reg_password" placeholder="Password">
                        <label>Enter confirm password</label>
                        <input type="password" name="password" class="reg_conf_password" placeholder="Confirm Password">

                        <button type="button" class="submit sign_up_button">Sign Up</button>
                    </form>
                    <div class="reg_message_section"></div>
                    <div class="text-center dont-account py-4">
                        <p class="mb-0">Already have any account ? <a href="#" class="load_login_form">Sign In</a></p>
                    </div>
                </div>
            </div>
            <div class="forgot_pass_content_section" style="display: none;">
                <div class="login-header">
                    <h4>Password reset</h4>
                </div>
                <div class="login-content">
                    <form action="#" class="login-form">
                        <label>Enter valid email</label>
                        <input type="email" name="email" class="reset_email" placeholder="Email" style="width: 75%;">
                        <span><button class="btn btn-primary btn-md otp_sending_btn_forgot_pass"
                                      style="padding-top: 4%;padding-bottom: 3%;">Get OTP </button></span>
                        <div class="reset_otp_message_section"></div>
                        <label>Enter OTP</label>
                        <input type="text" name="otp" class="reset_otp" placeholder="OTP">
                        <label>Enter password</label>
                        <input type="password" name="password" class="reset_password" placeholder="Password">
                        <label>Enter confirm password</label>
                        <input type="password" name="password" class="reset_conf_password" placeholder="Confirm Password">

                        <div class="forgot_pass_message_section"></div>
                        <button type="button" class="submit reset_pass_button">Reset</button>
                    </form>
                    <div class="reg_message_section"></div>
                    <div class="text-center dont-account py-4">
                        <p class="mb-0">Already have any account ? <a href="#" class="load_login_form">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
        {{--        <div class="forgot-password text-center">--}}
        {{--            <p>forgot Passowrd? <a href="#">Reset It</a></p>--}}
        {{--        </div>--}}
    </div>
</section>
<!-- login-area -->

<!-- mobile-footer -->
<div class="mobile-footer d-flex justify-content-between align-items-center d-xl-none">
    <button class="info" type="button" data-toggle="modal" data-target="#siteinfo1"><i
                class="fas fa-info-circle"></i></button>

    <div class="footer-cart">
        <a href="#" class="d-flex align-items-center open_cart_from_topbar"><span class="cart-icon"><i
                        class="fas fa-shopping-cart"></i><span
                        class="count cart_item_counter"> {{count($itemFromSession)}}</span></span> <span
                    class="cart-amount ml-2"></span></a>
    </div>

    <div class="footer-admin-area">
        <!-- <span class="user-admin">
            <i class="fas fa-user"></i>
        </span> -->
        <button class="user-admin" type="button" data-toggle="modal" data-target="#useradmin1"><i
                    class="fas fa-user"></i></button>
    </div>
</div>
<!-- mobile-footer -->

<input type="hidden" class="selected_shop_id" value="{{Session::get( 'selected_shop_id' )}}">
<a href="#top-page" class="to-top js-scroll-trigger"><span><i class="fas fa-arrow-up"></i></span></a>

@if( Request::is('all-trending-products'))
<script>
    document.title = 'all trending products';
</script>
@endif

@if( Request::is('checkout'))
    <script>
        document.title = 'checkout';
    </script>
@endif

@section('scripts')
@show
<!-- <script>
   $(document).ready(function () { //Make script DOM ready
       $('#myselect').change(function () { //jQuery Change Function
           var opval = $(this).val(); //Get value from select element
           if (opval == "changeshop") { //Compare it and if true
               $('#shopselectid').modal("show"); //Open Modal
           }
       });
   });
</script> -->

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />--}}

{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

<link rel="stylesheet" href="{{ asset('website_src/select2/select2.min.css') }}">
<script src="{{asset('website_src/select2/select2.min.js')}}"></script>
<script>

    function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");

        if(input.value == ''){
            document.querySelectorAll('.searchable_item').forEach(function(el) {
                el.style.display = 'none';
            });
            return false;
        }

        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }

    function filterFunctionMobile() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInputMobile");

        if(input.value == ''){
            document.querySelectorAll('.searchable_item').forEach(function(el) {
                el.style.display = 'none';
            });
            return false;
        }

        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdownMobile");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }

   $(document).on('keyup', '#search_data', function () {
       var search_data = $('#search_data').val();
       if (search_data != '') {
           $('.product_search_web').html('<i class="fa fa-spinner fa-spin"></i> Loading ....');
       }
   })

   $(document).ready(function () {
       $(".search_product_section").select2();
   });

   {{--$(document).on('change', '.search_product_section', function () {--}}
   {{--    var productId = $('#item_selector option:selected').val();--}}
   {{--  //  var productId = $('#item_selector :selected').val();--}}
   {{--    alert(productId);return false;--}}
   {{--    if (productId != -99999) {--}}
   {{--        location.href = "{{url('website/item-details')}}" + '/' + parseInt(productId);--}}
   {{--    }--}}
   {{--})--}}

   function getProductVal(sel)
   {
       var productId = sel.value;
      // alert(productId);return false;
       if (productId != -99999) {
           location.href = "{{url('website/item-details')}}" + '/' + parseInt(productId);
       }
   }

   {{--$(document).ready(function(){--}}

   {{--    $('#search_data').autocomplete({--}}
   {{--        source: '{{ url('/website/product-search-data') }}',--}}
   {{--        minLength: 1,--}}
   {{--        select: function(event, ui)--}}
   {{--        {--}}
   {{--            $('#search_data').val(ui.item.value);--}}
   {{--        }--}}
   {{--    }).data('ui-autocomplete')._renderItem = function(ul, item){--}}
   {{--        $('.product_search_web').html('');--}}
   {{--        return $("<li class='ui-autocomplete-row'></li>")--}}
   {{--            .data("item.autocomplete", item)--}}
   {{--            .append(item.label)--}}
   {{--            .appendTo(ul);--}}
   {{--    };--}}
   {{--});--}}

   var selected_shop = $('.selected_shop_id').val();
   if (selected_shop == '') {
       $('#shop-modal-id').modal({
           backdrop: 'static',
           keyboard: false
       });
       $('#shop-modal-id').modal('show');
   }
   $(document).on('click', '.select_shop_btn_name', function () {
       $('.select_city_dropdown_val').trigger('change');
   })

   $(document).on('click', '.load_registration_form', function () {
       $('.registration_content_section').css('display', 'block');
       $('.login_content_section').css('display', 'none');
       $('.forgot_pass_content_section').css('display', 'none');
   })
   $(document).on('click', '.load_login_form', function () {
       $('.registration_content_section').css('display', 'none');
       $('.login_content_section').css('display', 'block');
       $('.forgot_pass_content_section').css('display', 'none');
   })
    $(document).on('click', '.forgot_pass_form', function () {
        $('.registration_content_section').css('display', 'none');
        $('.login_content_section').css('display', 'none');
        $('.forgot_pass_content_section').css('display', 'block');
    })

   $(document).on('click', '.otp_sending_btn', function () {
       var reg_email = $('.reg_email').val();
       $('.reg_otp_message_section').html('');
       if (reg_email == '') {
           alert('Please enter valid email');
           return false;
       }
       var btn = jQuery(this);
       var btn_content = btn.html();
       btn.html('<i class="fa fa-spinner fa-spin"></i>');
       btn.prop('disabled', true);

       $.ajax({
           url: '{{ url('/website/get-reg-otp') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {
               reg_email: reg_email
           },
           success: function (response) {
               btn.html(btn_content);
               btn.prop('disabled', false);
               if (response.responseCode == 1) {
                   $('.reg_otp_message_section').html('<span style="color: #0d3625">Successfully sent otp. Please check email</span>');
               } else if (response.responseCode == 2) {
                   $('.reg_otp_message_section').html('<span style="color: #0d3625">Not valid email</span>');
               } else {
                   $('.reg_otp_message_section').html('<span style="color: red">Could not sent otp. Please try again</span>');
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {
               location.reload();

           }
       });
   })

   $(document).on('click', '.otp_sending_btn_forgot_pass', function () {
       var reset_email = $('.reset_email').val();
       $('.reset_otp_message_section').html('');
       if (reset_email == '') {
           alert('Please enter valid email');
           return false;
       }
       var btn = jQuery(this);
       var btn_content = btn.html();
       btn.html('<i class="fa fa-spinner fa-spin"></i>');
       btn.prop('disabled', true);

       $.ajax({
           url: '{{ url('/website/get-reg-otp') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {
               reg_email: reset_email
           },
           success: function (response) {
               btn.html(btn_content);
               btn.prop('disabled', false);
               if (response.responseCode == 1) {
                   $('.reset_otp_message_section').html('<span style="color: #0d3625">Successfully sent otp. Please check email</span>');
               } else if (response.responseCode == 2) {
                   $('.reset_otp_message_section').html('<span style="color: #0d3625">Not valid email</span>');
               } else {
                   $('.reset_otp_message_section').html('<span style="color: red">Could not sent otp. Please try again</span>');
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {
             //  location.reload();

           }
       });
   })

    $(document).on('click', '.sign_up_button', function () {

        // var reg_city = $('.reg_city').val();
        // var reg_country = $('.reg_country').val();
        // var reg_area = $('.reg_area').val();
        // var reg_street_name = $('.reg_street_name').val();
        // var reg_dob = $('.reg_dob').val();
        var reg_f_name = $('.reg_f_name').val();
        var reg_l_name = $('.reg_l_name').val();
        // var reg_house_no = $('.reg_house_no').val();
        // var reg_mobile = $('.reg_mobile').val();
        // var reg_post_code = $('.reg_post_code').val();
        var reg_email = $('.reg_email').val();
        var reg_password = $('.reg_password').val();
        var reg_conf_password = $('.reg_conf_password').val();
        var reg_otp = $('.reg_otp').val();

        // if (reg_country == '') {
        //     alert('Please enter country');
        //     return false;
        // }
        // if (reg_city == '') {
        //     alert('Please enter city');
        //     return false;
        // }
        // if (reg_area == '') {
        //     alert('Please enter area');
        //     return false;
        // }
        // if (reg_street_name == '') {
        //     alert('Please enter street name');
        //     return false;
        // }
        // if (reg_dob == '') {
        //     alert('Please enter date of birth');
        //     return false;
        // }
        if (reg_f_name == '') {
            alert('Please enter first name');
            return false;
        }
        if (reg_l_name == '') {
            alert('Please enter last name');
            return false;
        }
        // if (reg_house_no == '') {
        //     alert('Please enter house');
        //     return false;
        // }
        // if (reg_mobile == '') {
        //     alert('Please enter email');
        //     return false;
        // }
        // if (reg_post_code == '') {
        //     alert('Please enter post code');
        //     return false;
        // }
        if (reg_password == '') {
            alert('Please enter password');
            return false;
        }
        if (reg_conf_password == '') {
            alert('Please enter confirm password');
            return false;
        }
        if (reg_otp == '') {
            alert('Please enter valid otp');
            return false;
        }
        if (reg_email == '') {
            alert('Please enter valid email');
            return false;
        }

        $('.reg_message_section').html('');
        var btn = jQuery(this);
        var btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);

        $.ajax({
            url: '{{ url('/website/init-registration') }}',
            type: "POST",
            //dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                // reg_country: reg_country,
                // reg_city: reg_city,
                // reg_area: reg_area,
                // reg_street_name: reg_street_name,
                // reg_dob: reg_dob,
                reg_f_name: reg_f_name,
                reg_l_name: reg_l_name,
                // reg_house_no: reg_house_no,
                // reg_mobile: reg_mobile,
                // reg_post_code: reg_post_code,
                reg_password: reg_password,
                reg_conf_password: reg_conf_password,
                reg_otp: reg_otp,
                reg_email: reg_email
            },
            success: function (response) {
                btn.html(btn_content);
                btn.prop('disabled', false);
                if (response.responseCode == 1) {
                    $('.reg_message_section').html('<span style="color: #0d3625;font-weight: bold;">Successfully registered</span>');
                    location.reload();
                } else {
                    $('.reg_message_section').html('<span style="color: red;font-weight: bold;">' + response.message + '</span>');
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                location.reload();

            }
        });
    })

   $(document).on('click', '.reset_pass_button', function () {

       var reset_email = $('.reset_email').val();
       var reset_password = $('.reset_password').val();
       var reset_conf_password = $('.reset_conf_password').val();
       var reset_otp = $('.reset_otp').val();

       if (reset_password == '') {
           alert('Please enter password');
           return false;
       }
       if (reset_conf_password == '') {
           alert('Please enter confirm password');
           return false;
       }
       if (reset_otp == '') {
           alert('Please enter valid otp');
           return false;
       }
       if (reset_email == '') {
           alert('Please enter valid email');
           return false;
       }

       $('.reset_message_section').html('');
       var btn = jQuery(this);
       var btn_content = btn.html();
       btn.html('<i class="fa fa-spinner fa-spin"></i>');
       btn.prop('disabled', true);

       $.ajax({
           url: '{{ url('/website/init-forgot-pass') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {
               reset_password: reset_password,
               reset_conf_password: reset_conf_password,
               reset_otp: reset_otp,
               reset_email: reset_email
           },
           success: function (response) {
               btn.html(btn_content);
               btn.prop('disabled', false);

               $('.reset_email').val('');
               $('.reset_password').val('');
               $('.reset_conf_password').val('');
               $('.reset_otp').val('');

               if (response.responseCode == 1) {
                   $('.forgot_pass_message_section').html('<span style="color: #0d3625;font-weight: bold;">' + response.message + '</span>');
                  // location.reload();
               } else {
                   $('.forgot_pass_message_section').html('<span style="color: red;font-weight: bold;">' + response.message + '</span>');
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {
             //  location.reload();

           }
       });
   })

   $(document).on('click', '.login_up_button', function () {

       var login_email = $('.login_email').val();
       var login_password = $('.login_password').val();

       if (login_email == '') {
           alert('Please enter email');
           return false;
       }
       if (login_password == '') {
           alert('Please enter password');
           return false;
       }

       $('.login_message_section').html('');
       var btn = jQuery(this);
       var btn_content = btn.html();
       btn.html('<i class="fa fa-spinner fa-spin"></i>');
       btn.prop('disabled', true);

       $.ajax({
           url: '{{ url('/website/init-login') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {
               login_email: login_email,
               login_password: login_password
           },
           success: function (response) {
               btn.html(btn_content);
               btn.prop('disabled', false);
               if (response.responseCode == 1) {
                   $('.login_message_section').html('<span style="color: #0d3625;font-weight: bold;">Successfully logged in</span>');
                   location.reload();
               } else {
                   $('.login_message_section').html('<span style="color: red;font-weight: bold;">' + response.message + '</span>');
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {
               location.reload();

           }
       });
   })


   $(document).on('click', '.product_modal_open_button', function () {
       var itemcode = jQuery(this).data('itemcode');
       var itemimage = jQuery(this).data('itemimage');
       var itemname = jQuery(this).data('itemname');
       var itemdetails = jQuery(this).data('itemdetails');
       var itemquantity = jQuery(this).data('itemquantity');
       var itemprice = jQuery(this).data('itemprice');

       $('.product_title_in_modal').html(itemname);
       $('.product_details_in_modal').html(itemdetails);
       $('.product_image_in_modal').attr("src", itemimage);
       $('.product_quantiry_in_modal').html(itemquantity);
       $('.product_price_in_modal').html(itemprice);
       //  document.getElementById("sitebar-cart").classList.add('open-cart');

       $('.modal_add_to_cart_btn').attr('data-itemcode', itemcode);
       $('.modal_add_to_cart_btn').attr('data-itemimage', itemimage);

       document.getElementById("sitebar-drawar").classList.add('hide-drawer');

       // alert(itemimage);
   })

   $(document).on('click', '.add_to_cart_button_by_id', function () {
       var itemcode = jQuery(this).data('itemcode');
       var itemimage = jQuery(this).data('itemimage');
       var addedQuantity = 1;

       var btn = jQuery(this);
       var btn_content = btn.html();
       btn.html('<i class="fa fa-spinner fa-spin"></i>');
       btn.prop('disabled', true);

       $.ajax({
           url: '{{ url('/website/item-add-to-cart-by-id') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {
               itemcode: itemcode,
               itemimage: itemimage,
               addedQuantity: addedQuantity
           },
           success: function (response) {
               btn.html(btn_content);
               btn.prop('disabled', false);
               if (response.responseCode == 1) {
                   document.getElementById("sitebar-cart").classList.add('open-cart');
                   //  document.getElementById("sitebar-drawar").classList.add('hide-drawer');
                   // $(".open_cart_from_topbar").trigger('click');
                   $('.sitebarItemData').html(response.html);
                   itemCounter();
               } else {
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {

           }
       });
   })

   $(document).on('click', '.add_to_cart_button', function () {
       var itemcode = jQuery(this).data('itemcode');
       var itemimage = jQuery(this).data('itemimage');
       var itemname = jQuery(this).data('itemname');
       var itemunit = jQuery(this).data('itemunit');
       var itemprice = jQuery(this).data('itemprice');
       var addedQuantity = 1;
       var itembrandid = jQuery(this).data('itembrandid');
       var itemcurrencyid = jQuery(this).data('itemcurrencyid');
       var itemcategoryid = jQuery(this).data('itemcategoryid');
       var itemcategoryname = jQuery(this).data('itemcategoryname');
       var itemvartypename = jQuery(this).data('itemvartypename');
       var itemvartypevalue = jQuery(this).data('itemvartypevalue');
       var itemquantitytype = jQuery(this).data('itemquantitytype');

       $.ajax({
           url: '{{ url('/website/item-add-to-cart') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {
               itemcode: itemcode,
               itemimage: itemimage,
               itemname: itemname,
               itemunit: itemunit,
               itemprice: itemprice,
               itembrandid: itembrandid,
               addedQuantity: addedQuantity,
               itemcurrencyid: itemcurrencyid,
               itemcategoryid: itemcategoryid,
               itemcategoryname: itemcategoryname,
               itemvartypename: itemvartypename,
               itemvartypevalue: itemvartypevalue,
               itemquantitytype: itemquantitytype
           },
           success: function (response) {

               if (response.responseCode == 1) {
                   document.getElementById("sitebar-cart").classList.add('open-cart');
                   //  document.getElementById("sitebar-drawar").classList.add('hide-drawer');
                   // $(".open_cart_from_topbar").trigger('click');
                   $('.sitebarItemData').html(response.html);
                   itemCounter();
               } else {
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {
               location.reload();

           }
       });
   })

   $(document).on('click', '.open_cart_from_topbar', function () {

       $.ajax({
           url: '{{ url('/website/open-cart-list') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {},
           success: function (response) {

               if (response.responseCode == 1) {
                   document.getElementById("sitebar-cart").classList.add('open-cart');
                   $('.sitebarItemData').html(response.html);
               } else {
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {
               location.reload();

           }
       });
   })

   $(document).on('click', '.remove_item_from_cart_sitebar', function () {
       var itemcode = jQuery(this).data('itemcode');

       $.ajax({
           url: '{{ url('/website/remove-item-from-cart-sitebar') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {
               itemcode: itemcode
           },
           success: function (response) {

               if (response.responseCode == 1) {
                   //  document.getElementById("sitebar-cart").classList.add('open-cart');
                   $('.sitebarItemData').html(response.html);
                   itemCounter();
               } else {
               }

           },
           error: function (jqXHR, textStatus, errorThrown) {
               location.reload();

           }
       })
   })

   function itemCounter() {
       $.ajax({
           url: '{{ url('/website/topber-item-counter') }}',
           type: "POST",
           //dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {},
           success: function (response) {
               if (response.responseCode == 1) {
                   //  document.getElementById("sitebar-cart").classList.add('open-cart');
                   $('.cart_item_counter').html(response.data);
               }
           }
       })
   }


   $(document).on('change', '.select_city_dropdown_val', function () {
       var sessionLocationId = $('.sessionLocationId').val();
       var city_id = jQuery(this).val();
       $('.location_loading').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading ......');

       $.ajax({
           url: '{{ url('/website/get-location-data') }}',
           type: "POST",
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           // dataType: "jsonp",
           data: {city_id: city_id},
           success: function (data) {
               $('.location_loading').html('');

               $('.location_option').empty();
               $('.shop_option').empty();
               $('.location_option').append($('<option>', {
                   value: '',
                   text: 'Select location'
               }));
               $.each(data.locations, function (i, d) {
                   $('.location_option').append($('<option>', {
                       value: d.id,
                       text: d.name
                   }));
               });
               if (sessionLocationId != ''){
                   $('.location_option').val(sessionLocationId).attr("selected", "selected");
                   $('.select_location_dropdown_val').trigger('change');
               }
           }
       });
   })

   $(document).on('change', '.select_location_dropdown_val', function () {
       var location_id = jQuery(this).val();
       var sessionShopId = $('.sessionShopId').val();
       $('.shop_loading').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading ......');
       $.ajax({
           url: '{{ url('/website/get-shop-data') }}',
           type: "POST",
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: {location_id: location_id},
           success: function (data) {
               $('.shop_loading').html('');
               $('.shop_option').empty();

               if (data.responseCode == 1) {
                   $.each(data.shops, function (i, d) {
                       $('.shop_option').append($('<option>', {
                           value: d.id,
                           text: d.name
                       }));
                   });
                   if (sessionShopId != ''){
                       $('.shop_option').val(sessionShopId).attr("selected", "selected");
                   }
               } else {
                   $('.shop_option').append($('<option>', {
                       value: '',
                       text: 'No shop'
                   }));
               }

           }
       });
   })
</script>

</body>

</html>
