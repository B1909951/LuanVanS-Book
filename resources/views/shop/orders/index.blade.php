@extends('shop.layouts.sub')
@section('title', 'S-Book | Lịch sử đặt hàng')
@section('content')

<div class="col-sm-9 padding-right">
    <h2 class="title text-center">Lịch sử đặt hàng</h2>
    <section id="cart_items">
        <div class="container-fluid">
        
            <div class="table-responsive cart_info">
                <table class="table table-condensed">
                    <thead>
                    <tr class="cart_menu">
                        <td class="">ID</td>
                        <td></td>
                        <td class="">Giá tiền</td>
                        <td class="">Mã giảm</td>
                        <td class="">Tình trạng</td>
                        <td class="">Ngày đặt</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=0;?>
                        @foreach($orders as $order)
                    <?php $i+=1?>
                    <tr>
                        <td class="" style="padding-left:20px;">
                            <p class="cart_total_price">{{$order->order_id}}
                               @if($i==1)
                                <span class="badge" style="font-size:18px;color:red; background-color:#ffffff00">New</span>
                                @endif
                            </p>
                        </td>
                        <td><a href="{{URL::to('/order-details/'.$order->order_id)}}">Xem chi tiết</a></td>
                        <td class="">
                            <p>{{number_format($order->total, 0, '.',',').' vnđ'}}</p>
                        </td>
                        
                        <td class="" >
                            @foreach($coupons as $coupon)@if($coupon->coupon_id ==$order->coupon_id)
                                <p class="">Code: {{$coupon->code}}</p>
                                <p class="">Giảm: {{$coupon->value}}%</p>                             
                                @endif
                            @endforeach
                        </td>
                        <td class="" >
                            <p class="">@if($order->status==0)Chưa duyệt @endif @if($order->status==1)Đang vận chuyển @endif @if($order->status==2)Đã nhận hàng @endif @if($order->status==3)Đã được hoàn trả @endif @if($order->status==4)Đã bị hủy @endif</p>
                        </td>
                        <td class="" >
                            <p class="">{{$order->created_at}}</p>
                        </td>
                        
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<h2 class="title text-center">Danh sách đánh giá sản phẩm đã mua</h2>
    <section id="cart_items">
        <div class="container-fluid">
        
            <div class="table-responsive cart_info">
                <table class="table table-condensed">
                    <thead>
                        <tr class="cart_menu">
                            <td class="image">Sản phẩm</td>
                            <td class="description"></td>
                            <td  class="total" style="width: 40%; text-align: center;: right ">Đánh giá</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $pro)
                        <tr>
                            <td class="cart_product">
                                <a href="{{URL::to('/product-details/'.$pro->product_id)}}"><img style="height: 150px; width:150px" src="{{asset("/assets/clients/pro_img/$pro->image")}}" alt=""></a>
                            </td>
                            <td class="cart_description" style="padding-left:40px;width:30%">
                                <h4><a href="">{{$pro->name}}</a></h4>
                                <p>Web ID: {{$pro->product_id}}</p>
                            </td>
                            
                            <?php $r = null;
                                foreach($ratings as $rate){
                                    if($rate->product_id == $pro->product_id){
                                        $r = $rate;
                                    }
                                }
                            
                            ?>
                            
                            
                            <td class="cart_total" style="width: 40%; text-align: left ">
                                @if($r!= null)
                                    <p style="margin-right:1px; font-size:18px; color:#fc4f13">
                                        @for($i=1;$i<=$r->rating;$i++)<i class="fa fa-star"> </i>@endfor 
                                    </p>
                                    <p>
                                        {{$r->comment}}
                                    </p>
                                @else
                                    <p>Chưa đánh giá</p> 
                                    
                                @endif
                            </td>
                            
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
</div>
</section>
@endsection