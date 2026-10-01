<?php

namespace Filipegar\eRede\Acquirer\Auth\Token;

use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Acquirer\Requests\ERedeErrorException;
use Filipegar\eRede\Merchant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ClientCredentialsTokenProvider implements TokenProviderInterface
{
    private $cache;

    public function __construct(?TokenCacheInterface $cache = null)
    {
        if ($cache === null) {
            $cache = new NullTokenCache();
        }

        $this->cache = $cache;
    }

    /**
     * @param Environment $environment
     * @param Merchant $merchant
     *
     * @return string
     *
     * @throws ERedeErrorException
     */
    public function getToken(Environment $environment, Merchant $merchant)
    {
        $cacheKey = $this->buildCacheKey($environment, $merchant);
        $cachedToken = $this->cache->get($cacheKey);

        if (is_array($cachedToken)
            && isset($cachedToken['access_token'])
            && isset($cachedToken['expires_at'])
            && $cachedToken['expires_at'] > (time() + 30)
        ) {
            return $cachedToken['access_token'];
        }

        $tokenResponse = $this->requestToken($environment, $merchant);

        $expiresIn = isset($tokenResponse['expires_in']) ? (int) $tokenResponse['expires_in'] : 300;
        if ($expiresIn < 1) {
            $expiresIn = 300;
        }

        $tokenData = [
            'access_token' => $tokenResponse['access_token'],
            'expires_at' => time() + $expiresIn,
        ];

        $ttl = $expiresIn - 30;
        if ($ttl < 1) {
            $ttl = 1;
        }

        $this->cache->set($cacheKey, $tokenData, $ttl);

        return $tokenData['access_token'];
    }

    /**
     * @param Environment $environment
     * @param Merchant $merchant
     *
     * @return array
     * @throws ERedeErrorException
     */
    private function requestToken(Environment $environment, Merchant $merchant)
    {
        $client = new Client([
            'base_uri' => $environment->getOauthUrl(),
            'verify' => true,
            'defaults' => [
                'config' => [
                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                    ],
                ],
            ],
        ]);

        try {
            $response = $client->request('POST', $environment->getOauthTokenPath(), [
                'auth' => [$merchant->getAffiliation(), $merchant->getToken()],
                'headers' => [
                    'User-Agent' => 'Filipegar-e.Rede/1.0 PHP SDK',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $response = $exception->getResponse();
                $body = json_decode((string) $response->getBody());

                $message = isset($body->error_description) ? $body->error_description : 'Erro indeterminado.';
                $code = isset($body->error) ? $body->error : $response->getStatusCode();

                throw new ERedeErrorException($message, (int) $code, $exception);
            }

            throw new ERedeErrorException('Erro indeterminado.', 999, $exception);
        }

        $payload = json_decode((string) $response->getBody(), true);

        if (!is_array($payload) || !isset($payload['access_token'])) {
            throw new ERedeErrorException('Resposta invalida ao negociar o token OAuth.', 998, null);
        }

        return $payload;
    }

    /**
     * @param Environment $environment
     * @param Merchant $merchant
     *
     * @return string
     */
    private function buildCacheKey(Environment $environment, Merchant $merchant)
    {
        return sprintf(
            'erede.oauth.%s',
            md5($environment->getOauthUrl() . '|' . $environment->getOauthTokenPath() . '|' . $merchant->getAffiliation())
        );
    }
}

