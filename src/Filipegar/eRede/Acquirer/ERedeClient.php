<?php

namespace Filipegar\eRede\Acquirer;

use Filipegar\eRede\Acquirer\Requests\CreateTransactionRequest;
use Filipegar\eRede\Merchant;

/*
 * e.Rede Client Front-end
 */

class ERedeClient
{
    private $merchant;
    private $environment;

    /**
     * Create an instance of eRedeClient choosing the environment where the
     * requests will be send
     *
     * @param Merchant $merchant
     *            The merchant credentials (PV identification and token)
     * @param Environment environment
     *            The environment: {@link Environment::production()} or
     *            {@link Environment::sandbox()}
     */
    public function __construct(Merchant $merchant, Environment $environment = null)
    {
        if ($environment == null) {
            $environment = Environment::production();
        }

        $this->merchant = $merchant;
        $this->environment = $environment;
    }

    public function authorize(Transaction $transaction)
    {
        $createTransaction = new CreateTransactionRequest($this->merchant, $this->environment);

        return $createTransaction->execute($transaction);
    }
}
