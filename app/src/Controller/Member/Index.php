<?php

namespace PP\WebPortal\Controller\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Index extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/member.js']];

    public $preLoadKey = 'member';

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
        $people = $this->userModule->getPeopleList($this->userModule->user['ppmid']);

        $this->userModule->user['relationship'] = $people[$this->userModule->user['ppmid']]['relationship'];
        $people->sortByIdFirst($this->userModule->user['ppmid']);
        $holderInfo = $this->getHolder();

        //$p == logined user
        /* @var $p \PP\WebPortal\Module\Model\UserModel */
        $p = $people->getFirstData();
        $p->setReNew([]);

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/member/index.twig', [
            'people'     => $people,
            'hodlerInfo' => $holderInfo,
            'isHolder'   => $this->userModule->user->isHolder(),
            'token'      => $this->csrfHelper->getToken($request),
        ]);
    }

    private function getHolder()
    {
        $holderInfo = $this->holderModule->getHolderInfo($this->userModule->user['holder_id']);

        if ($holderInfo['renew'] !== null) {
            $out = $holderInfo['renew'];
            $out['renew'] = ture;
        } else {
            $out = $holderInfo;
            $out['renew'] = false;
        }

        return $out;
    }
}
