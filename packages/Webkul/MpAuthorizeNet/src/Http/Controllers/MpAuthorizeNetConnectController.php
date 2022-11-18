<?php

namespace Webkul\MpAuthorizeNet\Http\Controllers;

use Webkul\MpAuthorizeNet\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\MpAuthorizeNet\Repositories\MpAuthorizeNetRepository;
use Webkul\MpAuthorizeNet\Repositories\MpAuthorizeNetCartRepository;
use App\Helpers\Helper;


/**
 * MpAuthorizeNetConnectController Controller
 *
 * @author  shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MpAuthorizeNetConnectController extends Controller
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
     * mpauthorizenetRepository object
     *
     * @var array
     */
    protected $mpauthorizenetRepository;

    /**
     * mpauthorizenetcartRepository object
     *
     * @var array
     */
    protected $mpauthorizenetcartRepository;

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
        MpAuthorizeNetRepository $mpauthorizenetRepository,
        MpAuthorizeNetCartRepository $mpauthorizenetcartRepository,
        Helper $helper
    )
    {
        
        $this->orderRepository = $orderRepository;

        $this->mpauthorizenetRepository = $mpauthorizenetRepository;

        $this->mpauthorizenetcartRepository = $mpauthorizenetcartRepository;

        $this->helper = $helper;

        $this->cart = Cart::getCart();

    }

    public function collectToken()
    {  
        try {
            if (request()->input('savedCardSelectedId')) {

                $misc = $this->mpauthorizenetRepository->findOneWhere([
                    'id' => request()->input('savedCardSelectedId'),
                    'customers_id' => auth()->guard('customer')->user()->id,
                ])->misc;
                    
                $result = $this->mpauthorizenetcartRepository->create([
                    'cart_id' => \Cart::getCart()->id,
                    'mpauthorizenet_token' => $misc,
                ]);
    
                if ($result) {
                    return response()->json(['success' => 'true']);
                } else {
                    return response()->json(['success' => 'false'], 400);
                }
    
            } else {
                
                $misc = request()->input('response'); 
    
                if (auth()->guard('customer')->check() && request()->input('result') == 'true' ) { 
    
                    $last4 = $misc['encryptedCardData']['cardNumber'];
        
                    $cardExist = $this->mpauthorizenetRepository->findOneWhere([
                        'last_four' => $last4,
                        'customers_id' => auth()->guard('customer')->user()->id,
                    ]);
        
                    if ($cardExist) {
                        $result = $cardExist->update([
                            'token' => $misc['opaqueData']['dataValue'],
                            'misc' => json_encode($misc),
                        ]);
                    } else {
                        $result = $this->mpauthorizenetRepository->create([
                            'customers_id' => auth()->guard('customer')->user()->id,
                            'token' => $misc['opaqueData']['dataValue'],
                            'last_four' => $last4,
                            'misc' => json_encode($misc),
                        ]);
                    }
        
                    $this->mpauthorizenetcartRepository->create([
                        'cart_id' => \Cart::getCart()->id,
                        'mpauthorizenet_token' => json_encode($misc),
                    ]);
        
                    if ($result) {
                        return response()->json(['success' => 'true']);
                    } else {
                        return response()->json(['success' => 'false'], 400);
                    }
                } else {
    
                    session()->put('card',request()->input('result'));
    
                    $result = $this->mpauthorizenetcartRepository->create([
                        'cart_id' => \Cart::getCart()->id,
                        'mpauthorizenet_token' => json_encode($misc),
                    ]);
    
                    if ($result) {
                        return response()->json(['success' => 'true']);
                    } else {
                        return response()->json(['success' => 'false'], 400);
                    }
    
                }
    
            }
        } catch (\Exception $e) {
            session()->flash('error', __('mpauthorizenet::app.error.something-went-wrong'));
            
            return redirect()->route('shop.checkout.cart.index'); 
        }
        
    }

    public function createCharge()
    {   
        try {
            $cardBoolean = session()->get('card');
            
            if (auth()->guard('customer')->check() && $cardBoolean != 'false') { 

                $MpauthorizeNetCard = $this->mpauthorizenetcartRepository->findOneWhere([
                                'cart_id' => Cart::getCart()->id
                            ])->mpauthorizenet_token;           

                $MpauthorizeNetCardDecode = json_decode($MpauthorizeNetCard);

                if ( isset($MpauthorizeNetCardDecode->customerResponse)) {

                    $savedCardPaymentResponse = $this->helper->chargeCustomerProfile($MpauthorizeNetCardDecode);
                    
                    $this->mpauthorizenetcartRepository->deleteWhere([
                        'cart_id' => \Cart::getCart()->id
                    ]);

                $customerProfileResponse = $this->helper->paymentResponse($savedCardPaymentResponse);

                if ($customerProfileResponse == 'true') {

                        return redirect()->route('shop.checkout.success');

                } else {

                        session()->flash('warning', $customerProfileResponse);
                
                        return redirect()->route('shop.checkout.cart.index');
                }

                } else {

                    $customerEmail = Cart::getCart()->billing_address->email;

                    $cutomerProfileResponse = $this->helper->createCustomerProfile($customerEmail,$MpauthorizeNetCardDecode);
                
                    if (($cutomerProfileResponse != null) && ($cutomerProfileResponse->getMessages()->getResultCode() == "Ok")) {
            
                        $paymentProfiles = $cutomerProfileResponse->getCustomerPaymentProfileIdList();
            
                        $customerResponse = [
                            'customerProfileId' => $cutomerProfileResponse->getCustomerProfileId(),
                            'paymentProfielId' => $paymentProfiles[0],
                        ];
                    
                        $cardToken = $this->mpauthorizenetRepository->findOneWhere([
                            'token' => $MpauthorizeNetCardDecode->opaqueData->dataValue,
                        ])->misc;
                        
            
                        $cardTokenDecode = json_decode($cardToken);
            
                        $updateRespone = [
                            'cardResponse' => $cardTokenDecode,
                            'customerResponse' => $customerResponse,
                        ];
            
                        $this->mpauthorizenetRepository->findOneWhere([
                            'token' => $MpauthorizeNetCardDecode->opaqueData->dataValue,
                        ])->update([
                            'misc' => json_encode($updateRespone),
                        ]);
                        
                        $UpdatedToken = $this->mpauthorizenetRepository->findOneWhere([
                            'token' => $MpauthorizeNetCardDecode->opaqueData->dataValue,
                        ])->misc;
                        
                        $decodeUpdatedToken = json_decode($UpdatedToken);
                            
                        $savedCardPaymentResponse = $this->helper->chargeCustomerProfile($decodeUpdatedToken);

                        $customerProfileResponse = $this->helper->paymentResponse($savedCardPaymentResponse);

                        if ($customerProfileResponse == 'true') {

                            return redirect()->route('shop.checkout.success');
            
                    } else {
            
                        session()->flash('warning', $customerProfileResponse);
                
                        return redirect()->route('shop.checkout.cart.index');
                    }
                            
                    } else {
            
                        $this->helper->deleteCart();
                        
                        $errorMessages = $cutomerProfileResponse->getMessages()->getMessage();
            
                        session()->flash('warning', $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText());
            
                        return redirect()->route('shop.checkout.cart.index');
                    }
                }
            } else {

                $MpauthorizeNetCard = $this->mpauthorizenetcartRepository->findOneWhere([
                    'cart_id' => Cart::getCart()->id
                ])->mpauthorizenet_token;           

                $MpauthorizeNetCardDecode = json_decode($MpauthorizeNetCard);  

                $guestResponse = $this->helper->createAnAcceptPaymentTransaction($MpauthorizeNetCardDecode);

                $this->mpauthorizenetcartRepository->deleteWhere([
                    'cart_id' => \Cart::getCart()->id
                ]);
                
                $paymentResponse = $this->helper->paymentResponse($guestResponse);

                if ($paymentResponse == 'true') {

                    return redirect()->route('shop.checkout.success');

                } else {

                    $this->helper->deleteCart();

                    session()->flash('warning', $paymentResponse);
            
                    return redirect()->route('shop.checkout.cart.index');
                }
            }

        } catch (\Exception $e) {
            session()->flash('error', __('mpauthorizenet::app.error.something-went-wrong'));
            
            return redirect()->route('shop.checkout.cart.index'); 
        }
        
    }
    
     /**
     * Call to delete saved card
     *
     *
     * @return string
     */

    public function deleteCard()
    {   
        try {
            $deleteIfFound = $this->mpauthorizenetRepository->findOneWhere(['id' => request()->input('id'), 'customers_id' => auth()->guard('customer')->user()->id]);

            $result = $deleteIfFound->delete();

            return (string)$result;
        } catch(\Exception $e) {
            session()->flash('error', __('mpauthorizenet::app.error.something-went-wrong'));
            
            return redirect()->route('shop.checkout.cart.index');
        }
        
    }
    
}
