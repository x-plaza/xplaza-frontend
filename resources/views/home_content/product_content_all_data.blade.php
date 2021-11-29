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
                    <div class="product-item">
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
                               data-itemprice="{{$product->selling_price ." ". $product->currency_name}}"
                               data-itemquantity="{{$product->product_var_type_value." ".$product->product_var_type_name}}"
                               data-itemname="{{$product->name}}" onclick="openModal()"><img src="{{$imagePath}}"
                                                                                             alt="product"></a>
                            <span class="batch sale">Sale</span>
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
                            <a href="#" class="cata">Catagory</a>
                            <h6><a href="product-detail.html" class="product-title">{{$product->name}}
                                    Graves</a><span class="quantity_custom">{{$product->product_var_type_value." ".$product->product_var_type_name}}</span></h6>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price">{{$product->selling_price ." ". $product->currency_name}}</div>

                                <div class="cart-btn-toggle cart-btn-custom add_to_cart_button"
                                     data-itemcode="{{$product->id}}"
                                     data-itemimage="{{$imagePath}}"
                                     data-itemname="{{$product->name}}"
                                     data-itemunit="{{$product->product_var_type_value." ".$product->product_var_type_name}}"
                                     data-itemprice="{{$product->selling_price}}"
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




