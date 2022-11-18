<?php

namespace Webkul\GoogleShoppingFeed\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\GoogleShoppingFeed\Helpers\GoogleShoppingContentApi;
use Webkul\Product\Repositories\ProductRepository;

class ProductController extends Controller
{

    /**
     * ProductRepository object
     *
     * @return Object
     */
    protected $productRepository;

    /**
     * Holds object of GoogleShoppingContentApi
     */
    protected $googleShoppingContentApi;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    public function __construct(
        GoogleShoppingContentApi $googleShoppingContentApi,
        ProductRepository $productRepository
    )
    {
        $this->_config = request('_config');
        $this->middleware('admin');
        $this->productRepository = $productRepository;
        $this->googleShoppingContentApi = $googleShoppingContentApi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd( $this->googleShoppingContentApi->getProducts());
        return view($this->_config['view']);
    }

    /**
     * Show products uploaded on goolge shop.
     *
     * @return \Illuminate\Http\Response
     */
    public function exported()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Exports products to google shop
     */
    public function  export ()
    {
		//echo 'hello';die();
       $products = $this->productRepository->getAllShoppingProduct();
		//print_r($products);die();
     
       $accessToken = $this->googleShoppingContentApi->getAccessToken();

       if (is_null($accessToken)) {
            // session()->flash('warning', ));

         return response()->json(__('googleFeed::app.admin.product.refresh'), 401);

        }

        try {

            foreach ($products as $product) {
		
	  						
				$a = $this->googleShoppingContentApi->exportProduct($product);
					print_r($a); die();
                session()->flash('success', );
					
           }
			//die();
           return response()->json(__('product exported successfully'), 200);

        } catch (\Exception $e) {
           // return response()->json(__('googleFeed::app.admin.product.wrong', 500));
		   session()->flash('error', );
		   return response()->json(__('Something Went Wrong!!'), 200);
        }



    }
}
