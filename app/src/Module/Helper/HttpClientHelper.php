<?php

namespace PP\WebPortal\Module\Helper;

use PP\WebPortal\AbstractClass\AbstractContainer;
use GuzzleHttp\Psr7\Response;

/**
 * http client helper.
 */
final class HttpClientHelper extends AbstractContainer
{
    /**
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return bool
     */
    public function verifyResponse(Response $response)
    {
        $log = [
                'getStatusCode' => $response->getStatusCode(),
                'body'          => (string) $response->getBody(),
            ];
        $this->c->logger->info('post file response', $log);

        if ($response->getStatusCode() != 200) {
            $this->c->logger->error('getStatusCode != 200', $log);

            return false;
        }

        $result = json_decode((string) $response->getBody(), 1);
        if (isset($result['errors'])) {
            $this->c->logger->error('post file response', $log);

            return false;
        }

        return $result;
    }
}
