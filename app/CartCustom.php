<?php
namespace App;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Repositories\CartAddressRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Traits\CartCoupons;
use Webkul\Checkout\Traits\CartTools;
use Webkul\Checkout\Traits\CartValidators;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Tax\Helpers\Tax;
use Webkul\Tax\Repositories\TaxCategoryRepository;

class CartCustom 
{
	use CartCoupons, CartTools, CartValidators;

    /**
     * Create a new class instance.
     *
     * @param  \Webkul\Checkout\Repositories\CartRepository             $cartRepository
     * @param  \Webkul\Checkout\Repositories\CartItemRepository         $cartItemRepository
     * @param  \Webkul\Checkout\Repositories\CartAddressRepository      $cartAddressRepository
     * @param  \Webkul\Product\Repositories\ProductRepository           $productRepository
     * @param  \Webkul\Tax\Repositories\TaxCategoryRepository           $taxCategoryRepository
     * @param  \Webkul\Customer\Repositories\WishlistRepository         $wishlistRepository
     * @param  \Webkul\Customer\Repositories\CustomerAddressRepository  $customerAddressRepository
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected CartAddressRepository $cartAddressRepository,
        protected ProductRepository $productRepository,
        protected TaxCategoryRepository $taxCategoryRepository,
        protected WishlistRepository $wishlistRepository,
        protected CustomerAddressRepository $customerAddressRepository
    )
    {
    }

    /**
     * Returns cart.
     *
     * @return \Webkul\Checkout\Contracts\Cart|null
     */
    public static function getCart(): ?\Webkul\Checkout\Contracts\Cart
    {
        $cart = null;

        if (auth()->guard()->check()) {
            $cart = $this->cartRepository->findOneWhere([
                'customer_id' => auth()->guard()->user()->id,
                'is_active'   => 1,
            ]);
        } else if (session()->has('cart')) {
            $cart = $this->cartRepository->find(session()->get('cart')->id);
        }

        $this->removeInactiveItems($cart);

        return $cart;
    }
	
	 public static function addProduct($productId, $data)
    {

        Event::dispatch('checkout.cart.add.before', $productId);

        $cart = $this->getCart();

        if (! $cart && ! $cart = $this->create($data)) {
            return ['warning' => __('shop::app.checkout.cart.item.error-add')];
        }

        $product = $this->productRepository->findOneByField('id', $productId);

        if ($product->status === 0) {
            return ['info' => __('shop::app.checkout.cart.item.inactive-add')];
        }

        $cartProducts = $product->getTypeInstance()->prepareForCart($data);

        if (is_string($cartProducts)) {
            $this->collectTotals();

            if (count($cart->all_items) <= 0) {
                session()->forget('cart');
            }

            throw new Exception($cartProducts);
        } else {
            $parentCartItem = null;

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->getItemByProduct($cartProduct, $data);

                if (isset($cartProduct['parent_id'])) {
                    $cartProduct['parent_id'] = $parentCartItem->id;
                }

                if (! $cartItem) {
                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {
                    if (isset($cartProduct['parent_id']) && $cartItem->parent_id !== $parentCartItem->id) {
                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, [
                            'cart_id' => $cart->id,
                        ]));
                    } else {
                        $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                    }
                }

                if (! $parentCartItem) {
                    $parentCartItem = $cartItem;
                }
            }
        }

        Event::dispatch('checkout.cart.add.after', $cart);

        $this->collectTotals();

        return $this->getCart();
    }
	
}

?>