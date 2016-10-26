<?php

namespace PP\WebPortal\Module\Helper;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;

/**
 * CSRF helper.
 */
final class CSRFHelper extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function getToken(ServerRequestInterface $request)
    {
        $nameKey = $this->c['csrf']->getTokenNameKey();
        $valueKey = $this->c['csrf']->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return [
                'nameKey'  => $nameKey,
                'name'     => $name,
                'valueKey' => $valueKey,
                'value'    => $value,
            ];
    }

    public function addResponseHeader($request,$response)
    {
        $ar = $this->getToken($request);
        $tokenArray = [
            $ar['nameKey']  => $ar['name'],
            $ar['valueKey'] => $ar['value'],
        ];
        $this->logger->info('helper', $tokenArray);

        return $response = $response->withAddedHeader('X-CSRF-Token', json_encode($tokenArray));
    }
}
