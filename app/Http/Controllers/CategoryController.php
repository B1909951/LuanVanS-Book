<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Admin;
use App\Models\Category;

class CategoryController extends Controller
{
    public function manage(){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $all_cate = Category::all();
        return view('admin/cate/manage')->with('admin',$admin)->with('all_cate',$all_cate);
    }
    public function add(){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        return view('admin/cate/add')->with('admin',$admin);
    }
    public function add_cate(Request $request){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $cate = new Category();
        $result = DB::table('categories')->where('name',$request->name)->whereIn('show', ["0", "1"])
        ->first();
            if($result){
                
                Session::put('error',"Danh mục đã tồn tại!");
                return Redirect::to('admin/cate-add');
            }
        $cate['name'] = $request->name;
        $cate['desc'] = $request->desc;
        $cate->save();
        Session::put('success',"Thêm danh mục thành công!");
        return redirect()->back();
    }
    public function delete($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $cate = Category::find($id);
        if($cate){
            $cate['show'] = 2;
            $cate->update();
            Session::put('success',"Xóa danh mục thành công!");

        }else{
            Session::put('error',"Danh mục không tồn tại!");

        }
        return Redirect::to('admin/cate-manage');
    }
    public function recover($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $cate = Category::find($id);
        if($cate){
            $cate['show'] = 1;
            $cate->update();
        }
        return Redirect::to('admin/cate-manage');
    }
    public function deletedb($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $cate = Category::find($id);
        if($cate){
            $cate->delete();
        }
        return Redirect::to('admin/cate-manage');
    }
    public function edit($id){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $cate_edit = Category::find($id);
        $admin = Admin::where('admin_id',Session::get('id'))->get();

        return view('admin/cate/edit')->with('admin',$admin)->with('cate_edit',$cate_edit);
    }
    public function edit_cate(request $request){
        if(!Session::get('id') ){
            Session::put('error_login', "Vui lòng đăng nhập để thực hiện chức năng!");
            return Redirect::to('admin-login');
        }
        $cate = Category::find($request->id);
        $result = DB::table('categories')->where('name',$request->name)->whereIn('show', ["0", "1"])->select('categories.id')->first();
            if($result && ($request->id !=($result->id))) {
                Session::put('error',"Danh mục đã tồn tại!");
                return Redirect::to('admin/cate-edit/'.$request->id);
            }
        $cate['name'] = $request->name;
        $cate['desc'] = $request->desc;
        $cate['show'] = $request->show;
        $cate->update();
        Session::put('success',"Cập nhật danh mục thành công!");
        return redirect()->back();
    }

}
