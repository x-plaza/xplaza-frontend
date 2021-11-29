@extends('layouts.admin_layout')

@section('styles')
    @include('layouts.admin_common_css')

@endsection

@section('content')

    <div class="dashboard_loader" style="background-color: white;display: none;">
        <div class="canceled_list" style="text-align: center;">
            <img class="img-responsive" src="<?php echo e(asset('website_src/loading_img.gif')); ?>" >
        </div>
    </div>

    <!-- page-content -->
    <section class="page-content section-ptb-90 trending-product-section" style="display: none">

    </section>
    <!-- page-content -->

    <!-- product-details-popup start -->
    <section id="product-details-popup" class="product-details-popup">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="container">
            <div class="product-zoom-info-container">
                <div id="closed-modal" class="closed-modal" onclick="closeModal()">X</div>
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="product-zoom-area">
                            {{--                            <span class="batch">30%</span>--}}
                            <div class="cart-btn-toggle d-lg-none">
                                <span class="cart-btn"><i class="fas fa-shopping-cart"></i> Cart</span>

                                <div class="price-btn">
                                    <div class="price-increase-decrese-group d-flex">
                                        <span class="decrease-btn">
                                            <button type="button" class="btn quantity-left-minus" data-type="minus"
                                                    data-field="">-
                                            </button>
                                        </span>
                                        <input type="text" name="quantity" class="form-controls input-number" value="1">
                                        <span class="increase">
                                            <button type="button" class="btn quantity-right-plus" data-type="plus"
                                                    data-field="">+
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slick">
                                <div><img src="{{asset('website_src/images/product-detail/01.jpg')}}" alt=""
                                          class="img-fluid blur-up lazyload image_zoom_cls-0 product_image_in_modal"></div>
                                {{--                                <div><img src="{{asset('website_src/images/product-detail/02.jpg')}}" alt=""--}}
                                {{--                                          class="img-fluid blur-up lazyload image_zoom_cls-1"></div>--}}
                                {{--                                <div><img src="{{asset('website_src/images/product-detail/03.jpg')}}" alt=""--}}
                                {{--                                          class="img-fluid blur-up lazyload image_zoom_cls-2"></div>--}}
                                {{--                                <div><img src="{{asset('website_src/images/product-detail/01.jpg')}}" alt=""--}}
                                {{--                                          class="img-fluid blur-up lazyload image_zoom_cls-3"></div>--}}
                            </div>
                            {{--                            <div class="row">--}}
                            {{--                                <div class="col-12">--}}
                            {{--                                    <div class="slider-nav">--}}
                            {{--                                        <div><img src="{{asset('website_src/images/product-detail/01.jpg')}}" alt=""--}}
                            {{--                                                  class="img-fluid blur-up lazyload"></div>--}}
                            {{--                                        <div><img src="{{asset('website_src/images/product-detail/02.jpg')}}" alt=""--}}
                            {{--                                                  class="img-fluid blur-up lazyload"></div>--}}
                            {{--                                        <div><img src="{{asset('website_src/images/product-detail/01.jpg')}}" alt=""--}}
                            {{--                                                  class="img-fluid blur-up lazyload"></div>--}}
                            {{--                                        <div><img src="{{asset('website_src/images/product-detail/03.jpg')}}" alt=""--}}
                            {{--                                                  class="img-fluid blur-up lazyload"></div>--}}
                            {{--                                        <!-- <div><img src="assets/images/product-detail/02.jpg" alt=""--}}
                            {{--                                            class="img-fluid blur-up lazyload"></div> -->--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="product-details-content">
                            <a class="wish-link" href="#">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart"
                                     class="svg-inline--fa fa-heart fa-w-16" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 512 512">
                                    <path
                                            d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z">
                                    </path>
                                </svg>
                            </a>
                            <h2 class="product_title_in_modal">Product Title Here</h2>
                            <p class="quantity product_quantiry_in_modal"></p>
                            <h3 class="price product_price_in_modal"></h3>
                            {{--                            <div class="price-increase-decrese-group d-flex">--}}
                            {{--                                <span class="decrease-btn">--}}
                            {{--                                    <button type="button" class="btn quantity-left-minus" data-type="minus"--}}
                            {{--                                            data-field="">---}}
                            {{--                                    </button>--}}
                            {{--                                </span>--}}
                            {{--                                <input type="text" name="quantity" class="form-controls input-number" value="1">--}}
                            {{--                                <span class="increase">--}}
                            {{--                                    <button type="button" class="btn quantity-right-plus" data-type="plus"--}}
                            {{--                                            data-field="">+--}}
                            {{--                                    </button>--}}
                            {{--                                </span>--}}
                            {{--                            </div>--}}
                            <p class="product_details_in_modal"></p>
                            <div class="d-flex justify-content-end">
                                <a href="#" class="buy-now modal_add_to_cart_btn add_to_cart_button_by_id">Add To Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product-details-popup end -->
    <input type="hidden" class="product_cat_id" value="{{$cat_id}}">

@endsection

@section('scripts')

    @include('layouts.admin_common_js')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script language="javascript">

        $('.product-list>.col-sm-6').hide();
        $('.product-list>.col-sm-6').slice(0,12).show();
        $('.loadMore').on("click",function(){
            $('.product-list>.col-sm-6:hidden').slice(0,4).show();
            if($('.product-list>.col-sm-6:hidden').length ==0){
                $(this).hide();
            }
        })

        var selected_shop_id = $('.selected_shop_id').val();
        if (selected_shop_id != ''){
            loadTrendingProductList(selected_shop_id)
        }

        function loadTrendingProductList(shop_id){
            $('.dashboard_loader').css({"display":"block"});
            var product_cat_id = $('.product_cat_id').val();

            $.ajax({
                url: '{{ url('/website/get-product-list/by-sub-cat') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    shop_id: shop_id,
                    product_cat_id: product_cat_id
                },
                success: function (response) {

                    $('.dashboard_loader').css({"display":"none"});

                    if (response.responseCode == 1) {
                        $('.trending-product-section').css({"display":"block"});
                        $('.trending-product-section').html(response.html);
                    }
                    (function ($) {
                        // catagory-container swiper slider init
                        var catagoryContainer2 = new Swiper('.category-container2', {
                            slidesPerView: 6,
                            autoplay: {
                                delay: 2500,
                                disableOnInteraction: false,
                            },
                            loop: true,
                            navigation: {
                                nextEl: '.catagory-slider-next',
                                prevEl: '.catagory-slider-prev',
                            },
                            spaceBetween: 30,
                            breakpoints: {
                                1300: {
                                    slidesPerView: 4
                                },
                                768: {
                                    slidesPerView: 3
                                },
                                540: {
                                    slidesPerView: 2
                                },
                                400: {
                                    slidesPerView: 2
                                }
                            }
                        });

                        // catagory-container swiper slider init
                        var catagoryContainer = new Swiper('.catagory-container', {
                            slidesPerView: 6,
                            loop: true,
                            navigation: {
                                nextEl: '.catagory-slider-next',
                                prevEl: '.catagory-slider-prev',
                            },
                            spaceBetween: 30,
                            breakpoints: {
                                990: {
                                    slidesPerView: 4
                                },
                                768: {
                                    slidesPerView: 2
                                },
                                540: {
                                    slidesPerView: 2
                                },
                                400: {
                                    slidesPerView: 2
                                }
                            }
                        });

                        // trending-product-container swiper slider init
                        var trendingContainer = new Swiper('.trending-product-container', {
                            slidesPerView: 4,
                            loop: true,
                            navigation: {
                                nextEl: '.trending-slider-next',
                                prevEl: '.trending-slider-prev',
                            },
                            spaceBetween: 30,
                            breakpoints: {
                                1200: {
                                    slidesPerView: 3
                                },
                                990: {
                                    slidesPerView: 3
                                },
                                768: {
                                    slidesPerView: 2
                                },
                                540: {
                                    slidesPerView: 1
                                },
                                400: {
                                    slidesPerView: 1
                                }
                            }
                        });

                        // trending-product-container swiper slider init
                        var recommendContainer = new Swiper('.recommend-product-container', {
                            slidesPerView: 4,
                            loop: true,
                            navigation: {
                                nextEl: '.trending-slider-next',
                                prevEl: '.trending-slider-prev',
                            },
                            spaceBetween: 30,
                            breakpoints: {
                                1200: {
                                    slidesPerView: 3
                                },
                                990: {
                                    slidesPerView: 3
                                },
                                768: {
                                    slidesPerView: 2
                                },
                                540: {
                                    slidesPerView: 1
                                },
                                400: {
                                    slidesPerView: 1
                                }
                            }
                        });

                    })(jQuery);
                }
            });
        }

        $(document).on('click','.quantity-right-plus-btn',function () {
            var itemcode = jQuery(this).data('itemcode');

            $.ajax({
                url: '{{ url('/website/item-quantity-add-from-sitebar') }}',
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
                        $('.sitebarItemData').html(response.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        })


        $(document).on('click','.quantity-left-minus-btn',function () {
            var itemcode = jQuery(this).data('itemcode');

            $.ajax({
                url: '{{ url('/website/item-quantity-minus-from-sitebar') }}',
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
                        $('.sitebarItemData').html(response.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        })
    </script>
@endsection
