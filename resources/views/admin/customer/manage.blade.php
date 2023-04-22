@extends('admin.layouts.main')
@section('title', 'Admin | Quản lý khách hàng')
@section('content')
<div class="container-fluid pt-4 px-4">
<div class="row g-4">
<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4 fs-3">Danh sách khách hàng</h6>
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
                            <div class="th-inner sortable">Tên khách hàng</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner ">Email</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Phone</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Địa chỉ</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Trạng thái</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="text-align: right">
                            <div class="th-inner ">Hành động</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_customer as $customer)
                    @if($customer->status<2)

                    <tr data-index="0">
                        <td style="">{{$customer->customer_id}}</td>
                        <td style="">{{$customer->name}}</td>
                        <td style="">{{$customer->email}}</td>
                        <td style="">{{$customer->phone}}</td>

                        <td style="">{{$customer->address}}</td>
                        <td style="">@if($customer->status==1)Tự do @else Hạn chế @endif</td>
                        <td class="form-group" style="text-align: right; margin-right:10%">
                            <a  onclick="return confirm('Bạn có muốn khóa tài khoản này?')"  href="{{URL::to('/admin/customer-delete/'.$customer->customer_id)}}" class="btn btn-danger">
                                <i class="fa fa-lock"></i>
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

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4 fs-3">Danh sách tài khoản đã khóa</h6>
            <div class="table-responsive">
                <table data-toolbar="#toolbar" data-toggle="table" class="table table-hover">
                    <thead>
                        <tr>
                            <th style="">
                                <div class="th-inner sortable">ID</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Tên khách hàng</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner ">Email</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Phone</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Địa chỉ</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="">
                                <div class="th-inner sortable">Trạng thái</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th style="text-align: right">
                                <div class="th-inner " >Hành động</div>
                                <div class="fht-cell"></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_customer as $customer)
                    @if($customer->status>1)

                    <tr data-index="0">
                        <td style="">{{$customer->customer_id}}</td>
                        <td style="">{{$customer->name}}</td>
                        <td style="">{{$customer->email}}</td>
                        <td style="">{{$customer->phone}}</td>

                        <td style="">{{$customer->address}}</td>
                        <td style="">@if($customer->status==1)Tự do @else Hạn chế @endif</td>
                        <td class="form-group" style="text-align: right; margin-right:10%" >
                            <a onclick="return confirm('Bạn có muốn bỏ khóa khách hàng này?')"  href="{{URL::to('/admin/customer-recover/'.$customer->customer_id)}}" class="btn btn-primary">
                                <i class="fa fa-unlock"></i>
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
@endsection