@extends('admin.layouts.main')
@section('title', 'Admin | Quản lý danh mục')
@section('content')
<div class="container-fluid pt-4 px-4">
<div class="row g-4">
<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4 fs-3">Danh sách danh mục</h6>
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
                <a href="{{URL::to('/admin/cate-add')}}" class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> Thêm danh mục
                </a>
            </div>
            <table data-toolbar="#toolbar" data-toggle="table" class="table table-hover">
                <thead>
                    <tr>
                        <th style="">
                            <div class="th-inner sortable">ID</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Tên danh mục</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner ">Mô tả</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">Hiển thị</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="text-align: right">
                            <div class="th-inner ">Hành động</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_cate as $cate)
                    @if($cate->show<2)

                    <tr data-index="0">
                        <td style="">{{$cate->id}}</td>
                        <td style="">{{$cate->name}}</td>
                        <td style="">{{$cate->desc}}</td>
                        <td style="">@if($cate->show==1)Có@else Không @endif</td>
                        <td class="form-group" style="text-align: right; margin-right:10%">
                            <a href="{{URL::to('/admin/cate-edit/'.$cate->id)}}" class="btn btn-primary">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a  onclick="return confirm('Bạn có muốn xóa danh mục này?')"  href="{{URL::to('/admin/cate-delete/'.$cate->id)}}" class="btn btn-danger">
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

@endsection