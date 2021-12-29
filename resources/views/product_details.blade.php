@extends('layouts.admin_layout')

@section('styles')
    @include('layouts.admin_common_css')

@endsection

@section('content')

    @if($has_product_data)

    <div class="main-content-area">
        <!-- product-zoom-info section start -->
        <section class="product-zoom-info-section section-ptb">
            <div class="container">
                <div class="product-zoom-info-container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="product-zoom-area">
{{--                                <span class="batch">30%</span>--}}
                                <div class="product-slick">
                                    <div><img src="{{$imagePath}}" alt="" width="500" height="500"
                                              class="img-fluid blur-up lazyload image_zoom_cls-0"></div>
                                </div>
{{--                                <div class="row">--}}
{{--                                    <div class="col-12">--}}
{{--                                        <div class="slider-nav">--}}
{{--                                            <div><img src="http://admin.codezonebd.com/item_image/211111104442.jpg" alt=""--}}
{{--                                                      class="img-fluid blur-up lazyload"></div>--}}
{{--                                            <div><img src="assets/images/product-detail/02.jpg" alt=""--}}
{{--                                                      class="img-fluid blur-up lazyload"></div>--}}
{{--                                            <div><img src="assets/images/product-detail/03.jpg" alt=""--}}
{{--                                                      class="img-fluid blur-up lazyload"></div>--}}
{{--                                            <div><img src="assets/images/product-detail/01.jpg" alt=""--}}
{{--                                                      class="img-fluid blur-up lazyload"></div>--}}
{{--                                            <!-- <div><img src="assets/images/product-detail/02.jpg" alt=""--}}
{{--                                                    class="img-fluid blur-up lazyload"></div> -->--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="product-details-content">
                                <a class="wish-link" href="#">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart" class="svg-inline--fa fa-heart fa-w-16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path></svg>
                                </a>
                                <a href="#" class="cata">{{$product_data->category_name}}</a>
                                <h2>{{$product_data->name}}</h2>
                                <p class="quantity">{{$product_data->product_var_type_value}} {{$product_data->product_var_type_name}}</p>
                                @if($product_data->discounted_price == null)
                                    <div class="price">
                                        {{$product_data->currency_sign ." ". $product_data->selling_price}}
                                    </div>
                                @else
                                    <div class="price">
                                        {{$product_data->currency_sign ." ". $product_data->discounted_price}} <del>{{$product_data->currency_sign ." ". $product_data->selling_price}}</del>
                                    </div>
                                @endif
                                <!-- <h3 class="price">{{$product_data->selling_price}} {{$product_data->currency_name}}</h3> -->
                                <div class="price-increase-decrese-group d-flex">
{{--                                    <span class="decrease-btn">--}}
{{--                                        <button type="button"--}}
{{--                                                class="btn quantity-left-minus quantity-left-minus-btn" data-itemcode="{{$product_data->id}}" data-type="minus" data-field="">---}}
{{--                                        </button>--}}
{{--                                    </span>--}}
{{--                                    <input type="text" name="quantity" class="form-controls input-number" value="1" readonly>--}}
{{--                                    <span class="increase">--}}
{{--                                        <button type="button"--}}
{{--                                                class="btn quantity-right-plus quantity-right-plus-btn" data-itemcode="{{$product_data->id}}" data-type="plus" data-field="">+--}}
{{--                                        </button>--}}
{{--                                    </span>--}}
                                </div>
                                <p>{{$product_data->description}}</p>
                                <div class="d-flex justify-content-end">
                                    <button class="buy-now add_to_cart_button_by_id" data-itemcode="{{$product_data->id}}" data-itemimage="{{$imagePath}}">Add to cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- product-zoom-info section end -->

    </div>
   @else
         <center><h2>No product found</h2></center>
    @endif

@endsection

@section('scripts')

    @include('layouts.admin_common_js')


    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script language="javascript">



        $(document).on('click','.quantity-right-plus-btn',function () {
            var itemcode = jQuery(this).data('itemcode');
            var addedQuantity = parseInt(jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val()) + 1;
            jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val(addedQuantity);

            $.ajax({
                url: '{{ url('/website/item-quantity-add-from-sitebar') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    itemcode: itemcode,
                    addedQuantity: addedQuantity
                },
                success: function (response) {
                    // if (response.responseCode == 1) {
                    //     $('.total_price_section').html(response.price);
                    //     $('.delivery_cost_section').html(response.delivery_cost);
                    //     $('.grand_total_price_section').html(response.grand_totL);
                    // }
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
            var removedQuantity = parseInt(jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val()) - 1;
            if(removedQuantity < 1){ removedQuantity = 1;return false;}
            jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val(removedQuantity);

            $.ajax({
                url: '{{ url('/website/item-quantity-minus-from-sitebar') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    itemcode: itemcode,
                    removedQuantity: removedQuantity
                },
                success: function (response) {
                    // if (response.responseCode == 1) {
                    //     $('.total_price_section').html(response.price);
                    //     $('.delivery_cost_section').html(response.delivery_cost);
                    //     $('.grand_total_price_section').html(response.grand_totL);
                    // }

                    if (response.responseCode == 1) {
                        $('.sitebarItemData').html(response.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        })

        $(document).on('click','.item_remove_btn',function () {
            var itemcode = jQuery(this).data('itemcode');
            var div_pointer = jQuery(this);
            $.ajax({
                url: '{{ url('/website/item-remove-from-checkout') }}',
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
                        div_pointer.closest('.cart-product-item').remove();
                        $('.total_price_section').html(response.price);
                        $('.delivery_cost_section').html(response.delivery_cost);
                        $('.grand_total_price_section').html(response.grand_totL);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        })

        $(document).on('click','.place_order_btn',function () {

            if (!confirm('Are you sure want to place order ?')) {return false;}
            $('.error_message_section').html('');
            var btn = jQuery(this);
            var btn_content = btn.html();

            var delivery_schedule_id  = $('.checkout_delivery_schedule_id').val();
            var delivery_schedule_text  = $('.checkout_delivery_schedule_id').text();
         //   var customer_full_name  = $('.customer_full_name').val();
            var customer_address  = $('.customer_address').val();
            var coupon_number  = $('.coupon_number').val();
            var customer_mobile  = $('.customer_mobile').val();
            var delivery_date  = $('.delivery_date').val();

            if (delivery_schedule_id == ''){alert('Please select delivery schedule'); return false;}
          //  if (customer_full_name == ''){alert('Please enter full name'); return false;}
            if (customer_address == ''){alert('Please enter address'); return false;}
          //  if (coupon_number == ''){alert('Please enter email'); return false;}
            if (customer_mobile == ''){alert('Please enter email'); return false;}
            if (delivery_date == ''){alert('Please enter delivery date'); return false;}

            btn.html('<i class="fa fa-spinner fa-spin"></i>');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{ url('/website/place-order') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    delivery_schedule_id: delivery_schedule_id,
                    delivery_schedule_text: delivery_schedule_text,
                    delivery_date: delivery_date,
                 //   customer_full_name: customer_full_name,
                    customer_address: customer_address,
                    coupon_number: coupon_number,
                    customer_mobile: customer_mobile
                },
                success: function (response) {
                    btn.html(btn_content);
                    btn.prop('disabled', false);

                    if (response.responseCode == 1) {
                        $('.order_number_section').html(response.invoice);
                        $('.total_price_section').html(response.Total_price);
                        $('.place_order_init_section').css({"display":"none"});
                        $('.success_order_section').css({"display":"block"});
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }else if(response.responseCode == 5){
                        OpenSignUpForm();
                    } else {
                        $('.error_message_section').html(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        })

        $(document).on('click','.sign_up_button',function () {
            var btn = jQuery(this);
            var btn_content = btn.html();

            btn.html('<i class="fa fa-spinner fa-spin"></i>');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{ url('/sign-up') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {},
                success: function (response) {
                    btn.html(btn_content);
                    btn.prop('disabled', false);

                    if (response.responseCode == 1) {
                        CloseSignUpForm();
                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        })
    </script>
@endsection
