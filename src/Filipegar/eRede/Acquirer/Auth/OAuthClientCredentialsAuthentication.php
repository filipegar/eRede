<?php

namespace Filipegar\eRede\Acquirer\Auth;

use Filipegar\eRede\Acquirer\Auth\Token\ClientCredentialsTokenProvider;
use Filipegar\eRede\Acquirer\Auth\Token\TokenCacheInterface;
use Filipegar\eRede\Acquirer\Auth\Token\TokenProviderInterface;
use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Merchant;

class OAuthClientCredentialsAuthentication
{
    private $tokenProvider;

    public function __construct(?TokenProviderInterface $tokenProvider = null)
    {
        if ($tokenProvider === null) {
            $tokenProvider = new ClientCredentialsTokenProvider();
        }

        $this->tokenProvider = $tokenProvider;
    }

    /**
     * Helper to create OAuth authentication with optional token cache.
     *
     * @param TokenCacheInterface|null $cache
     *
     * @return self
     */
    public static function withCache(?TokenCacheInterface $cache = null)
    {
        return new self(new ClientCredentialsTokenProvider($cache));
    }

    /**
     * @param Environment $environment
     * @param Merchant $merchant
     * @param array $headers
     *
     * @return array
     */
    public function getClientOptions(Environment $environment, Merchant $merchant, array $headers)
    {
        $headers['Authorization'] = sprintf(
            'Bearer %s',
            $this->tokenProvider->getToken($environment, $merchant)
        );

        return [
            'base_uri' => $environment->getApiUrl(),
            'headers' => $headers,
            'verify' => true,
            'defaults' => [
                'config' => [
                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                    ],
                ],
            ],
        ];
    }
}

