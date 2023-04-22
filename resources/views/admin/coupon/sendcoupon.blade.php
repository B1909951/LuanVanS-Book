<h1>S-Book</h1>
<h2>Xin chào {{$user->name}}<h2>
<h3>S-Book gửi bạn coupon tháng này</h3>
@foreach ($list_coupon as $coupon)
    @if($coupon->expire_at >= date('Y-m-d'))<h4>{{$coupon->desc}}: {{$coupon->code}} - giảm {{$coupon->value}}%</h4>
    @endif
@endforeach
