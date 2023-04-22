@extends('admin.layouts.main')
@section('title', 'Admin | Sửa sản phẩm')
@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="col-md-8">
                    <h3 class="title text-left">Cập nhật sản phẩm</h3>

                    <?php 
                    $error = Session::get('error');
                    $success = Session::get('success');

                    if($error){
                        echo '<div class="alert alert-danger">'.$error.'</div>' ;
                        Session::put('error',null);
                    }
                    if($success){
                        echo '<div class="alert alert-success">'.$success.'</div>' ;
                        Session::put('success',null);
                    }
                    ?>
                    <form action="{{URL::to('/admin/product-edit-product')}}" role="form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input  name="id" type="hidden" class="form-control" placeholder="" value="{{$product_edit->id}}">
                        <input  name="now_image" type="hidden" class="form-control" placeholder="" value="{{$product_edit->image}}">
                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <input name="name" class="form-control" placeholder="" required value="{{$product_edit->name}}">
                            <span class="form-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Giá(vnđ)</label>
                            <input  name="price" type="number" min="0" class="form-control" required value="{{$product_edit->price}}">
                            <span class="form-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Tên tác giả</label>
                            <input name="author" class="form-control" placeholder="" required value="{{$product_edit->author}}">
                            <span class="form-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Mô tả ngắn</label>

                            <textarea name="desc" class="form-control" placeholder=""
                                id="floatingTextarea" style="height: 50px;">{{$product_edit->desc}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Chi tiết</label>

                            <textarea name="detail" class="form-control" placeholder=""
                                id="floatingTextarea" style="height: 50px;">{{$product_edit->detail}}</textarea>
                        </div>
                        <label>Thể loại</label>

                        @foreach ($all_genre as $genre)
                        <div class="form-check form-switch">
                            
                            <input class="form-check-input" name="genres[]" type="checkbox" role="switch"
                                id="flexSwitchCheckDefault"@foreach ($pro_genres as $pro_genre) @if($pro_genre->product_id == $product_edit->id && 
                                $pro_genre->genre_id ==$genre->id) checked @endif @endforeach value="{{$genre->id}}" >
                            <label class="form-check-label" for="flexSwitchCheckDefault">{{$genre->name}}</label>
                            
                        </div>
                        @endforeach
                        <label>Danh mục sản phẩm</label>
                        <select class="form-select form-select-sm mb-3" name="cate" aria-label=".form-select-sm example">
                            @foreach ($categories as $cate)
                                @if($product_edit->cate_id == $cate->id)<option selected value="{{$cate->id}}">{{$cate->name}}</option>@endif
                                @if($product_edit->cate_id != $cate->id)<option value="{{$cate->id}}">{{$cate->name}}</option>@endif
                            @endforeach
                        </select>
                        <div class="form-group mb-3">
                            <label>Ảnh sản phẩm</label> <br>
                            <input   id="avatar" name="image"  type="file" accept="image/*" onchange="loadFile(event)">
                            <span class="form-message"></span> <br>
                            <img id="output" src="{{asset("/assets/clients/pro_img/$product_edit->image")}}" width="250">
                        </div>
                        <button name="sbm" type="submit" class="btn btn-success">Cập nhật</button>
                        <a href="{{URL::to('/admin/product-edit-recommend/'.$product_edit->id)}}" class="btn btn-primary">
                            Làm mới danh sách sản phẩm gợi ý</i>
                        </a>
                        <a href="{{URL::to('/admin/product-manage')}}" class="btn btn-danger    ">
                            Trở về</i>
                        </a> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4 fs-3">Danh sách sản phẩm gợi ý cho sản phẩm này</h6>
            @if($pro_recommend->isEmpty()) <p>Chưa có danh sách sản phẩm gợi ý</p>
            @else
            <div class="table-responsive">
                
                <table data-toolbar="#toolbar" data-toggle="table" class="table table-hover">
                    <thead>
                        <tr>
                            <th style="">
                                <div class="th-inner sortable">ID</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Tên</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner "></div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Giá</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Thể loại</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Danh mục</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner ">View</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner ">Rate</div>
                                <div class="fht-cell"></div>
                            </th>   
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pro_recommend as $pro) 
                        <tr data-index="0">
                            <td style="">{{$pro->id}}</td>
                            <td style="" width = "20%">{{$pro->name}}</td>
                            <td style="">
                                <img width="100" src="{{asset("/assets/clients/pro_img/$pro->image")}}" width="75">
                            </td>
                            
                            <td style="">{{number_format($pro->price, 0, '.',',').' vnđ'}}</td>
                            
                            
                            <td style="">
                                @foreach ($pro_genres as $genre)
                                <div>
                                    @if($genre->product_id == $pro->id)•{{$genre->name}} @endif
                                </div>
                                @endforeach
                            </td>
                            <td style="">
                                @foreach ($categories as $cate)
                                <div>
                                    @if($cate->id == $pro->cate_id){{$cate->name}} @endif
                                </div>
                                @endforeach
                            </td>
                            <td style="">{{$pro->view}}</td>
                            <td style="">{{$pro->star}}<i class="fa fa-star text-warning" ></i></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    </div>
    </div>
    
@endsection
