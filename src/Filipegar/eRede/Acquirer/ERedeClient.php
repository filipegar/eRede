<?php

namespace Filipegar\eRede\Acquirer;

use Filipegar\eRede\Acquirer\Requests\CaptureTransactionRequest;
use Filipegar\eRede\Acquirer\Requests\CreateTransactionRequest;
use Filipegar\eRede\Acquirer\Requests\QueryTransactionRequest;
use Filipegar\eRede\Acquirer\Requests\RefundTransactionRequest;
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
     * requests will be send.
     *
     * @param Merchant $merchant
     *            The merchant credentials (PV identification and token).
     * @param Environment environment
     *            The environment: {@link Environment::production()} or
     *            {@link Environment::sandbox()}.
     */
    public function __construct(Merchant $merchant, Environment $environment = null)
    {
        if ($environment == null) {
            $environment = Environment::production();
        }

        $this->merchant = $merchant;
        $this->environment = $environment;
    }

    /**
     * Authorizes a card or Authorize and Capture a card transaction.
     *
     * @param Transaction $transaction
     *          The built transaction.
     * @return Transaction
     *          A new transaction object with TID, NSU, authorizationCode, etc from Rede.
     *
     * @throws Requests\eRedeErrorException if anything gets wrong.
     * @see <a href=
     *      "https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#documentacao-codigos">Error
     *      Codes</a>
     */
    public function authorize(Transaction $transaction)
    {
        $createTransaction = new CreateTransactionRequest($this->merchant, $this->environment);

        return $createTransaction->execute($transaction);
    }

    /**
     * Captures a card transaction.
     *
     * @param $transactionTid
     *          The transaction TID gerenated by Rede.
     * @param $amount
     *          The total amount you would like to capture from the card.
     * @return Transaction
     *          A new transaction object with the same TID, but with a new NSU and authorizationCode from Rede.
     *
     * @throws Requests\eRedeErrorException if anything gets wrong.
     * @see <a href=
     *      "https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#documentacao-codigos">Error
     *      Codes</a>
     */
    public function captureTransaction($transactionTid, $amount)
    {
        $transaction = (new Transaction())->setTid($transactionTid);
        $transaction->payment($amount);

        $captureTransaction = new CaptureTransactionRequest($this->merchant, $this->environment);

        return $captureTransaction->execute($transaction);
    }

    /**
     * Refunds a transaction.
     *
     * @param Refund $refund
     *      The built refund object.
     *
     * @return Refund
     *      A new refund object with same TID, refundId, cancelId and NSU from Rede.
     *
     * @throws Requests\eRedeErrorException if anything gets wrong.
     * @see <a href=
     *      "https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#documentacao-codigos">Error
     *      Codes</a>
     */
    public function refundTransaction(Refund $refund)
    {
        $refundTransaction = new RefundTransactionRequest($this->merchant, $this->environment);

        return $refundTransaction->execute($refund);
    }

    /**
     * Query a transaction on Rede via TID.
     *
     * @param $transactionTid
     *      The transaction TID gerenated by Rede.
     *
     * @return QueryTransaction
     *      A Query Transaction object with a Transaction object, capture details and refunds (if any).
     * @throws Requests\eRedeErrorException
     */
    public function queryTransaction($transactionTid)
    {
        $transaction = (new Transaction())->setTid($transactionTid);

        $queryTransaction = new QueryTransactionRequest($this->merchant, $this->environment);

        return $queryTransaction->execute($transaction);
    }

    /**
     * Query a transaction on Rede via Store Reference.
     *
     * @param $storeReference
     *      The reference gerenated by you when sending the authorize request.
     *
     * @return QueryTransaction
     *      A Query Transaction object with a Transaction object, capture details and refunds (if any).
     * @throws Requests\eRedeErrorException
     */
    public function queryTransactionReference($storeReference)
    {
        $transaction = (new Transaction())->setReference($storeReference);

        $queryTransaction = new QueryTransactionRequest($this->merchant, $this->environment);

        return $queryTransaction->execute($transaction);
    }
}
