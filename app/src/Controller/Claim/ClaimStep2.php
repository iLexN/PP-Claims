<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\BankModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep2 extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/claim2bank.js', '/assets/js/page/claim2cheque.js']];

    public $preLoadKey = 'claimStep2';

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

        if ($claims['status'] !== 'Save') {
            return $response->withStatus(301)->withHeader('Location', $this->c['router']->pathFor('Claim.ListClaim', ['name'=>'submited-claim']));
        }

        $response = $this->checkH2($response);

        if ($claims['payment_method'] === 'Cheque') {
            return $this->view->render($response, 'page/claim/step2cheque.twig', [
                'claim'   => $claims,
                'address' => $this->getAddress($claims),
                'payto'   => $this->getPayTo($claims),
                'token'   => $this->csrfHelper->getToken($request),
                'sysText'    => $this->getSysMsg(),
            ]);
        } else {
            //Bank Transfer
            $this->getBank($claims);

            return $this->view->render($response, 'page/claim/step2bank.twig', [
                'claim' => $claims,
                'banks' => $this->banks,
                'token' => $this->csrfHelper->getToken($request),
                'sysText'    => $this->getSysMsg(),
            ]);
        }
    }

    private function getAddress($claims)
    {
        $holderInfo = $this->holderModule->getHolderInfo($this->userModule->user['holder_id']);
        $address = $this->addressModule->getUserAddress($this->userModule->user['ppmid']);

        $address = $this->prepend($address, [
                'id'             => 'holder',
                'nick_name'      => $this->langText['member_PolicyAddr_title'],
                'address_line_2' => $holderInfo['policy_address_line_2'],
                'address_line_3' => $holderInfo['policy_address_line_3'],
                'address_line_4' => $holderInfo['policy_address_line_4'],
                'address_line_5' => $holderInfo['policy_address_line_5'],
            ]);
        $address = $this->needPushAddress($claims, $address);

        return $address;
    }

    private function needPushAddress($claims, $address)
    {
        if (!empty($claims['cheque']) && !$this->chequeExist($claims, $address)) {
            $ar = $claims['cheque'];
            $ar['id'] = 'claim_cheque';
            $ar['ppmid'] = $this->userModule->user['ppmid'];
            $address = $this->prepend($address, $ar);
        }

        return $address;
    }

    private function getPayTo($claims)
    {
        if (!empty($claims['cheque'])) {
            return $claims['cheque']['name'];
        } else {
            return $this->userModule->user->fullName();
        }
    }

    private function chequeExist($claim, $address)
    {
        foreach ($address as $addr) {
            if ($claim['cheque']['address_line_2'] === $addr['address_line_2'] && $claim['cheque']['address_line_3'] === $addr['address_line_3']) {
                return true;
            }
        }

        return false;
    }

    private function prepend($address, $ar)
    {
        array_unshift($address, $ar);

        return $address;
    }

    private function getBank($claims)
    {
        $this->banks = $this->bankModule->getUserBank($this->userModule->user['ppmid']);
        $this->needPush($claims);
        $this->banks = $this->bankModule->checkBankInfo($this->banks);
    }

    private function needPush($claims)
    {
        if ($claims['bank_info'] === null) {
            return;
        }

        if (!$this->checkBankExist($claims, $this->banks)) {
            $b = $claims['bank_info'];
            $b['banker_transfer_id'] = null;
            $b['nick_name'] = $b['account_number'];
            $this->banks->push(new BankModel($b, $this->currencyText));
        }
    }

    private function checkBankExist($claims, $banks)
    {
        foreach ($banks as $b) {
            if ($b['account_number'] === $claims['bank_info']['account_number']) {
                return true;
            }
        }

        return false;
    }

    private function getSysMsg(){
        return [
            'sys_pb_3613_t'  => $this->langText['sys_pb_3613_t'],
            'sys_pb_3613_d'  => $this->langText['sys_pb_3613_d'],
            'sys_pb_mi_t'    => $this->langText['sys_pb_mi_t'],
            'sys_pb_mi_d'    => $this->langText['sys_pb_mi_d'],
            'sys_pb_rd_t'    => $this->langText['sys_pb_rd_t'],
            'sys_pb_rb_d'    => $this->langText['sys_pb_rb_d'],
            'sys_p_2626_t'   => $this->langText['sys_p_2626_t'],
            'sys_p_2626_d'   => $this->langText['sys_p_2626_d'],
            'sys_p_mi_t'     => $this->langText['sys_p_mi_t'],
            'sys_p_mi_d'     => $this->langText['sys_p_mi_d'],
            'sys_p_ad_t'     => $this->langText['sys_p_ad_t'],
            'sys_p_ad_d'     => $this->langText['sys_p_ad_d'],
            'sys_p_an_t'     => $this->langText['sys_p_an_t'],
            'sys_p_an_d'     => $this->langText['sys_p_an_d'],
        ];
    }
}
