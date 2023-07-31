<?php

declare(strict_types=1);

namespace Study\Mod1\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

use Study\Mod1\Model\ConfigProvider;

class Index extends Template
{
    private ConfigProvider $configProvider;

    public function __construct(Context $context, ConfigProvider $configProvider, array $data = [])
    {
        $this->configProvider = $configProvider;
        parent::__construct($context, $data);
    }

    public function getWelcomeText(): string
    {
        return (string)$this->configProvider->getWelcomeText();
    }

    public function isModuleEnabled(): bool
    {
        return (bool)$this->configProvider->isModuleEnabled();
    }

    public function isQtyEnabled(): bool
    {
        return (bool)$this->configProvider->isQtyEnabled();
    }

    public function getQtyNumber(): int
    {
        return (int)$this->configProvider->getQtyNumber();
    }
}
