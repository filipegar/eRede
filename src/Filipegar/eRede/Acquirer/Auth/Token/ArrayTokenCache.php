<?php

namespace Filipegar\eRede\Acquirer\Auth\Token;

class ArrayTokenCache implements TokenCacheInterface
{
    private $items = [];

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        if (!isset($this->items[$key])) {
            return null;
        }

        $item = $this->items[$key];
        if ($item['expires_at'] !== null && $item['expires_at'] <= time()) {
            unset($this->items[$key]);
            return null;
        }

        return $item['value'];
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
        $expiresAt = null;
        if ($ttl > 0) {
            $expiresAt = time() + (int) $ttl;
        }

        $this->items[$key] = [
            'value' => $value,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
        unset($this->items[$key]);
    }
}

