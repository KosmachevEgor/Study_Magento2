<?php

namespace Study\Mod1\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Blacklist extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('study_egor_blacklist', 'id');
    }
}
