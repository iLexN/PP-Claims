<?php

namespace PP\Claims\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Logined
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
     * Login-ed Page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->c['view']->render($response, 'logined.html.twig', [
            'sysMsg' => 'Logined',
            'User'   => $this->c['user'],
            'Polices' => $this->getPolices(),
        ]);
    }

    private function getPolices(){

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

    private function getPoliciesByAPI($id){
        $response = $this->c['httpClient']->request('GET', 'user/'.$id. '/policy');

        $result = $this->c['httpHelper']->verifyResponse($response);

        return $result['data'];
    }
}
