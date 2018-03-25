<?php

namespace Filipegar\eRede\Acquirer\Requests;

class ERedeErrorException extends \Exception
{
    /**
     * eRedeErrorException constructor.
     *
     * @param $message
     * @param $code
     * @param null $previous
     */
    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
