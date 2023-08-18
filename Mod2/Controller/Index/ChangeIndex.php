<?php

namespace Study\Mod2\Controller\Index;

use Study\Mod1\Controller\Index\Index as Index;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;

class ChangeIndex extends Index
{
    private ResultFactory $resultFactory;
    private Session $session;

    public function __construct(ResultFactory $resultFactory, Session $session)
    {
        $this->resultFactory = $resultFactory;
        $this->session = $session;

        parent::__construct($resultFactory);
    }

    public function execute()
    {
        if ($this->session->isLoggedIn()) {
            return parent::execute();
        } else {
            $page = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $page->setHttpResponseCode(404);

            return $page;
        }
    }


}
