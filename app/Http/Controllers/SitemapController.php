<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use DB;
  
class SitemapController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index($value='')
    {
	//	echo 'testing';die();
        //$posts = Post::latest()->get();
		$categorys = DB::table('category_translations')->get(); //url_path
		$products = DB::table('product_flat')->get(); //url_key
		
		
		
        return response()->view('sitemap', [ 'categorys' => $categorys ])->header('Content-Type', 'text/xml');
    }
}