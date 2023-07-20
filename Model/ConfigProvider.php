<?php

namespace Study\Mod1\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider
{
    public const XML_PATH_IS_WELCOME_TEXT = 'Study_Section/Study_Group/Study_Field';
    private ScopeConfigInterface $scopeConfig;
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isWelcomeTextEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_IS_WELCOME_TEXT);
    }

}
