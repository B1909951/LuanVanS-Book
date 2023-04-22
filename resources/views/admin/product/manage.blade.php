@extends('admin.layouts.main')
@section('title', 'Admin | Quản lý sản phẩm')
@section('content')
<div class="container-fluid pt-4 px-4">
<div class="row g-4">
<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4 fs-3">Danh sách sản phẩm</h6>
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
        <div class="table-responsive">
            <div id="toolbar" class="btn-group">
                <a href="{{URL::to('/admin/product-add')}}" class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> Thêm sản phẩm
                </a>
                
            </div>
            <!-- Spinner Start -->
            <div id="spinner-recommend" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center d-none loading_create_list" style="z-index: 10000; opacity: 0.8;">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only" ">Loading...</span>
                </div>
                <br>
                <span style="color: black; padding-left:10px">Đang tạo danh sách</span>

            </div>
        <!-- Spinner End -->
        
            <div id="toolbar" class="btn-group" >
                <a id ="create_list"  href="{{URL::to('/admin/add-product-recommend')}}"><button class="btn btn-primary" ><i class="glyphicon glyphicon-refresh" ></i>Tạo mới danh sách sản phẩm tương tự</button></a>
            </div>
            
            
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
                        <th style="">
                            <div class="th-inner ">Show</div>
                            <div class="fht-cell"></div>
                        </th>
                        
                        <th style="text-align: right">
                            <div class="th-inner ">Hành động</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_product as $pro)
                    @if($pro->show<2)
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
                        <td style="">@if($pro->show==1)Có @else Không @endif</td>


                        <td class="form-group" style="text-align: right; margin-right:10%">
                            <a href="{{URL::to('/admin/product-edit/'.$pro->id)}}" class="btn btn-primary">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a  onclick="return confirm('Bạn có muốn xóa sản phẩm này?')" href="{{URL::to('/admin/product-delete/'.$pro->id)}}" class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
        
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/product-manage/<?php echo 1; ?>" aria-label="First">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/product-manage/<?php echo $current_page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php if ($current_page >1){
                        ?>
                    
                    <li class="page-item">
                        <a class="page-link" href="/admin/product-manage/<?php echo $current_page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">{{$current_page - 1}}</span>
                        </a>
                    </li>
                    <?php
                    }
                    ?>
                    <li class="page-item active">
                        <a class="page-link" href="/admin/product-manage/<?php echo $current_page; ?>"><?php echo $current_page; ?><span class="sr-only">(current)</span></a>
                    </li>
                    <?php if ($current_page < $total_pages){
                        ?>
                    
                    <li class="page-item">
                        <a class="page-link" href="/admin/product-manage/<?php echo $current_page + 1 ; ?>" aria-label="Previous">
                            <span aria-hidden="true">{{$current_page + 1}}</span>
                        </a>
                    </li>
                    <?php
                    }
                    ?>
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/product-manage/<?php echo $current_page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
        
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/product-manage/<?php echo $total_pages; ?>" aria-label="Last">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
        
                </ul>
            </nav>
        </div>
    </div>
    
</div>
</div>
</div>


    
@endsection