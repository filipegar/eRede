<?php

namespace Filipegar\eRede\Acquirer\Auth\Token;

interface TokenCacheInterface
{
    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl Time to live in seconds.
     *
     * @return void
     */
    public function set($key, $value, $ttl);

    /**
     * @param string $key
     *
     * @return void
     */
    public function delete($key);
}

