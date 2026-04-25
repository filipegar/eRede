<?php

namespace Filipegar\eRede\Acquirer\Auth\Token;

class NullTokenCache implements TokenCacheInterface
{
    /**
     * @param string $key
     *
     * @return null
     */
    public function get($key)
    {
        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     *
     * @return void
     */
    public function set($key, $value, $ttl)
    {
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
    }
}

