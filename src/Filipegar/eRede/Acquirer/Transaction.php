<?php

namespace Filipegar\eRede\Acquirer;

use Filipegar\eRede\Acquirer\Requests\Requestable;
use Filipegar\eRede\Acquirer\Traits\DoesRequests;

class Transaction implements \JsonSerializable, Requestable
{
    use DoesRequests;

    const ORIGIN_EREDE = 1;
    const ORIGIN_VISACHECKOUT = 4;
    const ORIGIN_MASTERPASS = 6;

    private $capture;
    private $reference;
    private $softDescriptor;
    private $subscription;
    private $origin;
    private $distributorAffiliation;
    private $threeDSecure;
    private $urls;
    private $payment;
    private $card;
    private $tid;
    private $nsu;
    private $authorizationCode;
    private $dateTime;
    private $returnCode;
    private $returnMessage;
    private $links;
    private $status;

    public function __construct($reference = null, $origin = self::ORIGIN_EREDE)
    {
        $this->setReference($reference);
        $this->setOrigin($origin);
    }

    /**
     * @param $json
     *
     * @return Transaction
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $transaction = new Transaction();
        $transaction->populate($object);

        return $transaction;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->returnCode = isset($data->returnCode) ? $data->returnCode : null;
        $this->returnMessage = isset($data->returnMessage) ? $data->returnMessage : null;
        $this->reference = isset($data->reference) ? $data->reference : null;
        $this->tid = isset($data->tid) ? $data->tid : null;
        $this->nsu = isset($data->nsu) ? $data->nsu : null;
        $this->authorizationCode = isset($data->authorizationCode) ? $data->authorizationCode : null;
        $this->dateTime = isset($data->dateTime) ? \DateTime::createFromFormat(\DateTime::ISO8601, $data->dateTime)
            : null;
        $this->softDescriptor = isset($data->softDescriptor) ? $data->softDescriptor : null;
        $this->subscription = isset($data->subscription) ? boolval($data->subscription) : null;
        $this->status = isset($data->status) ? $data->status : null;

        $this->payment = new Payment();
        $this->payment->populate($data);

        $this->card = new Card();
        $this->card->populate($data);

        $this->threeDSecure = new ThreeDSecure();
        $this->threeDSecure->populate($data);

        if (isset($data->links)) {
            $this->links = [];
            foreach ($data->links as $link) {
                array_push($this->links, $link);
            }
        }
    }

    /**
     * @return string
     */
    public function getCapture()
    {
        return $this->capture;
    }

    /**
     * @param string $capture
     * @return Transaction
     */
    public function setCapture($capture)
    {
        $this->capture = $capture;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return Transaction
     */
    public function setReference($reference)
    {
        if (!is_null($reference)) {
            $this->reference = substr($reference, 0, 16);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getSoftDescriptor()
    {
        return $this->softDescriptor;
    }

    /**
     * @param string $softDescriptor
     * @return Transaction
     */
    public function setSoftDescriptor($softDescriptor)
    {
        $this->softDescriptor = substr($softDescriptor, 0, 13);

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param string $subscription
     * @return Transaction
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return Transaction
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string
     */
    public function getDistributorAffiliation()
    {
        return $this->distributorAffiliation;
    }

    /**
     * @param string $distributorAffiliation
     * @return Transaction
     */
    public function setDistributorAffiliation($distributorAffiliation)
    {
        $this->distributorAffiliation = $distributorAffiliation;

        return $this;
    }

    /**
     * @return ThreeDSecure
     */
    public function getThreeDSecure()
    {
        return $this->threeDSecure;
    }

    /**
     * @param ThreeDSecure $threeDSecure
     * @return Transaction
     */
    public function setThreeDSecure(ThreeDSecure $threeDSecure)
    {
        $this->threeDSecure = $threeDSecure;

        return $this;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param Url $url
     * @return Transaction
     */
    public function setUrl(Url $url)
    {
        if (empty($this->urls)) {
            $this->urls = [];
        }

        array_push($this->urls, $url);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     * @return Transaction
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param mixed $card
     * @return Transaction
     */
    public function setCard(Card $card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * @param mixed $tid
     * @return Transaction
     */
    public function setTid($tid)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNsu()
    {
        return $this->nsu;
    }

    /**
     * @param mixed $nsu
     * @return Transaction
     */
    public function setNsu($nsu)
    {
        $this->nsu = $nsu;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed $authorizationCode
     * @return Transaction
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     * @return Transaction
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @param     $amount
     * @param int $installments
     *
     * @return Payment
     */
    public function payment($amount, $installments = 1)
    {
        $payment = new Payment($amount, $installments);

        $this->setPayment($payment);

        return $payment;
    }

    private function newCard($securityCode)
    {
        $card = new Card();
        $card->setSecurityCode($securityCode);

        return $card;
    }

    /**
     * @param $securityCode
     *
     * @return Card
     */
    public function creditCard($securityCode)
    {
        $card = $this->newCard($securityCode);

        $card->setKind(Card::TYPE_CREDITCARD);
        $this->setCard($card);

        return $card;
    }

    /**
     * @param $securityCode
     *
     * @return Card
     */
    public function debitCard($securityCode)
    {
        $card = $this->newCard($securityCode);

        $card->setKind(Card::TYPE_DEBITCARD);
        $this->setCard($card);

        return $card;
    }

    public function threeDSecure($mpi = ThreeDSecure::MPI_EREDE, $onFailure = ThreeDSecure::FAILURE_DECLINE, $directoryServerTransactionId = "",  $threeDIndicator = "2")
    {
        $threeDScure = new ThreeDSecure();

        $threeDScure->setEmbedded($mpi);
        $threeDScure->setOnFailure($onFailure);
        $threeDScure->setDirectoryServerTransactionId($directoryServerTransactionId);
        $threeDScure->setThreeDIndicator($threeDIndicator);
        $this->setThreeDSecure($threeDScure);

        return $threeDScure;
    }

    public function url($url, $urlKind)
    {
        $link = new Url();
        $link->setKind($urlKind);
        $link->setUrl($url);

        $this->setUrl($link);

        return $this;
    }
}