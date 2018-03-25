<?php

namespace Filipegar\eRede\Acquirer\Requests;

use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Acquirer\QueryTransaction;
use Filipegar\eRede\Acquirer\Transaction;
use Filipegar\eRede\Merchant;

class QueryTransactionRequest extends AbstractRequest
{
    private $environment;

    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->environment = $environment;
    }

    /**
     * @param Requestable $transaction
     * @return mixed|null
     *
     * @throws eRedeErrorException
     */
    public function execute(Requestable $transaction)
    {
        /** @var Transaction $transaction */
        if (empty($transaction->getTid())) {
            $query = "?reference={$transaction->getReference()}";
            $transaction->setTid("");
        } else {
            $query = "";
        }

        return $this->sendRequest(
            $this->environment,
            'GET',
            "transactions/{$transaction->getTid()}{$query}",
            null
        );
    }

    protected function unserialize($response)
    {
        return QueryTransaction::fromJson((string)$response);
    }
}
