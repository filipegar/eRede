<?php

namespace Filipegar\eRede\Acquirer;

class Payment implements \JsonSerializable
{
    private $amount;
    private $installments;

    public function __construct($amount = 0, $installments = 1)
    {
        $this->setAmount($amount);
        $this->setInstallments($installments);
    }

    /**
     * @param $json
     *
     * @return Payment
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $payment = new Payment();
        $payment->populate($object);

        return $payment;
    }

    public function populate(\stdClass $data)
    {
        $this->amount = isset($data->amount) ? $data->amount : null;
        $this->installments = isset($data->installments) ? $data->installments : 1;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = intval($amount);

        return $this;
    }

    /**
     * @return string
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @param string $installments
     * @return Payment
     */
    public function setInstallments($installments)
    {
        $this->installments = $installments > 1 ? $installments : null;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
