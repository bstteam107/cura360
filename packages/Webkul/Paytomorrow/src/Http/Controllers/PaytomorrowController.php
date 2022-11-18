<?php

namespace Webkul\Paytomorrow\Http\Controllers;
use App\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use App\Helpers\Helper;
use DB;

/**
 * MpAuthorizeNetConnectController Controller
 *
 * @author  shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PaytomorrowController extends Controller
{   
     /**
     * Cart object
     *
     * @var array
     */
    protected $cart;

     /**
     * Order object
     *
     * @var array
     */
    protected $order;

    /**
     * Helper object
     *
     * @var array
     */
    protected $helper;
    

    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;
  


    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * 
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        Helper $helper
    )
    {
        
        $this->orderRepository = $orderRepository;

        $this->helper = $helper;

        $this->cart = Cart::getCart();

    }

    public function getToken()
    {  
        try {
				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://api.paytomorrow.com/api/uaa/oauth/token',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS => 'grant_type=password&scope=openid&username=api5212%40paytomorrow.com&password=t%24P4m%24U2w',
				  CURLOPT_HTTPHEADER => array(
					'Authorization: Basic NTIxMjo2MjBiODdjYi1jMjliLTQ0MTktYjZmMi0yYzlmNzljZGM2ZTE=',
					'Content-Type: application/x-www-form-urlencoded',
					'Accept: application/json'
				  ),
				));

				$response = curl_exec($curl);
				//print_r($response);die();
				curl_close($curl);
				$res = json_decode($response);
				return $res->access_token;
 
        } catch (\Exception $e) {
            session()->flash('error','Paytomorrow Something Went Wrong');
            
            return redirect()->route('shop.checkout.cart.index'); 
        }
        
    }

    public function createCharge()
    {   
        try {
           $order = session()->get('order');
		  // echo  $order->increment_id.'<br>';
		  // print_r($order);die();
		   
		   
		   
			$cart = Cart::getCart();
			// print_r($cart->items );
			$ctotal = 0;
			foreach ($cart->items as $key => $item){
			
			$product = $item->product;	
			$options = DB::table('cart_item_options')->where('product_id', $product->id)->where('cart_id', $cart->id)->get();	
			if (!empty($options) && $options->count() > 0){
				foreach ($options as $option){
					$ctotal = $ctotal + $option->price;
				}
				$customtotal = $ctotal;} else{ $customtotal = 0; }
				$item->price = $customtotal + $item->price;
				$item->base_price = $customtotal + $item->base_price;
				$item->base_price_total = $item->quantity * ($customtotal + $item->base_price);
				$item->total = $item->total + ($customtotal * $item->quantity);
				$item->base_total = $item->base_total + ($customtotal * $item->quantity);
				
				$grand_total = $cart->grand_total + ($item->quantity * $customtotal);
				$base_grand_total = $cart->base_grand_total + ($item->quantity * $customtotal);
				$sub_total = $cart->sub_total + ($item->quantity * $customtotal);
				$base_sub_total = $cart->base_sub_total + ($item->quantity * $customtotal);
				$cartitemdata = array(
							'price'        => $item->price,
							'base_price'        => $item->base_price,
							'total'             => $item->total,
							'base_total'        => $item->base_total,
					);
				DB::table('cart_items')->where(['cart_id'=>$cart->id,'product_id'=>$product->id])->limit(1)->update($cartitemdata);
				
			$productname = $item->name;		
			$productprice = $item->price;		
			$productquantity = $item->quantity;		
			}
			$total = $cart->grand_total = $grand_total;
			$token = $this->getToken();
			$customerEmail = Cart::getCart()->billing_address->email;
			$customerFirstname = Cart::getCart()->billing_address->first_name;
			$customerLasttname = Cart::getCart()->billing_address->last_name;
			$customerCity = Cart::getCart()->billing_address->city;
			$customerState = Cart::getCart()->billing_address->state;
			$customerCountry = Cart::getCart()->billing_address->country;
			$customeraddress = Cart::getCart()->billing_address->address1;
			$customercode = Cart::getCart()->billing_address->postcode;
			$customerphone = Cart::getCart()->billing_address->phone;
			$orderid = $order->increment_id;
			$productname;
			$productprice;
			$productquantity;
			$csrftoken = csrf_token();
		$curl = curl_init();
		$fields = [
            'orderId' => $orderid,
            'firstName' => $customerFirstname,
            'lastName' => $customerLasttname,
            'street' => $customeraddress,
            'city' => $customerCity,
            'zip' => $customercode,
            'state' => $customerState,
            'email' => $customerEmail,
            'returnUrl' => "https://cura360.com/checkout/paytomorrow/success?id=".$orderid,
            'cancelUrl' => "https://cura360.com/checkout/onepage",
            'notifyUrl' => "https://cura360.com/notify",
            'cellPhone' => $customerphone,
            'loanAmount' => $total,
            'shipping' => 10,
            'applicationItems' => array( [
                'description' => $productname,
				'quantity' => $productquantity,
				'price' => $productprice,
				'brand' => "",
				'model' => ""
           ] )
        ];
		//print_r($fields);
		$postfields = json_encode($fields);
		  curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.paytomorrow.com/api/application/ecommerce/orders',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $postfields,
		  CURLOPT_HTTPHEADER => array(
			'authorization: bearer '.$token,
			'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		//print_r($response);die();
		curl_close($curl);
		if (curl_error($curl)) {
			session()->flash('error','Paytomorrow Something Went Wrong');
            
            return redirect()->route('shop.checkout.cart.index'); 
		}
		$res = json_decode($response);
		//echo $res->url;die();
		
		
		//return $res->url;
		return redirect()->to($res->url);
        } catch (\Exception $e) {
            session()->flash('error','Paytomorrow Something Went Wrong');
            
            return redirect()->route('shop.checkout.cart.index'); 
        }
        
    }
    
     
    
}
