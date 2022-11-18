<?php

namespace Webkul\MpAuthorizeNet\Http\Controllers;

use Webkul\MpAuthorizeNet\Http\Controllers\Controller;
use Webkul\MpAuthorizeNet\Repositories\MpAuthorizeNetRepository;


/**
 * MpAuthorizeNetAccountController Controller
 *
 * @author  shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MpAuthorizeNetAccountController extends Controller
{   
    /**
     * mpauthorizenetRepository object
     *
     * @var array
     */
    protected $mpauthorizenetRepository;


     /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    public function __construct(MpAuthorizeNetRepository $mpauthorizenetRepository)
    {
        
        $this->_config = request('_config');

        $this->mpauthorizenetRepository = $mpauthorizenetRepository;

    }

    public function saveCard() 
    {  
        $cardDetail = $this->mpauthorizenetRepository->scopeQuery(function($query){
            return $query->orderBy('id','desc');
            })->findByField('customers_id', auth()->guard('customer')->user()->id);

        return view($this->_config['view'],compact('cardDetail'));

    }

    public function storeCard()
    {
        $misc = request()->input('response');

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
            $lastInsertedData = ['cardExist' => 'true'];
        } else {
            $result = $this->mpauthorizenetRepository->create([
                'customers_id' => auth()->guard('customer')->user()->id,
                'token' => $misc['opaqueData']['dataValue'],
                'last_four' => $last4,
                'misc' => json_encode($misc),
            ]);
            $lastInsertedData = $this->mpauthorizenetRepository->scopeQuery(function($query){
                return $query->orderBy('id','desc');
                })->first();
        }

       
        if ($result) {
            return response()->json($lastInsertedData);
        } else {
            return response()->json(['success' => 'false'], 400);
        }
    }


    public function cardDefault()
    {  
        
        $updateIfalreadyDefault = $this->mpauthorizenetRepository->findOneWhere(['is_default' => '1', 'customers_id' => auth()->guard('customer')->user()->id]);
        
        if($updateIfalreadyDefault) {

           $updateIfalreadyDefault->update(['is_default'=>'0']);
        }
        $updateIfFound = $this->mpauthorizenetRepository->findOneWhere(['id' => request()->input('id'), 'customers_id' => auth()->guard('customer')->user()->id]);

        $result = $updateIfFound->update(['is_default'=>'1']);

        if ($result) {
            return response()->json(['success' => 'true']);
        } else {
            return response()->json(['success' => 'false'], 400);
        }

    }

}