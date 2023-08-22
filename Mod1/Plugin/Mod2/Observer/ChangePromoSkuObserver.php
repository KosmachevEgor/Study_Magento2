<?php

declare(strict_types=1);

namespace Study\Mod1\Plugin\Mod2\Observer;

use Study\Mod2\Observer\PromoSkuObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;

class ChangePromoSkuObserver
{
    private RequestInterface $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    public function aroundExecute(PromoSkuObserver $subject, callable $proceed, Observer $observer)
    {
        return $this->getRequest()->isAjax() ? null : $proceed($observer);
    }

    private function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
