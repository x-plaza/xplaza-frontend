<div class="container">
    <div class="section-heading">
        <h3 class="heading-title">Trending Products</h3>
    </div>

    <div class="section-wrapper">
        <!-- Add Arrows -->
        <div class="slider-btn-group">
            <div class="slider-btn-prev trending-slider-prev">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 443.52 443.52" style="enable-background:new 0 0 443.52 443.52;"
                     xml:space="preserve">
                                    <g>
                                        <path d="M143.492,221.863L336.226,29.129c6.663-6.664,6.663-17.468,0-24.132c-6.665-6.662-17.468-6.662-24.132,0l-204.8,204.8
                        c-6.662,6.664-6.662,17.468,0,24.132l204.8,204.8c6.78,6.548,17.584,6.36,24.132-0.42c6.387-6.614,6.387-17.099,0-23.712
                        L143.492,221.863z" />
                                    </g>
                                </svg>
            </div>
            <div class="slider-btn-next trending-slider-next">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;"
                     xml:space="preserve">
                                    <g>
                                        <path d="M388.425,241.951L151.609,5.79c-7.759-7.733-20.321-7.72-28.067,0.04c-7.74,7.759-7.72,20.328,0.04,28.067l222.72,222.105
                        L123.574,478.106c-7.759,7.74-7.779,20.301-0.04,28.061c3.883,3.89,8.97,5.835,14.057,5.835c5.074,0,10.141-1.932,14.017-5.795
                        l236.817-236.155c3.737-3.718,5.834-8.778,5.834-14.05S392.156,245.676,388.425,241.951z" />
                                    </g>
                                </svg>
            </div>
        </div>
        <div class="mlr-20">
            <div class="trending-product-container">
                <div class="swiper-wrapper">
                    @foreach($product_data as $product)
                        <div class="swiper-slide swiper-slide-custom trending-products-custom-style">
                        <div @if($product->quantity > 0)class="product-item"@else class="product-item stock-out" @endif>
                            <div class="product-thumb">
                                @php
                                    $imagePath = 'website_src/product_sample.png';
                                    if (isset($product->productImageList[0])){
                                        $imagePath = "https://admin.xwinkel.com/item_image/".$product->productImageList[0]->name;
                                    }
                                @endphp
                                <a class="product_modal_open_button" data-itemcode="{{$product->id}}"
                                   data-itemimage="{{$imagePath}}"
                                   data-itemdetails="{{$product->description}}"
                                   <?php if($product->discounted_price):?>
                                    data-itemprice="{{ $product->currency_sign." ". $product->discounted_price}} <del>{{$product->currency_sign ." ". $product->selling_price}}</del>"
                                    <?php else:?>
                                    data-itemprice="{{ $product->currency_sign." ". $product->selling_price}}"
                                    <?php endif;?>
                                   data-itemquantity="{{$product->product_var_type_value." ".$product->product_var_type_name}}"
                                   data-itemname="{{$product->name}}" onclick="openModal()"><img src="{{$imagePath}}"
                                                              alt="product">
                                </a>
                                <!-- <span class="batch sale">Sale</span> -->
                                @if($product->discounted_price)
                                    <span class="batch sale">Sale</span>
                                @endif
{{--                                <a class="wish-link" href="#">--}}
{{--                                    <svg aria-hidden="true" focusable="false" data-prefix="fas"--}}
{{--                                         data-icon="heart" class="svg-inline--fa fa-heart fa-w-16"--}}
{{--                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">--}}
{{--                                        <path--}}
{{--                                                d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z">--}}
{{--                                        </path>--}}
{{--                                    </svg>--}}
{{--                                </a>--}}
                            </div>
                            <div class="product-content">
{{--                                <a href="#" class="cata">Catagory</a>--}}
                                <h6><a href="{{url('website/item-details/'.$product->id)}}" class="product-title">{{$product->name}}
                                        </a><span class="quantity_custom">{{$product->product_var_type_value." ".$product->product_var_type_name}}</span></h6>

                                <div class="d-flex justify-content-between align-items-center">
                                    @if($product->discounted_price == null)
                                        <div class="price">
                                            {{$product->currency_sign ." ". $product->selling_price}}
                                        </div>
                                    @else
                                        <div class="price">
                                            {{$product->currency_sign ." ". $product->discounted_price}} <del>{{$product->currency_sign ." ". $product->selling_price}}</del>
                                        </div>
                                    @endif
                                </div>
                                <div class="cart-btn-toggle cart-btn-custom2 add_to_cart_button" style="float: right;"
                                     data-itemcode="{{$product->id}}"
                                     data-itemimage="{{$imagePath}}"
                                     data-itemname="{{$product->name}}"
                                     data-itemunit="{{$product->product_var_type_value." ".$product->product_var_type_name}}"
                                    @if($product->discounted_price == null)
                                     data-itemprice="{{$product->selling_price}}"
                                    @else
                                     data-itemprice="{{$product->discounted_price}}"
                                    @endif
                                     data-itembrandid="{{$product->brand_id}}"
                                     data-itemcurrencyid="{{$product->currency_id}}"
                                     data-itemcategoryid="{{$product->category_id}}"
                                     data-itemcategoryname="{{$product->category_name}}"
                                     data-itemvartypename="{{$product->product_var_type_name}}"
                                     data-itemvartypevalue="{{$product->product_var_type_value}}"
                                     data-itemquantitytype="{{$product->product_var_type_name}}"
                                >
                                                        <span class="cart-btn"><i class="fas fa-shopping-cart"></i> <b
                                                                    class="cart-text-conditional-show">Cart</b></span>

                                    <div class="price-btn">
                                        <div class="price-increase-decrese-group d-flex">
                                                                <span class="decrease-btn">
                                                                    <button type="button"
                                                                            class="btn quantity-left-minus"
                                                                            data-type="minus" data-field="">-
                                                                    </button>
                                                                </span>
                                            <input type="text" name="quantity"
                                                   class="form-controls input-number" value="1">
                                            <span class="increase">
                                                                    <button type="button"
                                                                            class="btn quantity-right-plus" data-type="plus"
                                                                            data-field="">+
                                                                    </button>
                                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @if(count($product_data) > 0)
                <div class="text-center pt-3">
                    <a href="{{url('/all-trending-products')}}" class="more-product-btn">More Products</a>
                </div>
            @else
                <div class="text-center pt-3">
                    No product available
                </div>
            @endif
        </div>
    </div>
</div>
