<?php

namespace Study\Mod1\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;

class ConfigProvider extends ConfigProviderAbstract
{


    public const XML_PATH_IS_MODULE = 'study_group/module_status_field';
    public const XML_PATH_IS_WELCOME_TEXT = 'study_group/welcome_text_field';
    public const XML_PATH_IS_QTY = 'study_group/qty_status_field';
    public const XML_PATH_IS_QTY_NUMBER = 'study_group/default_qty_number';

    protected string $pathPrefix = 'study_section';

    /**
     * @throws LocalizedException
     */
    public function getWelcomeText(): string
    {
        return (string)$this->getValue(self::XML_PATH_IS_WELCOME_TEXT);
    }

    /**
     * @throws LocalizedException
     */
    public function isModuleEnabled(): bool
    {
        return (bool)$this->getValue(self::XML_PATH_IS_MODULE);
    }

    /**
     * @throws LocalizedException
     */
    public function isQtyEnabled(): bool
    {
        return (bool)$this->getValue(self::XML_PATH_IS_QTY);
    }

    /**
     * @throws LocalizedException
     */
    public function getQtyNumber(): int
    {
        return (int)$this->getValue(self::XML_PATH_IS_QTY_NUMBER);
    }
}
