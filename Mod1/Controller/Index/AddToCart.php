<?php

declare(strict_types=1);

namespace Study\Mod1\Controller\Index;

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Study\Mod1\Model\ResourceModel\Blacklist\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Checkout\Model\Cart as ModelCart;
use Magento\Checkout\Model\SessionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
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
     * @var SessionFactory
     */
    private SessionFactory $checkoutSession;

    /**
     * @var EventManagerInterface
     */
    private EventManagerInterface $eventManager;

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
    protected CollectionFactory $blacklistCollectionFactory;

    public function __construct(
        RequestInterface           $request,
        RedirectFactory            $redirectFactory,
        ManagerInterface           $messageManager,
        ProductRepositoryInterface $productRepository,
        CollectionFactory          $blacklistCollectionFactory,
        ModelCart                  $modelCart,
        SessionFactory             $checkoutSession,
        CartRepositoryInterface    $cartRepository,
        ProductResource            $productResource,
        EventManagerInterface      $eventManager
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->productRepository = $productRepository;
        $this->blacklistCollectionFactory = $blacklistCollectionFactory;
        $this->modelCart = $modelCart;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->eventManager = $eventManager;
        $this->productResource = $productResource;
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create()->setPath('*/*/');

        $qty = $this->request->getParam(self::PARAM_QTY);
        $sku = $this->request->getParam(self::PARAM_SKU);

        if (empty($sku) || empty($qty)) {
            $this->messageManager->addErrorMessage(__("Fields must not be empty"));
            return $redirect;
        }

        $skus = explode(", ", $sku);
        $qties = explode(", ", $qty);

        if (count($skus) == count($qties)) {
            for ($i = 0; $i < count($skus); $i++) {
                $products[$i] = [$skus[$i], $qties[$i]];
            }
        } else {
            $this->messageManager->addErrorMessage(__("The quantity of the product does not match its quantity"));
            return $redirect;
        }

        foreach ($products as [$productName, $qtyProduct]) {
            try {
                if (!empty($this->checkBlacklistQtyProduct($products))) {
                    $blacklistQty = $this->checkBlacklistQtyProduct($productName);
                    $productId = $this->productResource->getIdBySku($productName);
                    $items = $this->modelCart->getQuote()->getAllItems();
                    $productQtyInCart = 0;

                    foreach ($items as $item) {
                        if ($item->getProductId() === $productId) {
                            $productQtyInCart = $item->getQty();
                        }
                    }

                    if ($qtyProduct + $productQtyInCart > $blacklistQty) {
                        if ($blacklistQty >= $productQtyInCart) {
                            $qtyProduct = $blacklistQty - $productQtyInCart;
                        }

                        $this->messageManager->addErrorMessage(__("It is possible to add only $qtyProduct products"));
                    }
                }

                $product = $this->productRepository->get($productName);
                $productType = $product->getTypeId();

                if ($productType === Type::TYPE_SIMPLE) {
                    $this->addProductToQuote($product, $qtyProduct);
                    $this->eventManager->dispatch(
                        'add_to_cart',
                        [
                            'customer_sku' => $sku
                        ]
                    );
                    $this->messageManager->addSuccessMessage("Product $productName successfully added to cart");
                } else {
                    $this->messageManager->addErrorMessage(__('This product is not simple'));
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__("Product $productName does not exist or or does not have a quantity. Check product name"));
            }
        }
        return $redirect;
    }

    private function checkBlacklistQtyProduct($sku)
    {
        $blacklistCollection = $this->blacklistCollectionFactory->create();
        $blacklistCollection->addFieldToFilter('sku', ['in' => $sku]);
        $qtyBlacklistSku = null;

        foreach ($blacklistCollection as $productSku) {
            $qtyBlacklistSku = $productSku->getQty();
        }

        return $qtyBlacklistSku;
    }

    private function addProductToQuote($product, $qty)
    {
        $session = $this->checkoutSession->create();
        $quote = $session->getQuote();
        $quote->addProduct($product, $qty);
        $this->cartRepository->save($quote);
    }
}
