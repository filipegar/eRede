<?php

namespace Filipegar\eRede;

/**
 * Class Merchant
 *
 * @package Filipegar\eRede
 */
class Merchant
{
    private $affiliation;
    private $token;

    /**
     * Merchant constructor.
     *
     * @param $affiliation
     * @param $token
     */
    public function __construct($affiliation, $token)
    {
        $this->affiliation  = $affiliation;
        $this->token = $token;
    }
    
    /**
     * Gets the merchant affiliation number
     *
     * @return string the merchant affiliation number on Rede
     */
    public function getAffiliation()
    {
        return $this->affiliation;
    }
    
    /**
     * Gets the merchant identification token
     *
     * @return string the merchant identification token on Rede
     */
    public function getToken()
    {
        return $this->token;
    }
}
