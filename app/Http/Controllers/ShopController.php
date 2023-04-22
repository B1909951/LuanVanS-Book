<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Product;
use App\Models\Genre;
use App\Models\Rate;
use App\Models\Category;
use App\Models\Product_genre;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\Product_recommend;
use App\Models\Customer_recommend;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
class ShopController extends Controller
{
    
    
    public function index(){

        $cus_id = Session::get('customer_id');
        $notif = Notification::where('customer_id',   $cus_id )->orderby('notif_id','desc')->limit(6)->get();

        $recommend_products=DB::table('cus_recommend_products')->where('customer_id',$cus_id )->join('products','products.id','=','cus_recommend_products.product_recommend_id')->select('products.*', 'products.id as pro_id')->get();
        if(count($recommend_products)==0){
            $recommend_products = Product::join('categories','categories.id','=','products.cate_id')
            ->select('products.*','products.id as product_id')
            ->where('products.show','1')
            ->where('categories.show','1')
            ->orderby('view','desc')
            ->limit(6)
            ->get();
        };
        $all_product = Product::join('categories','categories.id','=','products.cate_id')->select('products.*','products.id as product_id')->where('products.show','1')->where('categories.show','1')->orderby('product_id','desc')->limit(6)->get();
        
        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();
        $product_genres = Product_genre::All();
        return view('shop/index')->with('all_product',$all_product)->with('product_genres',$product_genres)->with('all_genre',$all_genre)->with('all_category',$all_category)->with('notif',$notif)
        ->with('recommend_products',$recommend_products)->with('current_page',1);
    }
    public function notfound(){

        $cus_id = Session::get('customer_id');
        $notif = Notification::where('customer_id',   $cus_id )->orderby('notif_id','desc')->limit(6)->get();

        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();
        $product_genres = Product_genre::All();
        return view('notfound')->with('product_genres',$product_genres)->with('all_genre',$all_genre)->with('all_category',$all_category)->with('notif',$notif);
        
    }
    public function page($page = 1){
        $cus_id = Session::get('customer_id');
        $notif = Notification::where('customer_id',   $cus_id )->orderby('notif_id','desc')->limit(6)->get();
        $per_page = 9;

        // Tính toán số trang
        $total_count = Product::join('categories', 'categories.id', '=', 'products.cate_id')
            ->where('products.show', '1')
            ->where('categories.show', '1')
            ->count();
        $total_pages = ceil($total_count / $per_page);
        if($page > $total_pages) {
            $page = $total_pages;
        }
        if($page < 1) {
            $page = 1;
        }
        // Tính vị trí bắt đầu của sản phẩm trên trang hiện tại
        $start = ($page - 1) * $per_page;

        // Lấy các sản phẩm tương ứng với trang hiện tại
        $all_product = Product::join('categories', 'categories.id', '=', 'products.cate_id')
            ->select('products.*', 'products.id as product_id')
            ->where('products.show', '1')
            ->where('categories.show', '1')
            ->orderBy('product_id', 'desc')
            ->skip($start)
            ->take($per_page)
            ->get();

        
        
        
        
        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();
        $product_genres = Product_genre::All();
        return view('shop/page')->with('all_product',$all_product)->with('product_genres',$product_genres)->with('all_genre',$all_genre)->with('all_category',$all_category)->with('notif',$notif)
        ->with('current_page',$page)->with('total_pages',$total_pages);
    }
    public function search(request $request){
        $notif = Notification::where('customer_id',Session::get('customer_id'))->orderby('notif_id','desc')->limit(6)->get();

        $all_product = Product::where('products.show','1')->get();
        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();
        $product_genres = Product_genre::All();
        $keywords = $request->keywords;
        $search_product = Product::join('categories','categories.id','=','products.cate_id')->select('products.*','products.id as product_id')->where('products.show','1')->where('categories.show','1')->where('products.name','like','%' .$keywords. '%')->select('products.id as pro_id', 'products.name', 'products.image', 'products.price')->orderby('pro_id','desc')->get();
        return view('shop/search')->with('all_product',$all_product)->with('product_genres',$product_genres)->with('all_genre',$all_genre)->with('all_category',$all_category)->with('search_product',$search_product)->with('keywords',$keywords)->with('notif',$notif);
    }
    public function product_details($id){
        $product = Product::find($id);
        if(isset($product)) {
            $product->view = $product->view + 1 ;
            $product->update();
        }
        $notif = Notification::where('customer_id',Session::get('customer_id'))->orderby('notif_id','desc')->limit(6)->get();
        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();
        $pro_genre = DB::table('product_genres')->join('genres','genres.id','=','product_genres.genre_id')->where('genres.show','1')->where('product_genres.product_id',$id)->select('genres.name', 'product_genres.genre_id')->get();
        
        $comments = DB::table('rates')->where('rates.status',1)->where('rates.product_id',$id)->join('customers','customers.customer_id','=','rates.customer_id')->select('rates.*','customers.name','customers.customer_id')->orderby('rate_id','desc')->get();


        $same_products=DB::table('pro_recommend_products')->where('product_id',$id )->join('products','products.id','=','pro_recommend_products.product_recommend_id')->select('products.id as pro_id', 'products.name', 'products.image', 'products.price')->get();
        if(count($same_products)==0){
            $same_products = DB::table('products')
            ->join('categories','categories.id','=','products.cate_id')
            ->where('categories.id',$product->cate_id)->where('products.show','1')->where('categories.show','1')->whereNotIn('products.id',[$id])->select('products.id as pro_id', 'products.name', 'products.image', 'products.price')->limit(6)->get();
        };
        // return $same_products;
        $exist_rate =  DB::table('rates')->where('status',1)->where('rates.product_id',$id)->where('rates.customer_id',Session::get('customer_id'))->first();
        if(!$exist_rate) $exist_rate=null;
        $exist_order = DB::table('details_orders')->where('details_orders.product_id',$id)->where('details_orders.customer_id',Session::get('customer_id'))->first();
        if(!$exist_order) $exist_order=null;

        return view('shop/product_details')->with('product',$product)->with('all_genre',$all_genre)->with('all_category',$all_category)->with('pro_genre',$pro_genre)->with('comments',$comments)->with('exist_rate',$exist_rate)->with('exist_order',$exist_order)->with('notif',$notif)
        ->with('same_products',$same_products);
    }
    public function cate_products($id, $page = 1){

        $cus_id = Session::get('customer_id');
        $notif = Notification::where('customer_id',   $cus_id )->orderby('notif_id','desc')->limit(6)->get();
        $per_page = 9;

        // Tính toán số trang
        $total_count = DB::table('products')
        ->where('products.cate_id',$id)
        ->where('products.show','1')
        ->select('products.id as pro_id', 'products.*')
        ->orderBy('pro_id', 'desc')
        ->count();
        $total_pages = ceil($total_count / $per_page);
        if($page > $total_pages) {
            $page = $total_pages;
        }
        if($page < 1) {
            $page = 1;
        }
        // Tính vị trí bắt đầu của sản phẩm trên trang hiện tại
        $start = ($page - 1) * $per_page;

        $cate_products = DB::table('products')
            ->where('products.cate_id',$id)
            ->where('products.show','1')
            ->select('products.id as pro_id', 'products.*')
            ->orderBy('pro_id', 'desc')
            ->skip($start)
            ->take($per_page)
            ->get();
        // Lấy các sản phẩm tương ứng với trang hiện tại
        
        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();

        $notif = Notification::where('customer_id',Session::get('customer_id'))->orderby('notif_id','desc')->limit(6)->get();

        $cate_name = Category::find($id)->name;
        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();
        $product_genres = Product_genre::All();
        
        return view('shop/cate_products')->with('all_genre',$all_genre)->with('cate_products',$cate_products)->with('all_category',$all_category)->with('cate_name',$cate_name)->with('product_genres',$product_genres)->with('notif',$notif)->with('current_page',$page)->with('total_pages',$total_pages);
    }
    
    public function genre_products($id, $page = 1){

        $cus_id = Session::get('customer_id');
        $notif = Notification::where('customer_id',   $cus_id )->orderby('notif_id','desc')->limit(6)->get();
        $per_page = 9;

        // Tính toán số trang
        $total_count = DB::table('product_genres')
        ->join('products','products.id','=','product_genres.product_id')
        ->where('product_genres.genre_id',$id)
        ->where('products.show','1')
        ->join('categories','categories.id','=','products.cate_id')
        ->select('products.*','products.id as product_id')
        ->where('categories.show','1')
        ->select('products.id as pro_id', 'products.*')

        ->count();
        $total_pages = ceil($total_count / $per_page);
        if($page > $total_pages) {
            $page = $total_pages;
        }
        if($page < 1) {
            $page = 1;
        }
        // Tính vị trí bắt đầu của sản phẩm trên trang hiện tại
        $start = ($page - 1) * $per_page;

        
        // Lấy các sản phẩm tương ứng với trang hiện tại

        $genre_name = Genre::find($id)->name;
        $notif = Notification::where('customer_id',Session::get('customer_id'))->orderby('notif_id','desc')->limit(6)->get();

        $all_category = DB::table('categories')->where('categories.show','1')->get();
        $all_genre = DB::table('genres')->where('genres.show','1')->get();
        $product_genres = Product_genre::All();
        $genre_products = DB::table('product_genres')
        ->join('products','products.id','=','product_genres.product_id')
        ->where('product_genres.genre_id',$id)
        ->where('products.show','1')
        ->join('categories','categories.id','=','products.cate_id')
        ->select('products.*','products.id as product_id')
        ->where('categories.show','1')
        ->select('products.id as pro_id', 'products.*')
        ->skip($start)
        ->take($per_page)
        ->orderBy('pro_id', 'desc')
        ->get();
        return view('shop/genre_products')->with('all_genre',$all_genre)->with('genre_products',$genre_products)->with('all_category',$all_category)->with('genre_name',$genre_name)->with('product_genres',$product_genres)->with('notif',$notif)->with('current_page',$page)->with('total_pages',$total_pages);
    }
}
