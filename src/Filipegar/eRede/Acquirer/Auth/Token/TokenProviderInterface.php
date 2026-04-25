<?php

namespace Filipegar\eRede\Acquirer\Auth\Token;

use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Merchant;

interface TokenProviderInterface
{
    /**
     * @param Environment $environment
     * @param Merchant $merchant
     *
     * @return string
     */
    public function getToken(Environment $environment, Merchant $merchant);
}

