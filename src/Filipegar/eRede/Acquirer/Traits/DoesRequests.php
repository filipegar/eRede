<?php

namespace Filipegar\eRede\Acquirer\Traits;

trait DoesRequests
{
    /**
     * @return string JSON payload
     */
    public function toRequest()
    {
        $data = $this->jsonSerialize();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }

            if (is_object($value) && $key !== "threeDSecure") {
                /** @var \JsonSerializable $value */
                foreach ($value->jsonSerialize() as $prop => $val) {
                    if (!is_null($val)) {
                        $data[$prop] = $val;
                    }
                }
                unset($data[$key]);
            }
        }

        return json_encode($data);
    }
}
