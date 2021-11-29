
@foreach($product_data as $product)
<div class="order-card mb--30">
    <div class="order-card-header d-flex justify-content-between align-items-center">
        <span class="deliver">{{$product->status_name}}</span>
        <span class="date"><i class="far fa-clock"></i> {{$product->received_time}}</span>
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
                <td class="text-center">Order#INVXPLZ{{$product->invoice_number}}</td>
                <td class="text-center">{{$totalItem}} Items</td>
                <td class="text-right">{{$product->grand_total_price}} {{$product->currency_sign}}</td>
            </tr>
            </tbody>
        </table>
        <div class="order-info-extra mt--20">
            <div class="row">
                <div class="col-sm-6">
                    <h6>Ordered item</h6>
                    <ul>
                        @foreach($product->orderItemPlaceLists as $item)
                            <li><i class="fas fa-check"></i>{{$item->item_name}} ( {{$item->quantity}} {{$item->quantity_type}} )</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-sm-6">
                    <h6>Price section</h6>
                    <div class="destination-box">
                        <ul>
                            <li>Total Price : {{$product->total_price}} {{$product->currency_sign}}</li>
                            <li>Delivery Cost : {{$product->delivery_cost}} {{$product->currency_sign}}</li>
                            <li>Grand Total : {{$product->grand_total_price}} {{$product->currency_sign}}</li>
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


