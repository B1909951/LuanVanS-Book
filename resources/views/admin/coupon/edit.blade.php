@extends('admin.layouts.main')
@section('title', 'Admin | Sửa thể loại')
@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="col-md-8">
                    <h3 class="title text-left">Cập nhật coupon</h3>

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
                    <form action="{{URL::to('/admin/coupon-edit-coupon')}}" role="form" method="post" >
                        @csrf
                        

                        
                       
                        <input id="id" name="id" type="hidden"  placeholder="" value="{{$coupon_edit->coupon_id}}">
                        <div class="form-group">
                            <label>Mã Coupon</label>
                            <input name="code" class="form-control" placeholder="" value="{{$coupon_edit->code}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Phần trăm giảm</label>
                            <input name="value" min="0" max="100" type="number" class="form-control" value="{{$coupon_edit->value}}" required> 
                        </div>
                        <div class="form-group">
                            <label>Mô tả ngắn</label>

                            <textarea name="desc" class="form-control" placeholder=""
                                id="floatingTextarea" style="height: 50px;">{{$coupon_edit->desc}}</textarea>
                            <span class="form-message"></span>

                        </div>
                        <div class="form-group mb-3">
                            <label>Ngày hết hạn</label>
                            <input name="expire_at" class="form-control" type="date" value="{{$coupon_edit->expire_at}}" required>
                            <span class="form-message"></span>
                        </div>
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                        <a href="{{URL::to('/admin/coupon-manage')}}" class="btn btn-danger    ">
                            Trở về</i>
                        </a> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection