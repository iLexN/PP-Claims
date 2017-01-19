<?php

namespace PP\WebPortal\Middleware;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class CheckClaim extends AbstractContainer
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

        $route = $request->getAttribute('route');
        $arguments = $route->getArguments();

        $claims = $this->claimModule->getClaim($arguments['id']);
        $this->logger->info($claims['status']);

        if ($claims['status'] !== 'Save') {
            return $response->withStatus(301)
                ->withHeader('Location', $this->c['router']->pathFor('Main'));
        }

        if (!$this->isBelowToUser($claims['user_policy_id'], $polices)) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        return $next($request, $response);
    }

    private function isBelowToUser($id, $polices)
    {
        if ($polices[$id] === null) {
            return false;
        }

        return true;
    }
}
