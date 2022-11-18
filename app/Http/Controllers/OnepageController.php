<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Payment\Facades\Payment;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Mail;
use Webkul\Shop\Mail\NotifyEmail;
use Illuminate\Support\Arr;
Use Exception;
use DB;

class OnepageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected CustomerRepository $customerRepository
    )
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Event::dispatch('checkout.load.index');

        if (! auth()->guard('customer')->check() && ! core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout')) {
            return redirect()->route('customer.session.index');
        }

        if (auth()->guard('customer')->check() && auth()->guard('customer')->user()->is_suspended) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (
            (! auth()->guard('customer')->check() && $cart->hasDownloadableItems())
            || (! auth()->guard('customer')->check() && ! $cart->hasGuestCheckoutItems())
        ) {
            return redirect()->route('customer.session.index');
        }

        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if (! $cart->checkMinimumOrder()) {
            session()->flash('warning', trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));

            return redirect()->back();
        }

        Cart::collectTotals();
		
		
		
		
        return view($this->_config['view'], compact('cart'));
    }

	
    /**
     * Return order short summary.
     *
     * @return \Illuminate\Http\Response
     */
    public function summary()
    {
        $cart = Cart::getCart();

        return response()->json([
            'html' => view('shop::checkout.total.summary', compact('cart'))->render(),
        ]);
    }

    /**
     * Saves customer address.
     *
     * @param  \Webkul\Checkout\Http\Requests\CustomerAddressForm  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = $request->all();

        if (! auth()->guard('customer')->check() && ! Cart::getCart()->hasGuestCheckoutItems()) {
            return response()->json(['redirect_url' => route('customer.session.index')], 403);
        }

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (Cart::hasError() || ! Cart::saveCustomerAddress($data)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        $cart = Cart::getCart();

        Cart::collectTotals();

        if ($cart->haveStockableItems()) {
            if (! $rates = Shipping::collectRates()) {
                return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
            }

            return response()->json($rates);
        }

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Saves shipping method.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveShipping()
    {
        $shippingMethod = request()->get('shipping_method');

        if (Cart::hasError() || ! $shippingMethod || ! Cart::saveShippingMethod($shippingMethod)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Saves payment method.
     *
     * @return \Illuminate\Http\Response
     */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if (Cart::hasError() || ! $payment || ! Cart::savePaymentMethod($payment)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'jump_to_section' => 'review',
            'html'            => view('shop::checkout.onepage.review', compact('cart'))->render(),
        ]);
    }

    /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveOrder()
    {
        if (Cart::hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $this->validateOrder();

        $cart = Cart::getCart();
		
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
			
				
		}
		$cart->grand_total = $grand_total;
		$cart->base_grand_total = $base_grand_total;
		$cart->sub_total = $sub_total;
		$cart->base_sub_total = $base_sub_total;
		$cartpricedata = array(
						'grand_total'             => $grand_total,
						'base_grand_total'        => $base_grand_total,
						'sub_total'               => $sub_total,
						'base_sub_total'          => $base_sub_total,
                );
		DB::table('cart')->where(['id'=>$cart->id])->limit(1)->update($cartpricedata);
		
		$cart->save();
		//print_r($cart);die();
		if($cart->payment->method == 'paytomorrow'){
				//Cart::deActivateCart();
		        //Cart::activateCartIfSessionHasDeactivatedCartId();
				$order = $this->orderRepository->create($this->prepareDataForOrders());
				session()->put('order', $order);
			}
        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
			
            return response()->json([
                'success'      => true,
                'redirect_url' => $redirectUrl,
            ]);
        }

        $order = $this->orderRepository->create($this->prepareDataForOrders());
        Cart::deActivateCart();

        Cart::activateCartIfSessionHasDeactivatedCartId();

        session()->flash('order', $order);

        return response()->json([
            'success' => true,
        ]);
    }
public function prepareDataForOrders(): array
    {
        $cart = Cart::getCart();
		
		$data = Cart::toArray();
		
		
        $finalData = [
            'cart_id'               => Cart::getCart()->id,
            'customer_id'           => $data['customer_id'],
            'is_guest'              => $data['is_guest'],
            'customer_email'        => $data['customer_email'],
            'customer_first_name'   => $data['customer_first_name'],
            'customer_last_name'    => $data['customer_last_name'],
            'customer'              => auth()->guard()->check() ? auth()->guard()->user() : null,
            'total_item_count'      => $data['items_count'],
            'total_qty_ordered'     => $data['items_qty'],
            'base_currency_code'    => $data['base_currency_code'],
            'channel_currency_code' => $data['channel_currency_code'],
            'order_currency_code'   => $data['cart_currency_code'],
            'grand_total'           => $cart->grand_total,
            'base_grand_total'      => $cart->base_grand_total,
            'sub_total'             => $cart->sub_total,
            'base_sub_total'        => $cart->base_sub_total,
            'tax_amount'            => $data['tax_total'],
            'base_tax_amount'       => $data['base_tax_total'],
            'coupon_code'           => $data['coupon_code'],
            'applied_cart_rule_ids' => $data['applied_cart_rule_ids'],
            'discount_amount'       => $data['discount_amount'],
            'base_discount_amount'  => $data['base_discount_amount'],
            'billing_address'       => Arr::except($data['billing_address'], ['id', 'cart_id']),
            'payment'               => Arr::except($data['payment'], ['id', 'cart_id']),
            'channel'               => core()->getCurrentChannel(),
        ];
        // if (Cart::getCart()->haveStockableItems()) {
        //     $finalData = array_merge($finalData, [
        //         'shipping_method'               => $data['selected_shipping_rate']['method'],
        //         'shipping_title'                => $data['selected_shipping_rate']['carrier_title'] . ' - ' . $data['selected_shipping_rate']['method_title'],
        //         'shipping_description'          => $data['selected_shipping_rate']['method_description'],
        //         'shipping_amount'               => $data['selected_shipping_rate']['price'],
        //         'base_shipping_amount'          => $data['selected_shipping_rate']['base_price'],
        //         'shipping_address'              => Arr::except($data['shipping_address'], ['id', 'cart_id']),
        //         'shipping_discount_amount'      => $data['selected_shipping_rate']['discount_amount'],
        //         'base_shipping_discount_amount' => $data['selected_shipping_rate']['base_discount_amount'],
        //     ]);
        // }
		
		
        foreach ($data['items'] as $item) {
			
            $finalData['items'][] = Cart::prepareDataForOrderItem($item);
        }

        if ($finalData['payment']['method'] === 'paypal_smart_button') {
			
            $finalData['payment']['additional'] = request()->get('orderData');
        }
        return $finalData;
    }




    /**
     * Order success page.
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        if (! $order = session('order')) {
            return redirect()->route('shop.checkout.cart.index');
        }

        return view($this->_config['view'], compact('order'));
    }
	public function paysucess()
    {
		
		Cart::removeAllItems();
		$orderid = $_GET['id'];
		$order = DB::table('orders')->where('increment_id', $orderid)->first();
		 $cart = Cart::getCart();
        //print_r($cart);die();
		//sleep(3);
		//$this->paynotify(Request $request);
		
		
		
		
        return view($this->_config['view'], compact('order'));
        
    }
	public function paynotify(Request $request)
    {
		//echo 'hello';die();
        $params = $request->request->all();
		//$response = json_decode($params);
		//print_r($params);die();
		
			$order_id = $params['order_id'];
			$status = $params['payment_status'];
			$order = $this->updatepayorderstatus($order_id,$status);
		print_r($order);
		//die();
		//return view($this->_config['view']);
    }
	
	public function updatepayorderstatus($order_id,$status){
			$data1 = array(
							'status' => $status,
						);
			try
			{ //return 'done';die();
			DB::table('orders')->where(['increment_id'=>$order_id])->limit(1)->update($data1);
				
				$data = array(
					'order_id' => $order_id,
					'status' => $status
				);
				
				Mail::queue(new NotifyEmail($data));

				
				
			}
			catch(Exception $e)
			{
			  // return dd($e->getMessage());
			}
		
	}
	public function notifymail(){
		
		$data = array(
					'order_id' => 12121,
					'status' => 'success'
				);
				
		Mail::queue(new NotifyEmail($data));
	}
    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if (auth()->guard('customer')->check() && auth()->guard('customer')->user()->is_suspended) {
            throw new \Exception(trans('shop::app.checkout.cart.suspended-account-message'));
        }

        if (! $cart->checkMinimumOrder()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }

        if ($cart->haveStockableItems() && ! $cart->shipping_address) {
            throw new \Exception(trans('shop::app.checkout.cart.check-shipping-address'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('shop::app.checkout.cart.check-billing-address'));
        }

        // if ($cart->haveStockableItems() && ! $cart->selected_shipping_rate) {
        //     throw new \Exception(trans('shop::app.checkout.cart.specify-shipping-method'));
        // }

        if (! $cart->payment) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-payment-method'));
        }
    }

    /**
     * Check customer is exist or not.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkExistCustomer()
    {
        $customer = $this->customerRepository->findOneWhere([
            'email' => request()->email,
        ]);

        if (! is_null($customer)) {
            return 'true';
        }

        return 'false';
    }

    /**
     * Login for checkout.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginForCheckout()
    {
        $this->validate(request(), [
            'email' => 'required|email',
        ]);

        if (! auth()->guard('customer')->attempt(request(['email', 'password']))) {
            return response()->json(['error' => trans('shop::app.customer.login-form.invalid-creds')]);
        }

        Cart::mergeCart();

        return response()->json(['success' => 'Login successfully']);
    }

    /**
     * To apply couponable rule requested.
     *
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon()
    {
        $this->validate(request(), [
            'code' => 'string|required',
        ]);

        $code = request()->input('code');

        $result = $this->coupon->apply($code);

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('shop::app.checkout.total.coupon-applied'),
                'result'  => $result,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => trans('shop::app.checkout.total.cannot-apply-coupon'),
            'result'  => null,
        ], 422);
    }

    /**
     * Initiates the removal of couponable cart rule.
     *
     * @return array
     */
    public function removeCoupon()
    {
        $result = $this->coupon->remove();

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('admin::app.promotion.status.coupon-removed'),
                'data'    => [
                    'grand_total' => core()->currency(Cart::getCart()->grand_total),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => trans('admin::app.promotion.status.coupon-remove-failed'),
            'data'    => null,
        ], 422);
    }

    /**
     * Check for minimum order.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkMinimumOrder()
    {
        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        $status = Cart::checkMinimumOrder();

        return response()->json([
            'status'  => ! $status ? false : true,
            'message' => ! $status ? trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]) : 'Success',
        ]);
    }
}
