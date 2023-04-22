@extends('admin.layouts.main')
@section('title', 'Admin | Quản lý đánh giá')
@section('content')
<div class="container-fluid pt-4 px-4">
<div class="row g-4">
<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4 fs-3">Danh sách đánh giá</h6>
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
            </div>
            <table data-toolbar="#toolbar" data-toggle="table" class="table table-hover">
                <thead>
                    <tr>
                        <th style="">
                            <div class="th-inner sortable">ID</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Sản phẩm</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner ">Khách hàng</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Comment</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Rate</div>
                            <div class="fht-cell"></div>
                        </th>
                       
                        <th style="text-align: right">
                            <div class="th-inner ">Hành động</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_rate as $rate)
                    @if($rate->status<2)

                    <tr data-index="0">
                        <td style="">{{$rate->rate_id}}</td>
                        <td ><p> <img src="{{asset("/assets/clients/pro_img/$rate->product_image")}}" width="100">
                        </p>
                        </td>
                        <td style="">{{$rate->customer_email}}</td>
                        <td style="">{{$rate->comment}}</td>

                        <td style="">{{$rate->rating}}<i class="fa fa-star text-warning" ></td>
                        <td class="form-group" style="text-align: right; margin-right:10%">
                            <a  onclick="return confirm('Bạn có muốn xóa đánh giá này?')"  href="{{URL::to('/admin/rate-delete/'.$rate->rate_id)}}" class="btn btn-danger">
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
                        <a class="page-link" href="/admin/rate-manage/<?php echo 1; ?>" aria-label="First">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/rate-manage/<?php echo $current_page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php if ($current_page >1){
                        ?>
                    
                    <li class="page-item">
                        <a class="page-link" href="/admin/rate-manage/<?php echo $current_page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">{{$current_page - 1}}</span>
                        </a>
                    </li>
                    <?php
                    }
                    ?>
                    <li class="page-item active">
                        <a class="page-link" href="/admin/rate-manage/<?php echo $current_page; ?>"><?php echo $current_page; ?><span class="sr-only">(current)</span></a>
                    </li>
                    <?php if ($current_page < $total_pages){
                        ?>
                    
                    <li class="page-item">
                        <a class="page-link" href="/admin/rate-manage/<?php echo $current_page + 1 ; ?>" aria-label="Previous">
                            <span aria-hidden="true">{{$current_page + 1}}</span>
                        </a>
                    </li>
                    <?php
                    }
                    ?>
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/rate-manage/<?php echo $current_page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
        
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/rate-manage/<?php echo $total_pages; ?>" aria-label="Last">
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