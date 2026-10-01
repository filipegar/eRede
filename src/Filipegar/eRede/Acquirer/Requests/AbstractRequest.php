<?php

namespace Filipegar\eRede\Acquirer\Requests;

use Filipegar\eRede\Acquirer\Auth\OAuthClientCredentialsAuthentication;
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
    private $authentication;

    /**
     * AbstractSaleRequest constructor.
     *
     * @param Merchant $merchant
     */
    public function __construct(Merchant $merchant, ?OAuthClientCredentialsAuthentication $authentication = null)
    {
        $this->merchant = $merchant;

        if ($authentication === null) {
            $authentication = new OAuthClientCredentialsAuthentication();
        }

        $this->authentication = $authentication;
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

        $clientOptions = $this->authentication->getClientOptions($environment, $this->merchant, $headers);
        $client = new Client($clientOptions);

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
                $body = json_decode((string) $response->getBody());

                $message = isset($body->returnMessage) ? $body->returnMessage : "Erro indeterminado.";
                $code = isset($body->returnCode) ? $body->returnCode : $response->getStatusCode();

                throw new ERedeErrorException($message, $code, $exception);
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
