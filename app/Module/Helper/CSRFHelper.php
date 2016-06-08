<?php

namespace PP\Module\Helper;

use Psr\Http\Message\ServerRequestInterface;

/**
 * CSRF helper
 */
class CSRFHelper
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

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
}
