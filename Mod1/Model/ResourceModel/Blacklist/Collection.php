<?php

namespace Study\Mod1\Model\ResourceModel\Blacklist;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Study\Mod1\Model\Blacklist;
use Study\Mod1\Model\ResourceModel\Blacklist as ResourceBlacklist;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Blacklist::class,
            ResourceBlacklist::class
        );

        parent::_construct();
    }
}
