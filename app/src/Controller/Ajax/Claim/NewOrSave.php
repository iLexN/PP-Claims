<?php

namespace PP\WebPortal\Controller\Ajax\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class NewOrSave extends AbstractContainer
{
    /**
     *
     * @var \PP\WebPortal\Module\Model\ClaimModel
     */
    private $claim;

    public function __invoke(Request $request, Response $response, array $args)
    {

        $this->claim = $this->claimModule->newClaim((array) $request->getParsedBody());

        //$this->claim->checkCheque();
        //$this->claim->checkBank();
        $this->claim->checkAmount();

        $result = $this->claimModule->postClaimByAPI($this->claim->toArray(), $this->getApiUrl());

        $this->logger->info('p',(array)$request->getParsedBody());

        if (!$result) {
            $this->logger->info('e', $this->httpHelper->getErrorMessages());
        }

        $this->claimModule->clearClamID($result['data']['id']);

        return $response->withJson($result);
    }

    private function getApiUrl()
    {
        if ($this->claim['claim_id'] === null) {
            return 'user-policy/'.$this->claim['user_policy_id'].'/claim';
        } else {
            return 'claim/'. $this->claim['claim_id'];
        }
    }
}
