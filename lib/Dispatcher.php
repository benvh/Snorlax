<?php
namespace Snorlax;

class Dispatcher {

    private $_pathHandlerManager;
    private $_pathResolver;

    public function __construct($pathHandlerManager) {
        $this->_pathHandlerManager = $pathHandlerManager;
    }

    public function dispatch($request, $response, $context) {
        $this->buildPathResolver();

        $path = $this->_pathResolver->resolve($request->getPath());

        if($path) {
            $handler = $this->_pathHandlerManager->getHandler($path, $request->getType());

            if($handler) {
                //we've found a matching path with a handler, now it's time to bind the path variables (if there are any...)
                $urlParts = Util::explode_path($request->getPath());
                $pathParts = Util::explode_path($path);

                foreach($pathParts as $i => $pathPart) {
                    if(substr($pathPart, 0, 1) == '{') {
                        $request->setParam( substr($pathPart, 1, strlen($pathPart) - 2), $urlParts[$i] );
                    }
                }

                $handler = $handler->getCallback()->bindTo($context, $context);
                $handler($request, $response);
            } else {
                $response->setStatus(Response::METHOD_NOT_ALLOWED);
                $response->clear();
                $response->write('<h1>405 - Method not allowed >:(</h1>');
            }
        } else {
            $response->setStatus(Response::NOT_FOUND);
            $response->clear();
            $response->write('<h1>404 - Page not found :(</h1>');
        }

    }

    private function buildPathResolver() {
        $dispatcherRootNode = new PathNode('/');
        foreach($this->_pathHandlerManager->getAllRegisteredPaths() as $registeredPath) {
            $dispatcherRootNode->addPath($registeredPath);
        }

        $this->_pathResolver = new PathResolver($dispatcherRootNode);
    }
}

?>
