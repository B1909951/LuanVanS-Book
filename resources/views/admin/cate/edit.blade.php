@extends('admin.layouts.main')
@section('title', 'Admin | Sửa danh mục')
@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="col-md-8">
                    <h3 class="title text-left">Cập nhật danh mục</h3>

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
                    <form action="{{URL::to('/admin/cate-edit-cate')}}" role="form" method="post" >
                        @csrf
                        <input id="id" name="id" type="hidden"  placeholder="" value="{{$cate_edit->id}}">
                        <div class="form-group">
                            <label>Tên thể loại</label>
                            <input name="name" class="form-control" placeholder="" value="{{$cate_edit->name}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Mô tả</label>
                            <input name="desc" type="text" class="form-control" value="{{$cate_edit->desc}}" required> 
                        </div>
                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-2 pt-0">Hiển thị</legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input1" type="radio" name="show"
                                        id="gridRadios1" value=0 @if(!$cate_edit->show) checked @endif>
                                    <label class="form-check-label" for="gridRadios1">
                                        Không
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input1" type="radio" name="show"
                                        id="gridRadios2" value=1 @if($cate_edit->show) checked @endif>
                                    <label class="form-check-label" for="gridRadios2">
                                        Có
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        
                        
                        <button name="sbm" type="submit" class="btn btn-success">Cập nhật</button>
                        
                        <a href="{{URL::to('/admin/cate-manage')}}" class="btn btn-danger    ">
                            Trở về</i>
                        </a> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection