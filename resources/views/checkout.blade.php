@extends('layouts.admin_layout')

@section('styles')
    @include('layouts.admin_common_css')

@endsection

@section('content')

    <!-- dashboard-section start -->
    <section class="dashboard-section place_order_init_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">

                    <div class="form-item billing-item bg-color-white box-shadow p-3 p-lg-5 border-radius5">
                        <h6>Delivery Info</h6>
                        <form action="#" class="billing-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-item">
                                        <label>Delivery Address*</label>
                                        <textarea class="form-control customer_address"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-item">
                                        <label>Mobile*</label>
                                        <input type="text" class="customer_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" name="mobile">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-item">
                                        <label>Coupon</label>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input type="text" class="coupon_number" name="coupon">
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-primary btn-md coupon_validate_btn"
                                                              style="padding-top: 8%;padding-bottom: 7%;">Apply </button>
                                            </div>
                                            <div class="coupon_validate_message_section"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="form-item time-schedule bg-color-white box-shadow p-3 p-lg-5 border-radius5">
                        <h6>Delivery Date</h6>
                        <div style="margin-bottom: 10px;">
                            <input type="text" value="" class="form-control delivery_date" placeholder="Please select date" autocomplete="off" name="delivery_date" readonly required>
                        </div>
                        <h6>Delivery Schedule</h6>
                        <div class="time-schedule-container">
                            <select class="form-control checkout_delivery_schedule_id slot_option">
                                <option value=""> Select Schedule</option>
                            </select>
                            <div class="slot_loading"></div>
                        </div>
                    </div>

                    <div class="form-item payment-item bg-color-white box-shadow p-3 p-lg-5 border-radius5">
                        <div class="text-left">
                            <p class="error_message_section" style="color: darkred;font-weight: bold;"></p>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-lg btn-success place_order_btn">Place Order</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="cart-item sitebar-cart bg-color-white box-shadow p-3 p-lg-5 border-radius5">
                        <div class="cart-product-container">
                            @php
                                $totalPrice = 0;
                                $sessionData = Session::get( 'cart_item_array' );
                                $finalItemArray = isset($sessionData) ? $sessionData : [];
                            @endphp
                            @foreach($finalItemArray as $item)
                                @php
                                    $totalPrice += $item['item_unit_price']*$item['quantity'];
                                @endphp
                                <div class="cart-product-item">
                                    <button class="btn btn-sm btn-danger item_remove_btn" data-itemcode="{{$item['item_code']}}" style="float: right;">X</button>
                                    <div class="row align-items-center">
                                        <div class="col-6 p-0">
                                            <div class="thumb">
                                                <a onclick="openModal()"><img src="{{$item['item_image']}}" alt="products"></a>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="product-content">
                                                <a onclick="openModal()" class="product-title">{{$item['item_name']}}</a>
                                                <div class="product-cart-info">
                                                    Unit Price : {{$item['item_unit_price']}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <div class="price-increase-decrese-group d-flex">
                                                        <span class="decrease-btn">
                                                            <button type="button"
                                                                    class="btn quantity-left-minus-btn" data-itemcode="{{$item['item_code']}}" data-type="minus" data-field="">-
                                                            </button>
                                                        </span>
                                                <input type="text" name="quantity" class="form-controls input-number" value="{{$item['quantity']}}" readonly>
                                                <span class="increase">
                                                            <button type="button"
                                                                    class="btn quantity-right-plus-btn" data-itemcode="{{$item['item_code']}}" data-type="plus" data-field="">+
                                                            </button>
                                                        </span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="product-price">
{{--                                                <span class="ml-4">{{$item['quantity']*$item['item_unit_price']}} BDT</span>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="cart-footer">
{{--                            <div class="product-other-charge">--}}
{{--                                <p class="d-flex justify-content-between">--}}
{{--                                    <span>Delevery charge</span>--}}
{{--                                    <span>$8.00</span>--}}
{{--                                </p>--}}
{{--                                <a href="#">Do you have a voucher?</a>--}}
{{--                            </div>--}}

                            <div class="cart-total">
                                <p class="saving d-flex justify-content-between">
                                    <span>Sub Total</span>
                                    <span><b class="total_price_section">R {{$totalPrice}}</b></span>
                                </p>
                                <p class="saving d-flex justify-content-between">
                                    <span>Delivery Cost</span>
                                    <span><b class="delivery_cost_section">R {{$deliveryCost}}</b></span>
                                    <input type="hidden" class="delivery_cost_section_hidden" value="{{$deliveryCost}}">
                                </p>
                                <p class="saving d-flex justify-content-between coupon_amount_section" style="display: none;">
                                    <span>Coupon amount</span>
                                    <span><b class="coupon_amount_val">0</b></span>
                                </p>
                                <p class="total-price d-flex justify-content-between">
                                    <span>Grand Total</span>
                                    <span><b class="grand_total_price_section">R {{$deliveryCost+$totalPrice}}</b></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- dashboard-section end -->

    <!-- Product order success section start -->
    <section class="product-order-success-section section-ptb success_order_section" style="display: none;">
        <div class="container">
            <div class="product-order-success-container">
                <div class="product-order-success">
                    <div class="iconimage">
                        <img src="{{asset('website_src/images/checkicon.png')}}" alt="icon">
                    </div>
                    <h2>Your order has been received.</h2>
                    <p>Thank you for ordering with us</p>
                    <a href="{{url('/')}}" class="order-btn">Make New Order</a>
                </div>
                <div class="order-description">
                    <ul class="order-info">
                        <li>
                            <h6>Your Order Number</h6>
                            <span>:</span>
                            <p class="order_number_section"></p>
                        </li>
                        <li>
                            <h6>Total</h6>
                            <span>:</span>
                            <p class="total_price_section"></p>
                        </li>
                    </ul>
{{--                    <a href="#" class="view-summery">View a summary of your order</a>--}}
                </div>
            </div>
        </div>
    </section>
    <!-- Product order success section end -->

{{--    <!-- Modal -->--}}
{{--    <div class="modal fade" id="myModal" role="dialog">--}}
{{--        <div class="modal-dialog">--}}

{{--            <!-- Modal content-->--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
{{--                    <h4 class="modal-title">Modal Header</h4>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <p>Some text in the modal.</p>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}


@endsection

@section('scripts')

    @include('layouts.admin_common_js')

    <script type="text/javascript" src="{{asset('website_src/date_picker/jquery-1.10.2.j')}}"></script>
    <script type="text/javascript" src="{{asset('website_src/date_picker/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{asset('website_src/date_picker/bootstrap.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('website_src/date_picker/jquery-ui.css')}}" />
    <script type="text/javascript" src="{{asset('website_src/date_picker/moment.min.js')}}"></script>
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script language="javascript">

        @if(! $authId)
            OpenSignUpForm();
        @endif
        // $(function () {
        //     $(".dob_val").datepicker({
        //         autoclose: true,
        //         todayHighlight: true,
        //         format: 'yyyy/mm/dd'
        //     }).datepicker('update', new Date());
        // });

        $(function () {
            $(".delivery_date").datepicker({
               // setDate: new Date(),
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy/mm/dd',
                minDate: 'now',
                maxDate: '+7d'
            }).datepicker("setDate", new Date());

            $(".delivery_date").trigger('change');
        });

        $(document).on('click', '.coupon_validate_btn', function () {
            var coupon_number = $('.coupon_number').val();
            var delivery_cost_section_hidden = $('.delivery_cost_section_hidden').val();
            $('.coupon_validate_message_section').html('');
            if (coupon_number == '') {
                alert('Please enter valid coupon');
                return false;
            }
            var btn = jQuery(this);
            var btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i>');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{ url('/website/validate-coupon') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    coupon_number: coupon_number,
                    delivery_cost_section_hidden: delivery_cost_section_hidden
                },
                success: function (response) {
                    btn.html(btn_content);
                    btn.prop('disabled', false);
                    if (response.responseCode == 1) {
                        $('.coupon_amount_section').css({"display":"block"});
                        $('.coupon_amount_val').html('R '+response.message);
                        $('.grand_total_price_section').html('R '+response.final_amount);
                        $('.coupon_validate_message_section').html('<span style="color: #0d3625">Coupon applied</span>');
                    } else {
                        $('.coupon_amount_section').css({"display":"none"});
                        $('.coupon_amount_val').html('R 0');
                        $('.grand_total_price_section').html('R '+response.final_amount);
                        $('.coupon_validate_message_section').html('<span style="color: red">'+response.message+'</span>');
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    location.reload();

                }
            });
        })

        $(document).on('change','.delivery_date',function () {
            var delivery_date = $('.delivery_date').val();
            $('.slot_loading').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading ......');

            $.ajax({
                url: '{{ url('/website/get-delivery-timeslot') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    delivery_date: delivery_date
                },
                success: function (response) {
                    $('.slot_loading').html('');
                    $('.slot_option').empty();
                    if (response.responseCode == 1) {
                        $.each(response.schedule_data, function (i, d) {
                            $('.slot_option').append($('<option>', {
                                value: d.day_slot,
                                text: d.day_slot
                            }));
                        });
                    }else {
                        $('.slot_option').append($('<option>', {
                            value: '',
                            text: 'No schedule'
                        }));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    location.reload();

                }
            });
        })

        $(document).on('click','.quantity-right-plus-btn',function () {
            var itemcode = jQuery(this).data('itemcode');
            var addedQuantity = parseInt(jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val()) + 1;
            jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val(addedQuantity);

            $.ajax({
                url: '{{ url('/website/item-quantity-add-from-checkout') }}',
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
                    if (response.responseCode == 1) {
                        $('.total_price_section').html('R '+response.price.toFixed(2));
                        $('.delivery_cost_section').html('R '+response.delivery_cost);
                        $('.grand_total_price_section').html('R '+response.grand_totL.toFixed(2));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    location.reload();

                }
            });
        })


        $(document).on('click','.quantity-left-minus-btn',function () {
            var itemcode = jQuery(this).data('itemcode');
            var removedQuantity = parseInt(jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val()) - 1;
            if(removedQuantity < 1){ removedQuantity = 1;return false;}
            jQuery(this).closest('.price-increase-decrese-group').find('.input-number').val(removedQuantity);

            $.ajax({
                url: '{{ url('/website/item-quantity-remove-from-checkout') }}',
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
                    if (response.responseCode == 1) {
                        $('.total_price_section').html('R '+response.price.toFixed(2));
                        $('.delivery_cost_section').html('R '+response.delivery_cost);
                        $('.grand_total_price_section').html('R '+response.grand_totL.toFixed(2));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    location.reload();

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
                        $('.total_price_section').html('R '+response.price.toFixed(2));
                        $('.delivery_cost_section').html('R '+response.delivery_cost.toFixed(2));
                        $('.grand_total_price_section').html('R '+response.grand_totL.toFixed(2));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    location.reload();

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
                    location.reload();

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
                    location.reload();

                }
            });
        })
    </script>
@endsection
