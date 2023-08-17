<?php

namespace Study\Mod2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Study\Mod2\Model\ConfigProvider;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Exception;

class PromoSkuObserver implements ObserverInterface
{
    public const PROMO_SKU_QTY = 1;

    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    public function __construct(
        ConfigProvider             $configProvider,
        Session                    $checkoutSession,
        CartRepositoryInterface    $cartRepository,
        ProductRepositoryInterface $productRepository,
        ManagerInterface           $messageManager
    )
    {
        $this->configProvider = $configProvider;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        if (!empty($observer->getData('customer_sku'))) {
            $forSkus = $this->configProvider->getValueForSkus();
            $promoSku = $this->configProvider->getPromoSku();

            try {
                $product = $this->productRepository->get($promoSku);

                foreach ($forSkus as $forSkuElement) {
                    if ($observer->getData('customer_sku') === $forSkuElement) {
                        $quote = $this->checkoutSession->getQuote();
                        $quote->addProduct($product, self::PROMO_SKU_QTY);
                        $this->cartRepository->save($quote);
                    }
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
    }
}
