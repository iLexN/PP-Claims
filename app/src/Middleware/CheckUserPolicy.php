<?php

namespace PP\WebPortal\Middleware;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class CheckUserPolicy extends AbstractContainer
{
    /**
     * auto redirect when user already login.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $polices = $this->policyModule->getPolices();
        $user = $this->userModule->user;

        $route = $request->getAttribute('route');
        $arguments = $route->getArguments();

        if (!$this->isBelowToUser( $arguments['id'] ,$polices, $user) ){
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        return $next($request, $response);
    }

    private function isBelowToUser($id , $polices , $user)
    {
        foreach ($polices as $police){
            if ( $police->isSelectPolicy($id) && $police->getUserID() === $user['ppmid'] ) {
                return true;
            }
        }
        return false;
    }
}
