@extends('shop.layouts.main')
@section('title', 'S-Book | Trang chủ')
@section('content')

<div class="col-sm-9 padding-right">
	
	<div class="features_items" id="products" ><!--features_items-->
		<h2 class="title text-center " style="padding-top: 5px; ">Danh sách sản phẩm trang {{$current_page}}</h2>
		@foreach ($all_product as $product)
		<div class="col-sm-4">
			<a href="{{URL::to('/product-details/'.$product->id)}}">
			<div class="product-image-wrapper" style="height: 380px">
				<div class="single-products">
					
					<div class="productinfo text-center">
						<img src="{{asset("/assets/clients/pro_img/$product->image")}}" alt="" />
						<h2 style="margin-top: 5px">{{number_format($product->price, 0, '.',',').' vnđ'}}</h2>
						<form action="{{URL::to('/add-to-cart')}}" method="POST" style="margin-bottom:10px">
							@csrf
							<input type="hidden" value="{{Session::get('customer_id')}}" name="id"/>
							<input type="hidden" value="{{$product->id}}" name="id"/>
							<input type="hidden" value="1" name="count"/>
							<button type="submit" class="btn btn-default add-to-cart" >
								<i class="fa fa-shopping-cart"></i>
								Thêm vào giỏ hàng
							</button>
						</form>
						<h4 style="overflow: hidden; height:40px">{{$product->name}}</h4>
					</div>
				</div>
			</div>
			</a>
		</div>
		@endforeach
		
	</div><!--/category-tab-->	
	<div class="text-center">
		<ul class="pagination" style="text-align: center ">
			<?php if (true): ?>
		  		<li><a href="/page/<?php echo 1; ?>#products"><<</a></li>
			<?php endif; ?>
		  		<li><a href="/page/<?php echo $current_page - 1; ?>#products" class="active"><</a></li>
	  
			<?php for ($i = max(1, $current_page - 1); $i <= min($current_page + 1, $total_pages); $i++): ?>
    			<?php if ($i == $current_page): ?>
      				<li class="active"><span><?php echo $i; ?></span></li>
    			<?php else: ?>
      				<li><a href="/page/<?php echo $i; ?>#products"><?php echo $i; ?></a></li>
    			<?php endif; ?>
  			<?php endfor; ?>
	  
		  	<li><a href="/page/<?php echo $current_page + 1; ?>#products">></a></li>
			<?php if (true): ?>
		  	<li><a href="/page/<?php echo $total_pages; ?>#products">>></a></li>
			<?php endif; ?>
	  	</ul>	
	</div>		
</div>
@endsection
