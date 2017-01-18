<?php

namespace PP\WebPortal\Controller\Ajax\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class Delete extends AbstractContainer
{
    /**
     *
     * @var \PP\WebPortal\Module\Model\ClaimModel
     */
    private $claim;

    private $apiUrl;
    private $type;

    public function __invoke(Request $request, Response $response, array $args)
    {
        $fileInfo = (array)$request->getParsedBody();

        if ($fileInfo['id'] === $args['fid'] && $fileInfo['status'] === 'Upload') {

            $url = 'attachment/'. $fileInfo['id'] ;

            $response1 = $this->httpClient->request('POST', $url, [
                'form_params' => $fileInfo,
            ]);

            $this->httpHelper->verifyResponse($response1);

            $this->claimModule->clearClamID($args['id']);

            return $response->withJson($this->httpHelper->result);
        }

       return $response->withJson([]);
    }
}
