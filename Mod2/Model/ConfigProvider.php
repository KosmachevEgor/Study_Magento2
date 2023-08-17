<?php

declare(strict_types=1);

namespace Study\Mod2\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider
{
    public const XML_PATH_IS_MODULE = 'studyMod2_config/study_group2/module_status_field';
    public const XML_PATH_FOR_SKU = 'studyMod2_config/study_group2/forsku';
    public const XML_PATH_PROMO_SKU = 'studyMod2_config/study_group2/promosku';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isModuleEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_IS_MODULE);
    }

    public function getValueForSkus(): array
    {
        $forSkus = $this->scopeConfig->getValue((self::XML_PATH_FOR_SKU));
        $forSkus = explode(',', $forSkus);
        $forSkus = array_map('trim', $forSkus);

        return $forSkus;
    }

    public function getPromoSku(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PROMO_SKU);
    }


}
