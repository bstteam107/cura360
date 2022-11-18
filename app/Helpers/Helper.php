<?php
namespace App\Helpers;
use Webkul\Checkout\Facades\Cart;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\MpAuthorizeNet\Repositories\MpAuthorizeNetCartRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

/**
 * MpAuthorizeNetConnect Helper Class
 *
 * @author  shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class Helper {

    /**
     * Marketplace Seller Repository object
     */
    protected $marketplaceSellerRepository;

    /**
     * Marketplace product Repository object
     */
    protected $marketplaceProductRepository;

     /**
     * to hold merchantAuthentication value
     */
    protected $merchantAuthentication;

     /**
     * to hold loginID value
     */
    protected $loginID;

     /**
     * to hold transactionKey value
     */
    protected $transactionKey;

    /**
     * to hold reference Id value
     */
    protected $refId;

     /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;

    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

     /**
     * mpauthorizenetcartRepository object
     *
     * @var array
     */
    protected $mpauthorizenetcartRepository;

    public function __construct( 
        OrderRepository $orderRepository,
        MpAuthorizeNetCartRepository $mpauthorizenetcartRepository,
        InvoiceRepository $invoiceRepository
    )
    {   
        $this->orderRepository = $orderRepository;

        $this->mpauthorizenetcartRepository = $mpauthorizenetcartRepository;

        $this->invoiceRepository = $invoiceRepository;

        if (core()->getConfigData('sales.paymentmethods.mpauthorizenet.debug') == '1') {
            $merchantLoginId = core()->getConfigData('sales.paymentmethods.mpauthorizenet.test_api_login_ID');
            $merchantAuthentication = core()->getConfigData('sales.paymentmethods.mpauthorizenet.test_transaction_key');
        } else {
            $merchantLoginId = core()->getConfigData('sales.paymentmethods.mpauthorizenet.api_login_ID');
            $merchantAuthentication = core()->getConfigData('sales.paymentmethods.mpauthorizenet.transaction_key');
        }
         /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $this->merchantAuthentication = new AnetAPI\MerchantAuthenticationType();

        $this->loginID = $this->merchantAuthentication->setName($merchantLoginId);

        $this->transactionKey = $this->merchantAuthentication->setTransactionKey($merchantAuthentication);
        
        // Set the transaction's refId
        $this->refId = 'ref' . time();
    
    }

     /**
     * Create Customer Profile
     *
     * @return array
     */
    function createCustomerProfile($email='',$MpauthorizeNetCardDecode='') 
    {
        
        // Create the payment object for a payment nonce
        $opaqueData = new AnetAPI\OpaqueDataType();
        $opaqueData->setDataDescriptor($MpauthorizeNetCardDecode->opaqueData->dataDescriptor);
        $opaqueData->setDataValue($MpauthorizeNetCardDecode->opaqueData->dataValue);


        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setOpaqueData($opaqueData);


        $billingAddress = Cart::getCart()->billing_address;
       
        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($billingAddress->first_name);
        $customerAddress->setLastName($billingAddress->last_name);
        $customerAddress->setAddress($billingAddress->address1);
        $customerAddress->setCity($billingAddress->city);
        $customerAddress->setState($billingAddress->state);
        $customerAddress->setZip($billingAddress->postcode);
        $customerAddress->setCountry($billingAddress->country);
        $customerAddress->setPhoneNumber($billingAddress->phone);


        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setBillTo($customerAddress);
        $paymentProfile->setPayment($paymentOne);
        $paymentProfiles[] = $paymentProfile;

        // Create an array of any shipping addresses

        // Create a new CustomerProfileType and add the payment profile object
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription(core()->getConfigData('sales.paymentmethods.mpauthorizenet.description'));
        $customerProfile->setMerchantCustomerId("M_" . time());
        $customerProfile->setEmail($email);
        $customerProfile->setpaymentProfiles($paymentProfiles);


        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setRefId($this->refId);
        $request->setProfile($customerProfile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
       
        return $response;
    }

     /**
     * Charge Customer Profile
     *
     * @return boolean
     */
    function paymentResponse($savedCardPaymentResponse = '') 
    {  
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        $this->order = $this->orderRepository->findOneWhere([
            'cart_id' => Cart::getCart()->id
            ]);


        if ($savedCardPaymentResponse != null) {

          if ($savedCardPaymentResponse->getMessages()->getResultCode() == "Ok") {

            $tresponse = $savedCardPaymentResponse->getTransactionResponse();
            
            if ($tresponse != null && $tresponse->getMessages() != null) {

                $this->deleteCart();

                Cart::deActivateCart();

                session()->flash('order', $order);

                $this->orderRepository->update(['status' => 'processing'], $this->order->id);
        
                if ($this->order->canInvoice()) {
                    
                    $this->invoiceRepository->create($this->prepareInvoiceData());
                }
           
                return true;

            }
            else { 
             
                $this->deleteCart();

                if($tresponse->getErrors() != null) {  
                
                    $error = $tresponse->getErrors()[0]->getErrorCode() . "  " .$tresponse->getErrors()[0]->getErrorText();

                    return $error;

                }
            }
          }
          else {
            
            $this->deleteCart();

            $tresponse = $savedCardPaymentResponse->getTransactionResponse();

            if($tresponse != null && $tresponse->getErrors() != null)
            {

              $error = $tresponse->getErrors()[0]->getErrorCode() . "  " .$tresponse->getErrors()[0]->getErrorText();
              
              return $error;                  

            }
            else {
               
              $error = $savedCardPaymentResponse->getMessages()->getMessage()[0]->getCode() . "  " .$savedCardPaymentResponse->getMessages()->getMessage()[0]->getText();
             
              return $error;

            }
          }
        }
        else
        {  
            $this->mpauthorizenetcartRepository->deleteWhere([
                'cart_id' => \Cart::getCart()->id
            ]);

            $error = 'Payment Failed';

            return $error;
        }
    }

    /**
     * Charge Customer Profile
     *
     * @return array
     */
    function chargeCustomerProfile($decodeUpdatedToken)
    {

        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($decodeUpdatedToken->customerResponse->customerProfileId);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($decodeUpdatedToken->customerResponse->paymentProfielId);
        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
        $transactionRequestType->setAmount(Cart::getCart()->base_grand_total);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setRefId($this->refId);
        $request->setTransactionRequest( $transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        return $response;
    }

    function createAnAcceptPaymentTransaction($MpauthorizeNetCardDecode)
    {
        // Create the payment object for a payment nonce
        $opaqueData = new AnetAPI\OpaqueDataType();
        $opaqueData->setDataDescriptor($MpauthorizeNetCardDecode->opaqueData->dataDescriptor);
        $opaqueData->setDataValue($MpauthorizeNetCardDecode->opaqueData->dataValue);


        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setOpaqueData($opaqueData);

        $billingAddress = Cart::getCart()->billing_address;
       
        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($billingAddress->first_name);
        $customerAddress->setLastName($billingAddress->last_name);
        $customerAddress->setAddress($billingAddress->address1);
        $customerAddress->setCity($billingAddress->city);
        $customerAddress->setState($billingAddress->state);
        $customerAddress->setZip($billingAddress->postcode);
        $customerAddress->setCountry($billingAddress->country);
        $customerAddress->setPhoneNumber($billingAddress->phone);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setId("C_" . time());
        $customerData->setEmail($billingAddress->email);

        // Add values for transaction settings
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");    

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction"); 
        $transactionRequestType->setAmount(Cart::getCart()->base_grand_total);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setRefId($this->refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        return $response;
    }

     /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = [
            "order_id" => $this->order->id
        ];
        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }
        return $invoiceData;
    }

    public function deleteCart() 
    {
        $this->mpauthorizenetcartRepository->deleteWhere([
            'cart_id' => \Cart::getCart()->id
        ]);
    }

}