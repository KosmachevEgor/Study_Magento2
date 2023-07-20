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
        $welcomeText = "Hello World";
        return (string) $welcomeText;
    }

    public function isWelcomeTextEnabled(): bool
    {
        return $this->configProvider->isWelcomeTextEnabled();
    }
}
