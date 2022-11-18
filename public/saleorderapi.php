<?php

/*$curl = curl_init();

$code = '1000.8ec130eb2ece2af86198c4ac0056a950.39b283b04d8f489fb1d7258834c625f0';


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token?grant_type=authorization_code&client_id=1000.ATWIV37G8BC5MKQF81V44FQ94GUAIX&client_secret=3ad1cf8505b67fa3393ca50b22b3f04b6a7b246362&redirect_uri=https://cura360.com/&code=$code',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Cookie: stk=18f0cb3155904adcd3d401ad8116a487; JSESSIONID=734D9928343AC17E75118170ED752E20; _zcsr_tmp=5cc657e7-163f-42b3-b3b0-f9ee55846fb8; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=412d04ceb86ecaf57aa7a1d4903c681d; iamcsr=5cc657e7-163f-42b3-b3b0-f9ee55846fb8'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
die();


die();
*/







$header = array(
    'Authorization: Zoho-oauthtoken 1000.65a9bbc9e404fa34fb85158aca2e7481.002b6ca9e7230d8c1049b7262f5f57ee'
);

$data = array(
'data' => array(
			'Owner' => array(
				'name' => 'Stephanie Bell',
                
                'id' => '4150868000000225013',
                
                'email' => 'matt@cura360.com'
			),
			'Account_Name' => array('id' => '1212112'),
			'Subject' => "SO Kaizen 53"  			
),
'trigger' => array(),
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