<?php

declare(strict_types = 1);

namespace Study\Mod1\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template
{
    public function getWelcomeText():string
    {
        $welcomeText = "Hello World";
        return (string) __($welcomeText);
    }
}
