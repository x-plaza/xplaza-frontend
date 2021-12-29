<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="row product-list">
                @php
                $productCounter = 0;
                @endphp
                @foreach($product_data as $product)
                    @php
                        $productCounter ++;
                    @endphp
                    <div class="col-sm-6 col-lg-4 col-xl-3">
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
                               data-itemname="{{$product->name}}" onclick="openModal()">
                               <img src="{{$imagePath}}" alt="product">
                            </a>
                            @if($product->discounted_price)
                                    <span class="batch sale">Sale</span>
                            @endif
                            <!-- <span class="batch sale">Sale</span> -->
                            <a class="wish-link" href="#">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                     data-icon="heart" class="svg-inline--fa fa-heart fa-w-16"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path
                                            d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                        <div class="product-content">
{{--                            <a href="#" class="cata">Catagory</a>--}}
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

                            <div class="cart-btn-toggle cart-btn-custom add_to_cart_button" style="float: right;"
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

                @if($productCounter > 12)
                    <div class="col-12 text-center mt-4">
                        <button class="loadMore">Load More</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>




