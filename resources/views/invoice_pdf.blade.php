<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php
//    $totalAmount= $paymentInfo->pay_amount + $paymentInfo->transaction_charge_amount + $paymentInfo->vat_amount;
$invoice = 'INVGEN'.dechex($orderDetailsData->invoice_number);
?>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">

                <h2>INVOICE # {{$invoice}} </h2>

                <div class="header_div">
                    <div class="left_content">
                        <table class="header_tbl">
                            <tr>
                                <td>Order Date </td>
                                <td>: </td>
                            </tr>
                            <tr>
                                <td>Discount </td>
                                <td>: {{$orderDetailsData->discount_amount}}</td>
                            </tr>
                            <tr>
                                <td>Delivery Cost </td>
                                <td>: {{$orderDetailsData->delivery_cost}}</td>
                            </tr>
                            <tr>
                                <td>Coupon Code </td>
                                <td>: {{$orderDetailsData->coupon_code}}</td>
                            </tr>
                            <tr>
                                <td>Coupon Amount </td>
                                <td>: {{$orderDetailsData->coupon_amount}}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="right_content">
                        <table class="header_tbl">
                            <tr>
                                <td>Customer Name</td>
                                <td>: {{$orderDetailsData->customer_name}} </td>
                            </tr>
                            <tr>
                                <td>Delivery Address </td>
                                <td>: {{$orderDetailsData->delivery_address}}</td>
                            </tr>
                            <tr>
                                <td>Customer Mobile </td>
                                <td>: {{$orderDetailsData->mobile_no}}</td>
                            </tr>
                            <tr>
                                <td>Delivery Hero </td>
                                <td>: {{$orderDetailsData->delivery_person}}</td>
                            </tr>
                            <tr>
                                <td>Delivery Man Mob </td>
                                <td>: {{$orderDetailsData->contact_no}}</td>
                            </tr>
                        </table>
                    </div>
                </div>


                <br>
                <h3>Order Details</h3>
                <table class="order_details" >
                    <tr>
                        <th> Item Name </th>
                        <th>Quantity </th>
                        <th>Quantity Type </th>
                        <th> Unit Price	</th>
                        <th> Total Price </th>
                    </tr>
                    @foreach($orderDetailsData->orderItemLists as $singleData)
                    <tr>
                        <td>{{$singleData->item_name}}</td>
                        <td>{{$singleData->quantity}}</td>
                        <td>{{$singleData->quantity_type}}</td>
                        <td>{{$singleData->unit_price}}</td>
                        <td>{{$singleData->item_total_price}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4">Total</td>
                        <td>{{$orderDetailsData->total_price}}</td>
                    </tr>
                    <tr>
                        <td colspan="4">Grand Total</td>
                        <td>{{$orderDetailsData->grand_total_price}}</td>
                    </tr>
                </table>



                <br/>


            </div>
        </div>
    </div>
</section>
</body>
</html>