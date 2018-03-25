<?php

namespace Filipegar\eRede\Acquirer;

class Card implements \JsonSerializable
{
    const TYPE_CREDITCARD = 'credit';
    const TYPE_DEBITCARD = 'debit';

    private $kind;
    private $cardHolderName;
    private $cardNumber;
    private $expirationMonth;
    private $expirationYear;
    private $securityCode;
    private $cardBin;
    private $last4;

    /**
     * @param $json
     *
     * @return Card
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $card = new Card();
        $card->populate($object);

        return $card;
    }

    public function populate(\stdClass $data)
    {
        $this->cardBin = isset($data->cardBin) ? $data->cardBin : null;
        $this->last4 = isset($data->last4) ? $data->last4 : null;
        $this->kind = isset($data->kind) ? $data->kind : null;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     * @return Card
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardHolderName()
    {
        return $this->cardHolderName;
    }

    /**
     * @param string $cardHolderName
     * @return Card
     */
    public function setCardHolderName($cardHolderName)
    {
        $this->cardHolderName = substr($cardHolderName, 0, 30);

        return $this;
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param string $cardNumber
     * @return Card
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpirationMonth()
    {
        return $this->expirationMonth;
    }

    /**
     * @param string $expirationMonth
     * @return Card
     */
    public function setExpirationMonth($expirationMonth)
    {
        $this->expirationMonth = intval($expirationMonth);

        return $this;
    }

    /**
     * @return string
     */
    public function getExpirationYear()
    {
        return $this->expirationYear;
    }

    /**
     * @param string $expirationYear
     * @return Card
     */
    public function setExpirationYear($expirationYear)
    {
        $this->expirationYear = intval($expirationYear);

        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * @param string $securityCode
     * @return Card
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardBin()
    {
        return $this->cardBin;
    }

    /**
     * @param mixed $cardBin
     */
    public function setCardBin($cardBin)
    {
        $this->cardBin = $cardBin;
    }

    /**
     * @return mixed
     */
    public function getLast4()
    {
        return $this->last4;
    }

    /**
     * @param mixed $last4
     */
    public function setLast4($last4)
    {
        $this->last4 = $last4;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
