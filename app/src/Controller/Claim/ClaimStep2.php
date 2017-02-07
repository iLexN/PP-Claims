<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\BankModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep2 extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/claim2bank.js','/assets/js/page/claim2cheque.js']];

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
            return $response->withStatus(301)
                ->withHeader('Location', $this->c['router']->pathFor('Main'));
        }

        $response = $this->checkH2($response);

        if ($claims['payment_method'] === 'Cheque') {
            $payto = $this->getPayTo($claims);
            $address = $this->getAddress();

            return $this->view->render($response, 'page/claim/step2cheque.twig', [
                'claim' => $claims,
                'address' => $address,
                'payto' => $payto,
                'token' => $this->csrfHelper->getToken($request),
            ]);
        } else {
            //Bank Transfer
            $this->banks = $this->userModule->getUserBank($this->userModule->user['ppmid']);
            $this->needPush($claims);
            $this->checkBankInfo();

            return $this->view->render($response, 'page/claim/step2bank.twig', [
                'claim' => $claims,
                'banks' => $this->banks,
                'token' => $this->csrfHelper->getToken($request),
            ]);
        }
    }

    private function getAddress()
    {
        $holderInfo = $this->holderModule->getHolderInfo($this->userModule->user['holder_id']);
        $address = $this->addressModule->getUserAddress($this->userModule->user['ppmid']);

        $address = $this->prepend($address, [
                'id' => 'holder',
                'nick_name' => $this->langText['member_PolicyAddr_title'],
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

    private function checkBankInfo()
    {
        if (empty($this->banks->data)) {
            $preference = $this->preferenceModule->getUserPreference($this->userModule->user['ppmid']);
            $this->banks->push(new BankModel([
                'currency' => $preference['currency'],
            ], $this->currencyText));
        }
    }
}
