<?php

namespace PP\WebPortal\Module\Helper;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;

/**
 * http client helper.
 */
final class HttpClientHelper extends AbstractContainer
{
    private $errorMessages = [];

    public $result;

    /**
     * @param Psr\Http\Message\ResponseInterface $response
     *
     * @return bool
     */
    public function verifyResponse(ResponseInterface $response)
    {
        $this->result = json_decode((string) $response->getBody(), 1);

        if (!$this->hasErrors($response)) {
            return false;
        }

        return $this->result;
    }

    /**
     * @param ResponseInterface $response
     */
    private function hasErrors(ResponseInterface $response)
    {
        $log = [
                'getStatusCode' => $response->getStatusCode(),
                'body'          => (string) $response->getBody(),
            ];

        if ($response->getStatusCode() != 200) {
            $this->logger->emerg('getStatusCode != 200', $log);
            $this->errorMessages = ['title' => 'API Error'];

            return false;
        }
        $result = json_decode((string) $response->getBody(), 1);
        if (isset($result['errors'])) {
            $this->errorMessages = $result['errors'];
            //$this->logger->error('API return error', $log);

            return false;
        }

        return true;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}
