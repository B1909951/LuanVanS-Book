<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;


Session_start();

class AdminController extends Controller
{
    public function index(Request $request){

        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $all_admin = Admin::all();
        $all_product = Product::all();
        $all_order = Order::all();
        $all_customer = Customer::all();
        $current_month = date('m'); 
        $current_year = date('Y'); 
        
        $waiting_order = Order::whereMonth('created_at', $current_month)
            ->whereYear('created_at', $current_year)
            ->where('status', 0)
            ->get();
        $shipping_order = Order::whereMonth('created_at', $current_month)
            ->whereYear('created_at', $current_year)
            ->where('status', 1)
            ->get();
        $completed_order = Order::whereMonth('created_at', $current_month)
            ->whereYear('created_at', $current_year)
            ->where('status', 2)
            ->get();
        $new_customer = Customer::whereMonth('created_at', $current_month)
        ->whereYear('created_at', $current_year)
        ->get();

        if ($request->has('year')) {
            $currentYear = $request->input('year');
            // Thực hiện xử lý với giá trị year
        } else {
            $currentYear = date('Y');
        }
        $defaultResult = array_fill(1, 12, 0); // Khởi tạo mảng với tất cả các giá trị bằng 0

        $totalByMonth = Order::
                select(DB::raw('SUM(total) as total'), DB::raw('MONTH(updated_at) as month'))
                ->whereRaw('YEAR(updated_at) = ?', [$currentYear])
                ->where('status', 2)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();
        $result = array_replace($defaultResult, $totalByMonth); // Gán giá trị mới vào mảng
         return view('admin/index')->with('admin',$admin)->with('all_admin',$all_admin)->with('all_product',$all_product)->with('all_order',$all_order)->with('all_customer',$all_customer)->with('totalByMonth',$result)->with('year', $currentYear)->with('waiting_order',$waiting_order)->with('shipping_order',$shipping_order)->with('completed_order',$completed_order)->with('new_customer',$new_customer);
    }
    public function login(){
        if(Session::get('id')){
            return Redirect::to('admin');
        }

        return view('admin/login');
    }
    public function logout(){
        Session::put('id', null);
        Session::put('name', null);
        Session::put('email', null);
        Session::put('level', null);
        Session::put('error_login', null);      
        return Redirect::to('admin-login');
    }
    public function login_test(Request $request){
        Session::put('error_login',null);

        if(!empty($_POST)){
            $email = $request->email;
            $password = $request->password;
            $result = DB::table('admins')->where('email',$email)->where('password',MD5($password))->whereIn('level', ["0", "1"])->first();
            if($result){
                Session::put('id', $result->admin_id);
                Session::put('name', $result->name);
                Session::put('email', $result->email);
                Session::put('level', $result->level);
                Session::put('error_login', null);
                return Redirect::to('admin');
        }else{
                Session::put('error_login', "Sai tài khoản hoặc mật khẩu!");
                return Redirect::back();
                }
            
        }
    }
    public function manage(){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $all_admin = Admin::where('level',1)->get();
        return view('admin/manage')->with('admin',$admin)->with('all_admin',$all_admin);
    }
    public function add(){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        return view('admin/add')->with('admin',$admin);
    }
    public function add_admin(Request $request){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admins = Admin::all();
        $admin = new Admin();
        //kiểm tra email tồn tại
        $result = DB::table('admins')->where('email',$request->email)->whereIn('level', ["0", "1"])->first();
            if($result){
                Session::put('error',"Email đã tồn tại!");
                // $this->view('users.add', compact('error'));
                return Redirect::to('admin-add');
            }
        $admin['name'] = $request->name;
        $admin['email'] = $request->email;
        $admin['phone'] = $request->phone;
        $admin['password'] = MD5($request->password);
        $admin['avatar'] = 'no';
        $get_image = $request->avatar;
        if($request->has('avatar')){
            $get_name_image = $get_image->getClientOriginalName(); 
            $name_image = current(explode('.',$get_name_image));
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension(); 
            $get_image->move(public_path('assets/admin/img/'),$new_image);
            $admin['avatar'] = $new_image;
           
        }
        Session::put('success', "Thêm admin thành công!");


        $admin->save();
        
        return Redirect::back();
    }
    public function delete($id){
        if(!Session::get('id') ){
            return Redirect::to('admin-login');
        }
        if(Session::get('level') != 0){
            Session::put('error_login', "Vui lòng đăng nhập bằng tài khoản Root để thực hiện thao tác!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::find($id);
        if($admin){
            if($admin->level == 0){
                Session::put('error', "Không thể xóa tài khoản Root!");
                return Redirect::back();
            }
            $admin['level'] = 2;
            $admin->update();
            Session::put('success', "Xóa admin thành công!");
            return Redirect::back();
        }else{
            Session::put('error', "Admin không tồn tại!");
            return Redirect::back();
        }
    }
    public function edit($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin_edit = Admin::find($id);
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        return view('admin/edit')->with('admin',$admin)->with('admin_edit',$admin_edit);
    }
    public function edit_admin(request $request){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admins = Admin::all();
        $admin = Admin::find($request->id);
        $result = DB::table('admins')->where('email',$request->email)->first();
        if($result && $request->id !=($result->admin_id)){
            Session::put('error',"Email đã tồn tại!");

            return Redirect::to('admin-edit/'.$request->id);
        }
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->avatar =  $request->now_avatar;
        $get_image = $request->avatar;
        if($request->has('avatar')){
            $get_name_image = $get_image->getClientOriginalName(); 
            $name_image = current(explode('.',$get_name_image));
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension(); 
            $get_image->move(public_path('assets/admin/img/'),$new_image);
            $admin->avatar  = $new_image;
           
        }
        Session::put('error', null);
        Session::put('success', "Cập nhật admin thành công!");
        $admin->update();
        return Redirect::to('admin-edit/'.$request->id);
    }
    public function change_password_admin($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin_edit = Admin::find($id);
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        return view('admin/change_password')->with('admin',$admin)->with('admin_edit',$admin_edit);
    }
    public function change_password(request $request){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admins = Admin::all();
        $admin = Admin::find($request->id);
        if($request->password == $request->re_password && strlen($request->password)>=6){
            $admin->password = MD5($request->password);
            $admin->update();
            Session::put('success', "Cập nhật admin thành công!");
            return Redirect::to('admin-change-password/'.$request->id);
        }
        
        Session::put('error',"Mật khẩu không hợp lệ!");

        return Redirect::to('admin-change-password/'.$request->id);
        
    }
}
