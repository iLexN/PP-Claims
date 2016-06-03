<?php

namespace PP\Module;

/**
 * Description of PolicyModule.
 *
 * @author user
 */
class PolicyModule
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    public function getPolices()
    {
        $user = $this->c['user'];

        /* @var $item Stash\Interfaces\ItemInterface */
        $item = $this->c['pool']->getItem('User/'.$user['Client_NO'].'/Policies');
        $policies = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $policies = $this->getPoliciesByAPI($user['Client_NO']);
            $this->c['pool']->save($item->set($policies));
        }

        return $policies;
    }

    private function getPoliciesByAPI($id)
    {
        $response = $this->c['httpClient']->request('GET', 'user/'.$id.'/policy');
        $result = $this->c['httpHelper']->verifyResponse($response);

        return $result['data'];
    }
}
