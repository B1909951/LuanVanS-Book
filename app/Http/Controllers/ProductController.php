<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Genre;
use App\Models\Product_genre;
use App\Models\Category;
use App\Models\Product_recommend;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProductController extends Controller
{
    public function create_products1(){
    //tạo file dữ liệu danh sách sản phẩm
    $all_product = Product::where('show','1')->get();
    $data = "Product_ID;Name;Info";
    $data .= "\r\n";
    foreach($all_product as $pro){
        $genres = Product_genre::where('product_id',$pro->id)->get();
        $data .=$pro->id;
        $data .=";";
        $data .=$pro->name;
        $data .=";";
        $data .=$pro->author;
        $data .="|";

        foreach($genres as $genre){
            $data .= $genre->name;
            $data .="|";
        }
        $cate_name = Category::where('id',$pro->cate_id)->first()->name;
        $data .= $cate_name;

        $data .= "\r\n";

    }
    Storage::disk('local')->put('python/products1.txt', $data);
    }
    public function create_products2(){
        //tạo file dữ liệu danh sách sản phẩm
        $all_product = Product::where('show','1')->get();
        $data = "id;description";
        $data .= "\r\n";
        foreach($all_product as $pro){
            $genres = Product_genre::where('product_id',$pro->id)->get();
            $data .=$pro->id;
            $data .=";";
            $data .=$pro->name;
            $data .=", ";
            $desc = str_replace( array( ';', '<', '>','"', "\r\n", "\n", "\r" ), ' ', $pro->desc);
            $data .= str_replace( array( '  ' ), ' ', $desc);
            $data .=", ";
            $cate_name = Category::where('id',$pro->cate_id)->first()->name;
            $data .= $cate_name;
            $data .=", ";
            foreach($genres as $genre){
                $data .= $genre->name;
                $data .=", ";
            }
            $data .=$pro->author;
            $data .= "\r\n";
        }
        Storage::disk('local')->put('python/products2.txt', $data);
    }
    public function same_products($pro_id){

        $process = new Process(["python3", 'D:\Programs\xampp2\htdocs\S-Book\storage\app\python\code2.py', $pro_id], env: [
            'SYSTEMROOT' => getenv('SYSTEMROOT'),
            'PATH' => getenv("PATH")
          ]);
          
        $process->run();
        $output_data = $process->getOutput();
        // echo "<pre>";
        // print_r($output_data); 
        // echo "</pre>";
        // return 1;
        $arr = explode(" ",$output_data);
        // echo "<pre>";
        // print_r($arr); 
        // echo "</pre>";
        foreach($arr as $key=>$value){
            $arr[$key] = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        }
        $same_products_id=array();
        for($i=0; $i<count($arr) && $i<6; $i++) { 
            $same_products_id[] = $arr[$i];
            
        }
        // echo "<pre>";
        // print_r($same_products_id); 
        // echo "</pre>";
        return $same_products_id;

    }
    public function manage(){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $all_product = Product::orderby('id','desc')->get();
        $pro_genres = Product_genre::all();
        $categories = Category::all();
        return view('admin/product/manage')->with('admin',$admin)->with('categories',$categories)->with('all_product',$all_product)->with('pro_genres',$pro_genres);
    }

    public function add(){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $categories = Category::all();
        $all_genre = Genre::all();
        return view('admin/product/add')->with('admin',$admin)->with('all_genre',$all_genre)->with('categories',$categories);
    }
    public function add_product(Request $request){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $product = new Product();
        $result = DB::table('products')->where('name',$request->name)->first();
            if($result){
                Session::put('error',"Sản phẩm đã tồn tại!");
                return Redirect::to('admin/product-add');
            }
        $product['name'] = $request->name;
        $product['author'] = $request->author;
        $product['price'] = $request->price;
        $product['cate_id'] = $request->cate;
        $product['desc'] = $request->desc;
        $product['detail'] = $request->detail;
        $product['image'] = 'no';
        $get_image = $request->image;
        if($request->has('image')){
            $get_name_image = $get_image->getClientOriginalName(); 
            $name_image = current(explode('.',$get_name_image));
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension(); 
            $get_image->move(public_path('assets/clients/pro_img/'),$new_image);
            $product['image'] = $new_image;
        }
        Session::put('error',null);
        $product->save();
        $pro_id = $product->id;
        $genres = $request->genres;
        if(!empty($genres)){
            $N = count($genres);
            for($i=0; $i < $N; $i++){
                $genre = Genre::find($genres[$i]);
                $product_genre = new Product_genre();
                $product_genre['product_id'] = $pro_id;
                $product_genre['genre_id'] = $genre->id;
                $product_genre['name'] = $genre->name;
                $product_genre->save();
            }
        }
        
        $this->create_products1();
        $this->create_products2();
        $arr_id = $this->same_products($pro_id);
        foreach($arr_id as $item){
            $pro_recommend = new Product_recommend();
            $pro_recommend['product_id']=$pro_id;
            $pro_recommend['product_recommend_id']=$item;
            $pro_recommend->save();
        }
        return redirect()->back()->with('success', 'Thêm sản phẩm thành công');
    }
    public function delete($id){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $product = Product::find($id);
        if($product){
            $product['show']=2;
            $product->update();
        }
        $this->create_products1();
        $this->create_products2();
        return Redirect::to('admin/product-manage');
    }
    public function recover($id){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $product = Product::find($id);
        if($product){
            $product['show']=1;
            $product->update();
        }
        $this->create_products1();
        $this->create_products2();
        return Redirect::to('admin/product-manage');
    }
    public function deletedb($id){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $product = Product::find($id);
        if($product){
            $product->delete();
        }
        $this->create_products1();
        $this->create_products2();
        return Redirect::to('admin/product-manage');
    }
    public function edit($id){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $product_edit = Product::find($id);
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $all_genre = Genre::all();
        $categories = Category::all();

        $pro_genres = Product_genre::all();        

        return view('admin/product/edit')->with('admin',$admin)->with('product_edit',$product_edit)->with('all_genre',$all_genre)->with('pro_genres',$pro_genres)->with('categories',$categories);
    }
    public function edit_product(request $request){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $product = Product::find($request->id);
        $result = DB::table('products')->where('name',$request->name)->first();
            if($result && $request->id !=($result->id)){
                Session::put('error',"Sản phẩm đã tồn tại!");
                return Redirect::to('admin/product-edit/'.$request->id);
            }
        $product['name'] = $request->name;
        $product['price'] = $request->price;
        $product['author'] = $request->author;

        $product['cate_id'] = $request->cate;

        $product['desc'] = $request->desc;
        
        $product['detail'] = $request->detail;
        $product['image'] =$request->now_image;
        $get_image = $request->image;
        if($request->has('image')){
            $get_name_image = $get_image->getClientOriginalName(); 
            $name_image = current(explode('.',$get_name_image));
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension(); 
            $get_image->move(public_path('assets/clients/pro_img/'),$new_image);
            $product['image'] = $new_image;
        }
        Session::put('error',null);
        $product->save();
        $pro_id = $product->id;
        $genres = $request->genres;
        $product_genres =  DB::table('product_genres')->where('product_id',$pro_id)->get();
        foreach($product_genres as $product_genre){
            $product_genre = Product_genre::find($product_genre->id);
            $product_genre->delete();
        }
        if(!empty($genres)){
            $N = count($genres);
            for($i=0; $i < $N; $i++){
                $genre = Genre::find($genres[$i]);
                $product_genre = new Product_genre();
                $product_genre['product_id'] = $pro_id;
                $product_genre['genre_id'] = $genre->id;
                $product_genre['name'] = $genre->name;
                $product_genre->save();
            }
        }
        
        $this->create_products1();
        $this->create_products2();
        session::put('success',"Đã cập nhật sản phẩm!");
        return redirect()->back();
    }
    public function add_product_recommend(){
        ini_set('max_execution_time', 6000);
        Product_recommend::query()->delete();
        $all_product = Product::all();
 

        foreach($all_product as $pro){
            
            $arr_id = $this->same_products($pro->id);

            foreach($arr_id as $id){
                $pro_recommend = new Product_recommend();
                $pro_recommend['product_id']=$pro->id;
                $pro_recommend['product_recommend_id']=$id;
                $pro_recommend->save();
            }
        }
        
        return Redirect::to('admin/product-manage')->with('msg', 'Đã làm mới danh sách gợi ý của từng sản phẩm.');

        

    }
    public function edit_recommend($id){
        Product_recommend::where('product_id', $id)->delete();
        $arr_id = $this->same_products($id);
        foreach($arr_id as $item){
            $pro_recommend = new Product_recommend();
            $pro_recommend['product_id']=$id;
            $pro_recommend['product_recommend_id']=$item;
            $pro_recommend->save();
        }
        Session::put('success',"Đã làm mới danh sách gợi ý của sản phẩm!");
        return redirect()->back();
    }

}
