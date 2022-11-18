<?php

namespace Webkul\GoogleShoppingFeed\Helpers;

use Carbon\Carbon;
use Webkul\GoogleShoppingFeed\Repositories\OAuthAccessTokenRepository;
use Webkul\GoogleShoppingFeed\Repositories\MapGoogleCategoryRepository;
use Webkul\GoogleShoppingFeed\Repositories\MapGoogleProductAttributeRepository;
use Webkul\Product\Helpers\ConfigurableOption;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\GoogleShoppingFeed\Repositories\ExportedProductRepository;
use Log;

class GoogleShoppingContentApi
{
    /**
     * OAuthAccessTokenRepository repository object
     */
    protected $oAuthAccessTokenRepository;

     /**
     * mapGoogleCategoryRepository repository object
     */
    protected $mapGoogleCategoryRepository;

    /**
     * mapGoogleProductAttributeRepository repository object
     */
    protected $mapGoogleProductAttributeRepository;

    /**
     * productAttributeValueRepository repository object
     */
    protected $productAttributeValueRepository;


    /**
     * productRepository repository object
     */
    protected $productRepository;

    /**
     * attributeOptionRepository repository object
     */
    protected $attributeOptionRepository;


    /**
     * ExportedProductRepository repository object
     */
    protected $exportedProductRepository;


    public function __construct(
        OAuthAccessTokenRepository $oAuthAccessTokenRepository,
        MapGoogleCategoryRepository $mapGoogleCategoryRepository,
        MapGoogleProductAttributeRepository $mapGoogleProductAttributeRepository,
        ProductAttributeValueRepository $productAttributeValueRepository,
        AttributeOptionRepository $attributeOptionRepository,
        ProductRepository $productRepository,
        ExportedProductRepository $exportedProductRepository
    )
    {
        $this->oAuthAccessTokenRepository = $oAuthAccessTokenRepository;
        $this->mapGoogleProductAttributeRepository = $mapGoogleProductAttributeRepository;
        $this->mapGoogleCategoryRepository = $mapGoogleCategoryRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->attributeOptionRepository = $attributeOptionRepository;
        $this->productRepository = $productRepository;
        $this->exportedProductRepository = $exportedProductRepository;
    }

    /**
     * fetch google shopping product list
     */
    public function getProducts()
    {
        $merchantId =  core()->getConfigData('googleFeed.settings.general.google_merchant_id');

        try {

            $client = new \Google_Client();
            $client->setAccessToken($this->getAccessToken());
            $serviceShoppingContent =  new \Google_Service_ShoppingContent($client);
            $products = $serviceShoppingContent->products->listProducts(
                $merchantId);

            return $products;

        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Exports all products to google shop
     */
    public function exportProduct ($storeProduct)
    {
            $mainProductType = $storeProduct->product->type;
 return $this->uploadProduct($storeProduct, $mainProductType);die();
            switch ($mainProductType) {
                case 'configurable':
                        foreach($storeProduct->product->variants as $variant) {
                            $this->uploadProduct($variant->product_flats[0], $mainProductType);
                        }
                  break;
                case 'bundle':
                    $this->uploadProduct($storeProduct, $mainProductType);
                   break;
                case 'downloadable':
                    //  $this->uploadProduct($storeProduct, $mainProductType);
                  break;

                case 'simple':
                    $this->uploadProduct($storeProduct, $mainProductType);
                    break;
                case 'virtual':
                    //  $this->uploadProduct($storeProduct, $mainProductType);
                        break;
                case 'grouped':
                    $this->uploadProduct($storeProduct, $mainProductType);
                        break;
              }

    }

    /**
     * upload product to google shop
     */

     public function uploadProduct($storeProduct, $type)
     {
        $feedData = $this->getProductFeed($storeProduct, $type);

        $merchantId =  core()->getConfigData('googleFeed.settings.general.google_merchant_id');
        $accessToken = $this->getAccessToken();

        $client = new \Google_Client();
        $client->setAccessToken($accessToken);
        $service =  new \Google_Service_ShoppingContent($client);
		
        
        $product = new \Google_Service_ShoppingContent_Product();
        $product->setOfferId($feedData['offer_id']);
        $product->setTitle($feedData['title']);
        $product->setDescription($feedData['description']);
        $product->setLink($feedData['product_link']);
        $product->setImageLink($feedData['image']);
        $product->setContentLanguage($feedData['lang']);
        $product->setTargetCountry(core()->getConfigData('googleFeed.settings.defaultConfiguration.target_country'));
        $product->setChannel('online');
        $product->setAvailability($feedData['availability']);
        $product->setCondition($feedData['condition']);
        $product->setGoogleProductCategory($feedData['category']);

        if ($type = 'configurable') {
            $product->setItemGroupId($storeProduct->product_id);
        }

        if ($type = 'bundle') {
            $product->setIsBundle(true);
        }

        $price = new \Google_Service_ShoppingContent_Price();
        $price->setValue($feedData['price']);
        $price->setCurrency($feedData['currency']);

        if ($feedData['brand']) {
            $product->setBrand($feedData['brand']);
        }
        if ($feedData['color']) {
            $product->setColor($feedData['color']);
        }

        $product->setGender($feedData['gender']);
        // $customProduct = $product->getGfCustomProduct();
        if (! is_null($feedData['gtin'])) {
            $product->setGtin($feedData['gtin']);
            /** $product->setMpn($feedData['mpn']); */
        } else {
            $product->setIdentifierExists(false);
        }

        $product->setAgeGroup($feedData['ageGroup']);

        if (! is_null($feedData['size'])) {
            $product->setSizes($feedData['size']);
        }
        // if (! is_null($feedData['sizeType'])) {
        //     $product->setSizes($feedData['sizeType']);
        // }
        // if (! is_null($feedData['sizeSystem'])) {
        //     $product->setSizeSystem($feedData['sizeSystem']);
        // }

        $shipping_weight = new \Google_Service_ShoppingContent_ProductShippingWeight();
        $shipping_weight->setValue($feedData['weight']);
        $shipping_weight->setUnit(core()->getConfigData('googleFeed.settings.defaultConfiguration.weight_unit'));
        $product->setShippingWeight($shipping_weight);

        $product->setPrice($price);
        // $product->setShipping(array($shipping));

		
	/*	$product->setOfferId('00002');
		$product->setAvailability('in stock');
		$product->setChannel('online');
		$product->setContentLanguage('en');
		$product->setTargetCountry('GB');
		$product->setLink('http://127.0.0.1/test');
		$product->setImageLink('https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');
		$product->setTitle('Test');

		$price = new \Google_Service_ShoppingContent_Price();
		$price->setValue('12.00');
		$price->setCurrency('GBP');
		$product->setPrice($price);

		$customAttribute = new \Google_Service_ShoppingContent_CustomAttribute();
		$customAttribute->setName('weight');
		$customAttribute->setValue('10');

		$product->setCustomAttributes([$customAttribute]);
*/
		//$product = [];
		
       return $result = $service->products->insert($merchantId, $product);

       /* if (! is_null($result)) {
            
        }

        return $result;*/
     }

    /**
     * Get access token
     */
    public function getAccessToken ()
    {
        $tokenDetails = $this->oAuthAccessTokenRepository->first();
        $current = Carbon::now();

        if (($current->lessThan($tokenDetails->expire_at)))  {

            return $tokenDetails->access_token;
        } else {
            return null;
        }

    }

    /**
     * Get feed category
     */
    public function getProductCategoryFeed ($product)
    {
      $lastProductCategory = last($product->product->categories);

      if ($lastProductCategory) {
        $googleCategory = $this->mapGoogleCategoryRepository->findOneWhere(['category_id' => $lastProductCategory->id]);
      } else {
        $googleCategory = null;
      }

      return $googleCategory;
    }

    /**
     * Get feed category
     */
    public function getProductPriceFeed ($product)
    {
        try {
            $price = $product->product->getTypeInstance()->getMinimalPrice();
            // if (in_array($product->type, ['configurable', 'bundle', 'grouped'])) {
            //     $price = $product->getPriceInfo()->getPrice('final_price')->getMinimalPrice()->getValue();
            // }
            return $price;
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }
    }

    /**
     * Get feed product image
     */
    public function getProductImageFeed ($product,  $type)
    {
        $productImageHelper =  app('Webkul\Product\ProductImage');
        switch ($type) {
            case 'configurable':
                $images = $productImageHelper->getGalleryImages($product);
                if (empty($images)) {
                    $images = $productImageHelper->getGalleryImages($product->product->parent);
                }

              break;
            case 'bundle':
                $images = $productImageHelper->getGalleryImages($product);
              break;
            case 'downloadable':
                $images = $productImageHelper->getGalleryImages($product);
              break;

            case 'simple':
                 $images = $productImageHelper->getGalleryImages($product);
            default :
                $images = $productImageHelper->getGalleryImages($product);
          }



        return $images[0]['medium_image_url'];
    }

    /**
     * get Grouped and bundle product weight
     */
    public function getGroupOrBundleWeight($product, $type)
    {
        if ($type == 'bundle') {

            $productIds = $product->product->bundle_options[0]->bundle_option_products->pluck('product_id')->toArray();
            $weights =  $this->productRepository->findWhereIn('id', $productIds )->pluck('weight')->toArray();

           return array_sum($weights);
        } else {
           $productIds = $product->grouped_products->pluck('associated_product_id')->toArray();
           $weights =  $this->productRepository->findWhereIn('id', $productIds )->pluck('weight')->toArray();

           return array_sum($weights);
        }

    }

    /**
     * Get product feed data
     */
    public function  getProductFeed ($product, $type)
    {
       $attributes = $this->mapGoogleProductAttributeRepository->first();
       $googleCategory = $this->getProductCategoryFeed($product);

       $feedData = [
            "offer_id" => $product[$attributes->product_id],
            "title" => $product[ $attributes->title_id],
            "gtin" => $product[ $attributes->gtin_id],
            "description" => $product[ $attributes->description_id],
            "brand" => $this->getSelectMultiSelecttAttributeValues($product->product->brand),
            "color" => $this->getSelectMultiSelecttAttributeValues($product[ $attributes->color_id]),
            "weight" => $type == 'bundle' || $type == 'grouped' ? $this->getGroupOrBundleWeight($product, $type) : $product[ $attributes->weight_id],
            "image" => $product[ $attributes->image_id] ?? $this->getProductImageFeed($product, $type),
            "size" => $this->getSelectMultiSelecttAttributeValues($product->product->size),
            "size_system" => $product[ $attributes->size_system_id],
            "size_type" => $product[ $attributes->size_type_id],
            "mpn" => $product[ $attributes->mpn_id],
            'product_link' => url('/').'/'.$product->url_key,
            'lang' => core()->getCurrentLocale()->code,
            'availability' => $product->totalQuantity() ? 'in stock' : 'out of stock',
            'category' => is_null($googleCategory) ? core()->getConfigData('googleFeed.settings.defaultConfiguration.category') : $googleCategory->google_category_path,
            'price' => $this->getProductPriceFeed($product),
            'currency' => core()->getBaseCurrency()->code,
            'custom_product' => isset($product->product->custom_product) ? $product->product->custom_product : 0
       ];

       $feedData['ageGroup'] = $this->getSelectMultiSelecttAttributeValues($product->product->age_group) ?? core()->getConfigData('googleFeed.settings.defaultConfiguration.age_group');
       $feedData['gender'] = $this->getSelectMultiSelecttAttributeValues($product->product->available_for) ?? core()->getConfigData('googleFeed.settings.defaultConfiguration.available_for');
       $feedData['condition'] = $this->getSelectMultiSelecttAttributeValues($product->product->condition) ?? core()->getConfigData('googleFeed.settings.defaultConfiguration.condition');

       return $feedData;
    }

    /**
     * Get  product attribute option values
     */
    public function getSelectMultiSelecttAttributeValues($id)
    {
        $optionValue  = $this->attributeOptionRepository->find($id);

        if (! is_null($optionValue)) {
            return $optionValue->admin_name;
        } else {
            return null;
        }
    }

}
