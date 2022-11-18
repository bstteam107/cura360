<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;


class Options 
{
    /**
     * Returns the product's avg rating
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return float
     */
    public function getOptions($product)
    {
		$options = DB::table('product_options')->where('product_id', $product->id)->get();      
		return $options;
    }
	public function getOptionsItem($product)
    {
		$optionsItem = DB::table('product_options_item')->where('product_id', $product->id)->get();
        return $optionsItem;
    }
	public function getcartOptions($product_id,$cart_id)
    {
		$options = DB::table('cart_item_options')->where('product_id', $product_id)->where('cart_id', $cart_id)->get();
        return $options;
    }
	public function getOrder($order_id)
    {   
	
	
		$order = DB::table('orders')->where('id', $order_id)->first();
		$products = DB::table('order_items')->where('order_id', $order_id)->get();
		$address = DB::table('addresses')->where('order_id', $order_id)->get();
		 
		
		//oreder table
		 $customer_email = $order->customer_email;
		 $status = $order->status;
		 $increment_id = $order->increment_id;
		 $customer_first_name = $order->customer_first_name;
		 $customer_last_name = $order->customer_last_name;
		 $customer_company_name = $order->customer_company_name;
		 $grand_total = $order->grand_total;
		 $sub_total = $order->sub_total;
		 $discount_amount = $order->discount_amount;
		 $tax_amount = $order->tax_amount;
		 $order_id = $order->id;
		 $cart_id = $order->cart_id;
		 
		 
		 //order item
		 $productname = $products[0]->name;
		 $qty_ordered = $products[0]->qty_ordered;
		 $product_id = $products[0]->id;
		 $price = $products[0]->price;
		 
		 //options
		 $options = $this->getcartOptions($product_id,$cart_id);
		 $des = '';
		 if (isset($options)){ $ctotal = 0;
		 
		 foreach ($options as $option){
		    if($option->price != 0){ 
				$des .= $option->option.' : '.$option->option_item.'/'.core()->currency($option->price).', '; 
			}
			else{
				$des .= $option->option.' : '.$option->option_item.', ';
			}	
			$ctotal = $ctotal + $option->price;
		 }
		$customtotal = $ctotal;
		}else{ $customtotal = 0; }
		
		
		$productprice = $qty_ordered * ($customtotal + $products[0]->price);
		$subtotal = $sub_total + ($qty_ordered * $customtotal);
		$grandtotal = $grand_total + ($qty_ordered * $customtotal);
		
		//Address
		$Ship_to_Name  = $customer_first_name.' '.$customer_last_name;
		if(isset($address[1])){
		$Billing_Street  = $address[1]->address1.' '.$address[1]->address2;
		$Billing_State  =  $address[1]->state;
		$Billing_Country  = $address[1]->country;
		$Billing_Code  = $address[1]->postcode;
		$Billing_City  = $address[1]->city;
		$Billing_Company  = $address[1]->company_name;
		}
		else{
		$Billing_Street  = '';
		$Billing_State  =  '';
		$Billing_Country  = '';
		$Billing_Code  = '';
		$Billing_City  = '';
		$Billing_Company  = $address[0]->company_name;
		}
		$Shipping_City  = $address[0]->city;
		$Shipping_Code  = $address[0]->postcode;
		$Shipping_Country  = $address[0]->country;
		$Shipping_State  = $address[0]->state;
		$customeremail  = $address[0]->email;
		$customerphone  = $address[0]->phone;
		$Shipping_Street  = $address[0]->address1.' '.$address[0]->address2;
		$accountname =  $address[0]->first_name.' '.$address[0]->last_name;
		
		
		
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token?grant_type=refresh_token&client_id=1000.ATWIV37G8BC5MKQF81V44FQ94GUAIX&client_secret=3ad1cf8505b67fa3393ca50b22b3f04b6a7b246362&refresh_token=1000.dd90170264ce5050be04d10478c203ba.d1b1f93623908b294c0948bcefe8a4d2',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Cookie: _zcsr_tmp=4bdb3f01-234b-438a-beaa-2cd21c8cba4f; b266a5bf57=57c7a14afabcac9a0b9dfc64b3542b70; iamcsr=4bdb3f01-234b-438a-beaa-2cd21c8cba4f'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$res = json_decode($response);//print_r($res);
$access_token =  $res->access_token;



$post_product_data = [
						'data' =>[ 
									[
										"Product_Name" => $productname,
										"Unit_Price" => $price,
									]
								]
					 ];

$chp = curl_init();
curl_setopt( $chp, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Products");
curl_setopt( $chp, CURLOPT_POST, 1 );
curl_setopt( $chp, CURLOPT_POSTFIELDS, json_encode($post_product_data) ); 
curl_setopt( $chp, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $chp, CURLOPT_SSL_VERIFYPEER, 0 );
curl_setopt( $chp, CURLOPT_HTTPHEADER, array(
	'Authorization: Zoho-oauthtoken ' . $access_token,
	'Content-Type: application/x-www-form-urlencoded'
) );
$fetch = curl_exec($chp);
curl_close($chp);

$fetchrecord = json_decode($fetch);
//print_r($fetchrecord);
//print_r($fetchrecord->data[0]->status);
if($fetchrecord->data[0]->status == 'error'){
$zoho_product_id = $fetchrecord->data[0]->details->id;
}
else{
$zoho_product_id = $fetchrecord->data[0]->details->Modified_By->id;	
}

$post_data = [
		'data' =>[ 
		    [
				"Account_Name" => $accountname,
				"Subject" => $accountname,
				"Customer_Email" =>$customeremail,
				"Description" => "",
				"Customer_Phone"  => $customerphone,
				"Purchase_Order"  => 'cura360: '.$increment_id,
				"Status"  => $status,
				"Carrier"  => "CURA360",
				"Organization_Name"  => $Billing_Company,
				"Ship_to_Name"  => $Ship_to_Name,
				"Billing_Street"  => $Billing_Street,
				"Billing_State"  => $Billing_State,
				"Billing_Country"  => $Billing_Country,
				"Billing_Code"  => $Billing_Code,
				"Billing_City"  => $Billing_City,
				"Shipping_City"  => $Shipping_City,
				"Shipping_Code"  => $Shipping_Code,
				"Shipping_Country"  => $Shipping_Country,
				"Shipping_State"  => $Shipping_State,
				"Shipping_Street"  => $Shipping_Street,
				"Sub_Total"  => $subtotal,
				"Discount"  => $discount_amount,
				"Tax"  => $tax_amount,
				"Grand_Total"  => $grandtotal,
				"Product_Details" => [ 
               
					[
                   
                    "product" =>  [ 
							"index" => $zoho_product_id,
							
							
							"Currency" =>  "USD",
							
							
							"id" => $zoho_product_id
						],
						
						"quantity" =>  $qty_ordered,
						
						"Discount" =>  0,
						
						"total_after_discount" =>  0,
						
						"net_total" =>  $productprice,
						
						"Tax" =>  0,
						
						"list_price" =>  $productprice,
						
						"unit_price" =>  $productprice,
						
						"quantity_in_stock" =>  -1,
						
						"total" =>  $productprice,
						
						"product_description" =>  $des
					]
				]
		    ]
		],
		"trigger" =>[
				"approval",
				"workflow",
				"blueprint"
		]
];
$chr = curl_init();
curl_setopt( $chr, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Sales_Orders");
curl_setopt( $chr, CURLOPT_POST, 1 );
curl_setopt( $chr, CURLOPT_POSTFIELDS, json_encode($post_data) ); 
curl_setopt( $chr, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $chr, CURLOPT_SSL_VERIFYPEER, 0 );
curl_setopt( $chr, CURLOPT_HTTPHEADER, array(
	'Authorization: Zoho-oauthtoken ' . $access_token,
	'Content-Type: application/x-www-form-urlencoded'
) );
$result = curl_exec($chr);
curl_close($chr);
return $result;

		
	}
}