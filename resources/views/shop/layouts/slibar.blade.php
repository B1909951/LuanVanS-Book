<div class="col-sm-3">
					<div class="left-sidebar">
						
                        <div class="brands_products"><!--brands_products-->
							<h2 style="padding-top: 5px">Danh mục</h2>
							<div class="brands-name">
								<ul class="nav nav-pills nav-stacked">
									@foreach($all_category as $cate)
                                    <li><a href="{{URL::to('/cate-products/'.$cate->id.'/1')}}"> <span class="pull-right"></span>{{$cate->name}}</a></li>
                                    @endforeach
									
								</ul>
							</div>
						</div><!--/brands_products-->
						<div class="brands_products" style="margin-top: 10px"><!--brands_products-->
							<h2>Thể loại</h2>
							<div class="brands-name">
								<ul class="nav nav-pills nav-stacked">
									@foreach($all_genre as $genre)
                                    <li><a href="{{URL::to('/genre-products/'.$genre->id.'/1')}}"> <span class="pull-right"></span>{{$genre->name}}</a></li>
                                    @endforeach
									
								</ul>
							</div>
						</div><!--/brands_products-->
						
						
					
					</div>
				</div>