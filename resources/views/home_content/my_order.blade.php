
@foreach($product_data as $product)
<div class="order-card mb--30">
    <div class="order-card-header d-flex flex-column">
        <span class="deliver">{{$product->status_name}}</span>
        <span class="date"><STRONG>Order Date:</STRONG> {{$product->received_time}}</span>
        <span class="date"><STRONG>Date to deliver:</STRONG> {{$product->date_to_deliver}}</span>
        <span><STRONG>Timing:</STRONG> {{$product->allotted_time}}</span>
        <span><STRONG>Address: </STRONG>{{$product->delivery_address}}</span>
    </div>
    @php
     $totalItem = 0;
    @endphp
    @foreach($product->orderItemPlaceLists as $item)
        @php
            $totalItem ++;
        @endphp
    @endforeach
    <div class="order-card-body">
        <table>
            <thead>
            <tr>
                <th class="text-center">Orders:</th>
                <th class="text-center">Items:</th>
                <th  class="text-right">Total Payments</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">Order - {{$product->invoice_number}}</td>
                <td class="text-center">{{$totalItem}} Items</td>
                <td class="text-right">{{$product->currency_sign}} {{$product->grand_total_price}}</td>
            </tr>
            </tbody>
        </table>
        <div class="order-info-extra mt--20">
            <div class="row">
                <div class="col-sm-6">
                    <h6>Ordered item</h6>
                    <ul>
                        @foreach($product->orderItemPlaceLists as $item)
                            <li><i class="fas fa-check"></i>{{$item->item_name}} ( unit: {{$item->quantity}} )</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-sm-6">
                    <h6>Price section</h6>
                    <div class="destination-box">
                        <ul>
                            <li>Total Price : {{$product->currency_sign}} {{$product->total_price}}</li>
                            <li>Delivery Cost :{{$product->currency_sign}} {{$product->delivery_cost}}</li>
                            <li>Coupon Amount :{{$product->currency_sign}} {{$product->coupon_amount}}</li>
                            <li>Grand Total : {{$product->currency_sign}} {{$product->grand_total_price}}</li>
                        </ul>
                    </div>
                </div>
            </div>
{{--            <div class="d-flex justify-content-end mt--25">--}}
{{--                <a class="order-detail" href="#">Order Details</a>--}}
{{--                <a class="review" href="#">Review</a>--}}
{{--            </div>--}}
        </div>
    </div>
    <div class="order-card-footer">
        <div class="text-center">
            <span class="view">View More</span>
            <span class="show-less">Show Less</span>
        </div>
    </div>
</div>
@endforeach


