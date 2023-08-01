<?php

declare(strict_types=1);

namespace Study\Mod1\Controller\Index;

use Exception;
use Laminas\Stdlib\ArrayObject;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Checkout\Model\Cart as ModelCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class AddToCart implements HttpPostActionInterface
{
    public const PARAM_QTY = 'qty';
    public const PARAM_SKU = 'sku';

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ModelCart
     */
    private ModelCart $modelCart;

    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @var ProductResource
     */
    private ProductResource $productResource;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $productCollectionFactory;

    public function __construct(
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $productCollectionFactory,
        ModelCart $modelCart,
        Session $checkoutSession,
        CartRepositoryInterface $cartRepository,
        ProductResource $productResource
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->modelCart = $modelCart;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->productResource = $productResource;
    }

    public function checkQtyProduct($sku)
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('sku', ['eq' => $sku]);
        $qtyProductSku = null;

        foreach ($productCollection as $productSku) {
            $qtyProductSku = $productSku->getQty();
        }

        return $qtyProductSku;
    }

    public function addProductToQuote($product, $qty)
    {
        $quote = $this->checkoutSession->getQuote();
        $quote->addProduct($product, $qty);
        $this->cartRepository->save($quote);
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create()->setPath('*/*/');

        $qty = $this->request->getParam(self::PARAM_QTY);
        $sku = $this->request->getParam(self::PARAM_SKU);

        $skuData = new ArrayObject(explode(", ", $sku));
        $qtyData = new ArrayObject(explode(", ", $qty));

        $skuDataIterator = $skuData->getIterator();
        $qtyDataIterator = $qtyData->getIterator();

        for ($skuDataIterator->rewind(), $qtyDataIterator->rewind();
        $skuValue = $skuDataIterator->current(), $qtyValue = $qtyDataIterator->current();
        $skuDataIterator->next(),$qtyDataIterator->next()) {
            try {
                if (!empty($this->checkQtyProduct($skuValue))) {
                    $productQty = $this->checkQtyProduct($skuValue);
                    $productId = $this->productResource->getIdBySku($skuValue);
                    $items = $this->modelCart->getQuote()->getAllItems();
                    $productQtyInCart = 0;

                    foreach ($items as $item) {
                        if ($item->getProductId() === $productId) {
                            $productQtyInCart = $item->getQty();
                        }
                    }

                    if ($qtyValue + $productQtyInCart > $productQty) {
                        if ($productQty >= $productQtyInCart) {
                            $qtyValue = $productQty - $productQtyInCart;
                        }

                        $this->messageManager->addErrorMessage(__("It is possible to add only $qtyValue products"));
                    }
                }

                $product = $this->productRepository->get($skuValue);
                $productType = $product->getTypeId();

                if ($productType === Type::TYPE_SIMPLE) {
                    $this->addProductToQuote($product, $qtyValue);
                    $this->messageManager->addSuccessMessage("Product $skuValue successfully added to cart");
                } else {
                    $this->messageManager->addErrorMessage(__('This product is not simple'));
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__("Product $skuValue does not exist. Check product name"));
            }
        }
        return $redirect;
    }
}
