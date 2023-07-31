<?php

declare(strict_types=1);

namespace Study\Mod1\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;

abstract class ConfigProviderAbstract
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var string
     */
    protected string $pathPrefix;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @throws LocalizedException
     */
    private function getPathPrefix(): string
    {
        if (empty($this->pathPrefix)) {
            $className = str_replace('\Interceptor', '', static::class);

            throw new LocalizedException(__('%1::$pathPrefix property should be initialized', $className));
        }

        return rtrim($this->pathPrefix, '/');
    }

    /**
     * @throws LocalizedException
     */
    protected function getValue(
        string $path,
        $scopeId = null,
        $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ): ?string {
        return $this->scopeConfig->getValue($this->getPathPrefix() . '/' . $path, $scope, $scopeId);
    }
}
