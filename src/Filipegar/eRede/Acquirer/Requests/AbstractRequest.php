<?php

namespace Filipegar\eRede\Acquirer\Requests;

use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Merchant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

/**
 * Class AbstractRequest
 *
 * @package Filipegar\eRede\Acquirer\Requests
 */
abstract class AbstractRequest
{
    private $merchant;

    /**
     * AbstractSaleRequest constructor.
     *
     * @param Merchant $merchant
     */
    public function __construct(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * @param Requestable $param
     *
     * @return mixed
     */
    abstract public function execute(Requestable $param);

    /**
     * @param Environment $environment
     * @param $method
     * @param $operation
     * @param \JsonSerializable|null $content
     *
     * @return mixed|null
     *
     * @throws eRedeErrorException
     */
    protected function sendRequest(Environment $environment, $method, $operation, $content = null)
    {
        $headers = [
            'User-Agent' => 'Filipegar-e.Rede/1.0 PHP SDK',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'RequestId' => uniqid(),
        ];

        $client = new Client([
            'base_uri' => $environment->getApiUrl(),
            'auth' => [$this->merchant->getAffiliation(), $this->merchant->getToken()],
            'headers' => $headers,
            'verify' => true,
            'defaults' => [
                'config' => [
                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
                    ]
                ]
            ]
        ]);

        if ($content !== null) {
            $options = [
                'body' => $content,
            ];
        } else {
            $options = [];
        }

        try {
            $response = $client->request($method, $operation, $options);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $response = $exception->getResponse();
                throw new ERedeErrorException($response->getBody(), $response->getStatusCode(), $exception);
            } else {
                throw new ERedeErrorException("Erro indeterminado.", 999, $exception);
            }
        }

        return $this->readResponse($response);
    }


    /**
     * @param Response $response
     *
     * @return mixed
     */
    protected function readResponse(Response $response)
    {
        return $this->unserialize($response->getBody());
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    abstract protected function unserialize($json);
}
