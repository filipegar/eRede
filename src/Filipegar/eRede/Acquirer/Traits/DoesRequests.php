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
        // brand é somente retorno da Rede, nunca é enviado.
        unset($data['brand']);

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
