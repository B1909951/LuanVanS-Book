@extends('admin.layouts.main')
@section('title', 'Admin | Quản lý Coupon')
@section('content')
<div class="container-fluid pt-4 px-4">
<div class="row g-4">
<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4 fs-3">Danh sách Coupon</h6>
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
                <a href="{{URL::to('/admin/coupon-add')}}" class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> Thêm Coupon
                </a>
            </div>
            <!-- Spinner Start -->
            <div id="spinner-recommend" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center d-none loading_create_list" style="z-index: 10000; opacity: 0.8;">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only" ">Loading...</span>
                </div>
                <br>
                <span style="color: black; padding-left:10px">Đang gửi coupon đến khách hàng</span>

            </div>
        <!-- Spinner End -->
            <div id="toolbar" class="btn-group" >
                <a id="create_list" href="{{URL::to('/admin/coupon/send-coupon')}}"><button class="btn btn-primary" ><i class="glyphicon glyphicon-mail" ></i>Gửi coupon</button></a>
            </div>
            <table data-toolbar="#toolbar" data-toggle="table" class="table table-hover">
                <thead>
                    <tr>
                        <th style="">
                            <div class="th-inner sortable" width = 10%>ID</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="" width = 20%>
                            <div class="th-inner sortable">Mã Coupon</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner ">Value</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable" width = 30%>Mô tả</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Ngày hết hạn</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="text-align: right">
                            <div class="th-inner ">Hành động</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_coupon as $coupon)
                    @if($coupon->status<2)

                    <tr data-index="0">
                        <td style="">{{$coupon->coupon_id}}</td>
                        <td style="">{{$coupon->code}}</td>
                        <td style="">{{$coupon->value}}%</td>
                        <td style="">{{$coupon->desc}}</td>

                        @if($coupon->expire_at >= date('Y-m-d'))<td style="color: green">{{$coupon->expire_at}}</td> @else <td style="color: red">{{$coupon->expire_at}}</td> @endif
                        <td class="form-group" style="text-align: right; margin-right:10%">
                            <a href="{{URL::to('/admin/coupon-edit/'.$coupon->coupon_id)}}" class="btn btn-primary">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a  onclick="return confirm('Bạn có muốn xóa coupon này?')"  href="{{URL::to('/admin/coupon-delete/'.$coupon->coupon_id)}}" class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td> 
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

{{-- <div class="container-fluid pt-4 px-4">
    <div class="row g-4">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4 fs-3">Danh sách Coupon đã xóa</h6>
            <div class="table-responsive">
                <table data-toolbar="#toolbar" data-toggle="table" class="table table-hover">
                    <thead>
                        <tr>
                            <th style="">
                                <div class="th-inner sortable" width = 10%>ID</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style=""  width = 20%>
                                <div class="th-inner sortable">Mã Coupon</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner ">Value</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable" width = 30%>Mô tả</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Ngày hết hạn</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner ">Hành động</div>
                                <div class="fht-cell"></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_coupon as $coupon)
                        @if($coupon->status==2)
    
                        <tr data-index="0">
                            <td style="">{{$coupon->coupon_id}}</td>
                            <td style="">{{$coupon->code}}</td>
                            <td style="">{{$coupon->value}}%</td>
                            <td style="">{{$coupon->desc}}</td>
    
                            @if($coupon->expire_at >= date('Y-m-d'))<td style="color: green">{{$coupon->expire_at}}</td> @else <td style="color: red">{{$coupon->expire_at}}</td> @endif
                            <td class="form-group" style="">
                                <a href="{{URL::to('/admin/coupon-edit/'.$coupon->coupon_id)}}" class="btn btn-primary">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a  onclick="return confirm('Bạn có muốn xóa coupon này?')"  href="{{URL::to('/admin/coupon-delete/'.$coupon->coupon_id)}}" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div> --}}
@endsection