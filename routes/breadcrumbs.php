<?php
use App\Models\Category\Category;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use App\Http\Controllers\CategoryController;
 
// Home
Breadcrumbs::for('home', function ($trail) {
$trail->push('Home', route('shop.home.index'));
});
 
// Category
/*Breadcrumbs::for('category', function ($trail,$category){
$trail->parent('home');
$trail->push($category->name, route('shop.productOrCategory.index', $category->url_path));
});*/

Breadcrumbs::for('category', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('home');
	$arr = explode('/',$category->url_path);
    $length = count($arr);
    for($i=0;$i<$length;$i++){
	$cat = \DB::table('category_translations')->where('slug', '=', $arr[$i])->first();
	$trail->push($cat->name, route('shop.productOrCategory.index', $cat->url_path));		
	}
});


Breadcrumbs::for('product', function (BreadcrumbTrail $trail, Product $product) {
    $trail->parent('home');
	
	$cat = \DB::table('product_flat')->where('url_key', '=', $product->url_key)->first();
	$trail->push($product->name, route('shop.productOrCategory.index', $product->url_key));		
	
});