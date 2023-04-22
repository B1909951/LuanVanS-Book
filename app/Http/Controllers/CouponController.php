<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Redirect;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Genre;
use App\Models\Product_genre;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customer;
use App\Mail\SendCoupon;
class CouponController extends Controller
{
    public function check(Request $request)
    {
        $coupon = DB::table('coupons')->where('coupons.code',$request->code)->where('coupons.status', 1)->first();
        return Redirect::to('cart')->with('coupon',$coupon);
    }
    public function manage(){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $all_coupon = Coupon::all();
        return view('admin/coupon/manage')->with('admin',$admin)->with('all_coupon',$all_coupon);
    }
    public function add(){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        return view('admin/coupon/add')->with('admin',$admin);
    }
    public function add_coupon(Request $request){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $coupon = new Coupon();
        $result = DB::table('coupons')->where('code',$request->code)->where('status', 1)->first();
            if($result){
                Session::put('error',"Coupon đã tồn tại!");
                return Redirect::to('admin/coupon-add');
            }
        $coupon['code'] = $request->code;
        $coupon['value'] = $request->value;
        $coupon['desc'] = $request->desc;
        $coupon['expire_at'] = $request->expire_at;
        Session::put('success',"Thêm coupon thành công!");
        $coupon->save();
        
        return Redirect::back();
    }
    public function delete($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $coupon = Coupon::find($id);
        if($coupon){
            $coupon['status'] = 2;
            $coupon->update();
        }
        Session::put('success',"Xóa coupon thành công!");
        return Redirect::to('admin/coupon-manage');
    }
    
    public function edit($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $coupon_edit = Coupon::find($id);
        $admin = Admin::where('admin_id',Session::get('id'))->get();

        return view('admin/coupon/edit')->with('admin',$admin)->with('coupon_edit',$coupon_edit);
    }
    public function edit_coupon(request $request){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $coupon = Coupon::find($request->id);
        $result = DB::table('coupons')->where('code',$request->code)->where('status',1)->first();
            if($result && ($request->id !=($result->coupon_id))) {
                Session::put('error',"Coupon đã tồn tại!");
                return Redirect::to('admin/coupon-edit/'.$request->id);
            }
        $coupon['code'] = $request->code;
        $coupon['value'] = $request->value;
        $coupon['desc'] = $request->desc;
        $coupon['expire_at'] = $request->expire_at;
        Session::put('error',null);
        $coupon->update();
        Session::put('success',"Cập nhật coupon thành công!");

        return Redirect::back();
    }
    public function send_coupon(){
        ini_set('max_execution_time', 60000);
        $all_coupon = Coupon::where('status',1)->Where('expire_at', '>=', date('Y-m-d'))->get();
        $list_customer = Customer::where('status',1)->get();
        foreach($list_customer as $cus){
            Mail::to($cus->email)->send(new SendCoupon($cus, $all_coupon));
        }
        Session::put('success',"Đã gửi coupon đến khách hàng!");
        return redirect()->back();
    }    

}
