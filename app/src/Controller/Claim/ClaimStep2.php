<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PP\WebPortal\Module\Model\BankModel;

final class ClaimStep2 extends AbstractContainer
{
    private $preLoad = ['script'=>['/assets/js/page/claim2.js']];

    private $preLoadKey = 'claimStep2';

    private $banks;
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
        $claims = $this->claimModule->getClaim($args['id']);
        $this->banks = $this->userModule->getUserBank($this->userModule->user['ppmid']);

        $this->needPush($claims);
        $this->checkBankInfo();
        $response = $this->checkH2($response);

        return $this->view->render($response, $this->getTemplate($claims), [
            'claim' => $claims,
            'banks' => $this->banks,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }

    private function needPush($claims){

        if ( $claims['bank_info'] === null) {
            return ;
        }

        $this->logger->info('bank',$claims['bank_info']);

        if ( !$this->checkBankExist($claims, $this->banks) ){
            $b = $claims['bank_info'];
            $b['banker_transfer_id'] = null;
            $b['nick_name'] = $b['account_number'];
            $this->banks->push(new BankModel($b, $this->currencyText));
        }
    }

    private function checkBankExist($claims,$banks){
        foreach ( $banks as $b ) {
            if ( $b['account_number'] === $claims['bank_info']['account_number']) {
                return true;
            }
        }
        $this->logger->info('false');
        return false;
    }

    private function checkBankInfo(){
        if (empty($this->banks->data)) {
            $preference = $this->userModule->getUserPreference($this->userModule->user['ppmid']);
            $this->banks->push(new BankModel([
                'currency' =>$preference['currency'],
            ], $this->currencyText));
        }
    }

    private function getTemplate($claim)
    {
        if ($claim['payment_method'] === 'Cheque') {
            return 'page/claim/step2cheque.twig';
        } else {
            // Bank transfer
            return 'page/claim/step2bank.twig';
        }
    }
}
