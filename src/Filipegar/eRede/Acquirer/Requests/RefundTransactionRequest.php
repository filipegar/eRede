<?php

namespace Filipegar\eRede\Acquirer\Requests;

use Filipegar\eRede\Acquirer\Auth\OAuthClientCredentialsAuthentication;
use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Acquirer\Refund;
use Filipegar\eRede\Merchant;

class RefundTransactionRequest extends AbstractRequest
{
    private $environment;

    public function __construct(Merchant $merchant, Environment $environment, ?OAuthClientCredentialsAuthentication $authentication = null)
    {
        parent::__construct($merchant, $authentication);

        $this->environment = $environment;
    }

    /**
     * @param Requestable $refund
     * @return mixed|null
     *
     * @throws eRedeErrorException
     */
    public function execute(Requestable $refund)
    {
        /** @var Refund $refund */
        return $this->sendRequest(
            $this->environment,
            'POST',
            "transactions/{$refund->getTid()}/refunds",
            $refund->toRequest()
        );
    }

    protected function unserialize($response)
    {
        return Refund::fromJson((string) $response);
    }
}
