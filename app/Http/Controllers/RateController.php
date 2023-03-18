<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ShopController;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Rate;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Customer_recommend;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
class RateController extends Controller
{
    public function recommend_products(){

        
        $process = new Process(["python3", 'D:\Programs\xampp2\htdocs\S-Book\storage\app\python\code1.py'], env: [
            'SYSTEMROOT' => getenv('SYSTEMROOT'),
            'PATH' => getenv("PATH")
          ]);
          
        $process->run();
        $output_data = $process->getOutput();
        $arr = explode(" ",$output_data);
        foreach($arr as $key=>$value){
            $arr[$key] = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        }
        $rec_pro_id_for_cus=array();
        for($i=0; $i<count($arr) && $i<6; $i++) {
            $rec_pro_id_for_cus[] = $arr[$i];
        }
        // echo "<pre>";
        // print_r($rec_pro_id_for_cus); 
        // echo "</pre>";
        return $rec_pro_id_for_cus;

    }
    public function create_cus_rating(){
        //tạo file dữ liệu danh sách đánh giá của khách hàng hiện tại
        $cus_id = Session::get('customer_id');
        if( $cus_id==null){
            return null;
        }
        $u_rating = Rate::where('customer_id',Session::get('customer_id'))->get();
        if(count($u_rating) == 0){
            return null;
        }
        $data = "Product_ID;Rating";
        $data .= "\r\n";
        
        foreach($u_rating as $rating){
            $data .= $rating->product_id;
            $data .=";";
            $data .= $rating->rating;
            $data .= "\r\n";
        }
        Storage::disk('local')->put('python/user_ratings.txt', $data);
        return 1;
    }
    public function add_rate(request $request){
        $cus_id = Session::get('customer_id');

        $update_rate = DB::table('rates')
        ->where('rates.customer_id',$request->customer_id)->where('rates.product_id',$request->product_id)->first();
        if($update_rate){
            $rate = Rate::find($update_rate->rate_id);
            $data=$_POST;
            $rate->fill($data);

            $rate->update();

            if($this->create_cus_rating()!=null){
                Customer_recommend::where('customer_id',$cus_id)->delete();
                $arr_id = $this->recommend_products();
                foreach ($arr_id as $id){
                    $cus_recommend = new Customer_recommend();
                    $cus_recommend['customer_id']= $cus_id;
                    $cus_recommend['product_recommend_id']= $id;
                    $cus_recommend->save();
                }
            }

            $product = DB::table('rates')->where('rates.product_id',$request->product_id)->get();
            $total = 0;
            $n = 0;
            foreach($product as $pro) {
                $total+=$pro->rating;
                $n++;
            }
            $product_rating = Product::find($request->product_id);
            $product_rating->star = $total/$n;
            $product_rating->update();
            return Redirect()->back();
        }
        $rate = new Rate();
        $data=$_POST;
        $rate->fill($data);

        $rate->save();
        if($this->create_cus_rating()!=null){
            Customer_recommend::where('customer_id',$cus_id)->delete();
            $arr_id = $this->recommend_products();
            foreach ($arr_id as $id){
                $cus_recommend = new Customer_recommend();
                $cus_recommend['customer_id']= $cus_id;
                $cus_recommend['product_recommend_id']= $id;
                $cus_recommend->save();
            }
        }
        $product = DB::table('rates')
        ->where('rates.product_id',$request->product_id)->get();
        $total = 0;
        $n = 0;
        foreach($product as $pro) {
            $total+=$pro->rating;
            $n++;
        }
        $product_rating = Product::find($request->product_id);
        $product_rating->star = $total/$n;
        $product_rating->update();
        return Redirect()->back();
    }
    //admin
    public function manage(){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $admin = Admin::where('admin_id',Session::get('id'))->get();
        $all_rate = Rate::join('customers','customers.customer_id','=','rates.customer_id')->join('products','products.id','=','rates.product_id')->select('rates.*','customers.email as customer_email','products.name as product_name', 'products.image as product_image')->orderby('rate_id','desc')->get();
        return view('admin/rate/manage')->with('admin',$admin)->with('all_rate',$all_rate);
    }
    public function delete($id){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $rate = rate::find($id);
        if($rate){
            $rate['status'] = 2;
            $rate->update();
        }
        
        return Redirect::to('admin/rate-manage');
    }
    public function recover($id){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $rate = rate::find($id);
        if($rate){
            $rate['status'] = 1;
            $rate->update();
        }
        return Redirect::to('admin/rate-manage');
    }
    public function deletedb($id){
        if(!Session::get('id')){
            return Redirect::to('admin-login');
        }
        $rate = Rate::find($id);
        if($rate){
            $rate->delete();
        }
        return Redirect::to('admin/rate-manage');
    }
}
