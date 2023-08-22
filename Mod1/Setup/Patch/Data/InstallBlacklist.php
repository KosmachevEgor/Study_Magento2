<?php

declare(strict_types=1);

namespace Study\Mod1\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Study\Mod1\Model\BlacklistFactory;
use Study\Mod1\Model\ResourceModel\Blacklist as ResourceBlacklist;

class InstallBlacklist implements DataPatchInterface
{
    /**
     * @var BlacklistFactory
     */
    private $blacklistFactory;

    /**
     * @var ResourceBlacklist
     */
    private $resourceBlacklist;

    public function __construct(
        BlacklistFactory         $blacklistFactory,
        ResourceBlacklist        $resourceBlacklist,
    ) {
        $this->blacklistFactory = $blacklistFactory;
        $this->resourceBlacklist = $resourceBlacklist;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $products = [
            'SimpleProd1' => 11,
            'Simple2' => 320,
            'Simple3' => 50
        ];

        foreach ($products as $sku => $qty) {
            $blacklist = $this->blacklistFactory->create();
            $blacklist->setSku($sku);
            $blacklist->setQty($qty);
            $this->resourceBlacklist->save($blacklist);
        }
    }
}
