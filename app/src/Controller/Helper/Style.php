<?php

namespace PP\WebPortal\Controller\Helper;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Style extends AbstractContainer
{
    /**
     * Router help.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        $contact = new \PP\WebPortal\Module\Model\ContactModel(\json_decode('{"contact_details_id":1,"region":"HK","region_full":"Hong Kong","tel_1":"852 3113 2112 (Chinese)","tel_2":"852 3113 1331 (English)","fax_1":"852 3113 2332","fax_2":"852 2915 7770","fax_3":"852 2915 6603","address_1":"Unit 1-11","address_2":"35th Floor","address_3":"One Hung To Road","address_4":"Kwun Tong","address_5":"Hong Kong","gmap_lat":"22.3148666","gmap_lng":"114.2189591","gmap_keyword":"Pacific Prime"}',1));

        return $this->view->render($response, 'helper/style.html.twig', [
            'contact' => $contact,
        ]);
    }
}
