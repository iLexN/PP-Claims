<?php

namespace PP\WebPortal\Middleware\Dev;

use JShrink\Minifier;
use MatthiasMullie\Minify;
use PP\WebPortal\AbstractClass\AbstractContainer;
use Symfony\Component\Finder\Finder;

class Dev extends AbstractContainer
{
    /**
     * dev for min js/css.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $this->minJS($request->getServerParams()['DOCUMENT_ROOT']);
        $this->minCSS($request->getServerParams()['DOCUMENT_ROOT']);

        return $response = $next($request, $response);
    }

    private function minJS($docRoot)
    {
        $finder = new Finder();
        $folder = $docRoot.'/assets/js/';
        $finder->files()->in($folder)->exclude('test')->name('*.js')->notName('*.min.js');
        /* @var $file \Symfony\Component\Finder\SplFileInfo */
        foreach ($finder as $file) {
            $minifiedCode = Minifier::minify($file->getContents());
            $newFile = $folder.$file->getRelativePath().'/'.$file->getBasename('.js').'.min.js';
            file_put_contents($newFile, $minifiedCode);
        }
    }

    private function minCSS($docRoot)
    {
        $finder = new Finder();
        $folder = $docRoot.'/assets/css/';
        $finder->files()->in($folder)->name('*.css')->notName('*.min.css')->
                sort(function (\SplFileInfo $a) {
                    if ($a->getBasename() == 'normalize.css') {
                        return false;
                    }

                    return true;
                });
        $minifier = new Minify\CSS();
        /* @var $file \Symfony\Component\Finder\SplFileInfo */
        foreach ($finder as $file) {
            $newFile = $folder.$file->getRelativePathname();
            $minifier->add($newFile);
        }
        $minifier->minify($folder.'build.min.css');
    }
}