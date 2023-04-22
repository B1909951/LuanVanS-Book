@extends('shop.layouts.main')
@section('title', 'S-Book | Trang chủ')
@section('content')

<div class="col-sm-9 padding-right">
	{{-- <div class="features_items"><!--features_items-->
		<h2 class="title text-center" style="padding-top: 5px">Gợi ý cho bạn</h2>
			@foreach ($recommend_products as $product)
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
	</div><!--/category-tab--> --}}
	<div class="recommended_items"><!--recommended_items-->
		<h2 class="title text-center" style="padding-top: 5px">Gợi ý cho bạn</h2>
		<?php $count = 0?>
		<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
				<div class="item active">	
					@foreach ($recommend_products as $product)
					<?php $count +=1?>
					<?php if($count <=3){

					?>
					<div class="col-sm-4">
						<a href="{{URL::to('/product-details/'.$product->id)}}">
							<div class="product-image-wrapper" style="height: 380px">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="{{asset("/assets/clients/pro_img/$product->image")}}" alt="" />
										<h2 style="margin-top: 5px; margin-bottom:10px">{{number_format($product->price, 0, '.',',').' vnđ'}}</h2>
										<form action="{{URL::to('/add-to-cart')}}" method="POST" style="margin-bottom:10px">
											@csrf
											<input type="hidden" value="{{Session::get('customer_id')}}" name="id"/>
											<input type="hidden" value="{{$product->id}}" name="id"/>
											<input type="hidden" value="1" name="count"/>
											<button type="submit" class="btn btn-default add-to-cart add-hover-cart"   >
												<i class="fa fa-shopping-cart"></i>
												Thêm vào giỏ hàng
											</button>
											

											</style>
										</form>
										<h4 style="overflow: hidden; height:40px">{{$product->name}}</h4>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php } ?>
					@endforeach
					
				</div>
				<?php $count = 0?>

				<div class="item">	

					@foreach ($recommend_products as $product)
					<?php $count +=1?>
					<?php if($count >3){
					?>
					<div class="col-sm-4">
						<a href="{{URL::to('/product-details/'.$product->id)}}">
							<div class="product-image-wrapper" style="height: 380px">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="{{asset("/assets/clients/pro_img/$product->image")}}" alt="" />
										<h2 style="margin-top: 5px;margin-bottom:10px">{{number_format($product->price, 0, '.',',').' vnđ'}}</h2>
										<form action="{{URL::to('/add-to-cart')}}" method="POST" style="margin-bottom:10px">
											@csrf
											<input type="hidden" value="{{Session::get('customer_id')}}" name="id"/>
											<input type="hidden" value="{{$product->id}}" name="id"/>
											<input type="hidden" value="1" name="count"/>
											<button type="submit" class="btn btn-default add-to-cart add-hover-cart"   >
												<i class="fa fa-shopping-cart"></i>
												Thêm vào giỏ hàng
											</button>
											<style>
												.add-hover-cart {
													background-color: #fc4f13; /* Màu nền ban đầu */
													color: #e6e4df; /* Màu chữ ban đầu */
												}
												
												.add-hover-cart:hover {
													background-color: #e6e4df!important; /* Màu nền khi hover */
													color: #fc4f13; /* Màu chữ khi hover */
												}
											 </style>
										</form>
										<h4 style="overflow: hidden; height:40px">{{$product->name}}</h4>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php } ?>
					@endforeach
					
				</div>
			</div>
			 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
				<i class="fa fa-angle-left"></i>
			  </a>
			  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
				<i class="fa fa-angle-right"></i>
			  </a>			
		</div>
	</div><!--/recommended_items-->
	<div class="features_items" ><!--features_items-->
		<h2 class="title text-center " style="padding-top: 20px; border-top:1px solid">Sản phẩm mới</h2>
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
	<a href="{{URL::to('/page/'.$current_page)}}"><h4 class="title text-center" style="padding-top: 5px;padding-bottom: 5px; text-decoration: underline; font-style: italic;">Xem tất cả sản phẩm</h4></a>
		
</div>
@endsection
