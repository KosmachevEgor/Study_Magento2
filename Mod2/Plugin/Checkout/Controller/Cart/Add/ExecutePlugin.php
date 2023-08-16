<?php

namespace Study\Mod2\Plugin\Checkout\Controller\Cart\Add;

use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Checkout\Controller\Cart\Add;
use Study\Mod1\Controller\Index\AddToCart;

class ExecutePlugin
{
    /**
     * @var ProductResource
     */
    private ProductResource $productResource;

    public function __construct(
        ProductResource $productResource
    ) {
        $this->productResource = $productResource;
    }

    public function beforeExecute(Add $subject)
    {
        $request = $subject->getRequest();

        if ($request->getParam('product') === null && $request->getParam(AddToCart::PARAM_SKU)) {
            $sku = $request->getParam(AddToCart::PARAM_SKU);
            $productId = $this->productResource->getIdBySku($sku);

            if ($productId) {
                $params = $request->getParams();
                $params['product'] = $productId;
                $request->setParams($params);
            }
        }
    }
}
