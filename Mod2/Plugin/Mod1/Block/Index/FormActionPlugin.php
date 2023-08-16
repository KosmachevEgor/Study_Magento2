<?php

declare(strict_types=1);

namespace Study\Mod2\Plugin\Mod1\Block\Index;

use Study\Mod1\Block\Index;

class FormActionPlugin
{
    private const CART_FORM_ACTION = 'checkout/cart/add';

    public function afterGetFormAction(Index $subject, string $result): string
    {
        return self::CART_FORM_ACTION;
    }
}
