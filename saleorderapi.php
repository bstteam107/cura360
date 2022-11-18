<?php



$header = array(
    'Authorization: Zoho-oauthtoken 1000.8f43eeee56170ac69d8cd78b34a0fe20.6161b2ec67108d44531c32d0b346a139'
);

$data = array(
			'Account_Name' => array('id' => '1212112'),
			'Subject' => "SO Kaizen 53",
            'Product_Details' => array (
                'product' => array(
                        "id" => "3652397000000416001"
                    ),
					"quantity"=> 10,
                    "Discount"=> 20,
                    "Unit Price"=> 1000,
                    "line_tax"=> array(
                        
                            "percentage"=>10,
                            "name"=> "Sales Tax",
                            "percentage"=> 1,
                            "name"=> "VAT"
                        
                    )
                )
			);

	
	
$data_json = json_encode( $data );
$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Sales_Orders");
curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PUT" );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_json ); 
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$response = curl_exec($ch);
curl_close($ch);
print_r($response);
?>