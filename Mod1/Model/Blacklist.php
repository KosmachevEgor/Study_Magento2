<?php

namespace Study\Mod1\Model;

use Magento\Framework\Model\AbstractModel;

class Blacklist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Blacklist::class);

        parent::_construct();
    }
}
