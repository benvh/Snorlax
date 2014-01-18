<?php
namespace Snorlax;

/**
 * Dispatcher that can do "middleware" stuff! Awesome!1
 */
class MiddlewareDispatcher {

    private $_dispatcher;
    private $_pathHandlerManager;

    private $_beforeHandlers;
    private $_afterHandlers;

    public function __construct($pathHandlerManager) {
        $this->_dispatcher = new Dispatcher($pathHandlerManager);
        $this->_pathHandlerManager = $pathHandlerManager;

        $this->_beforeHandlers = new PathHandlerManager();
        $this->_afterHandlers = new PathHandlerManager();
    }

    public function before($path, $type, $callback) {
        $this->_beforeHandlers->addHandler(Util::trail_path($path), $type, $callback);
    }

    public function after($path, $type, $callback) {
        $this->_afterHandlers->addHandler(Util::trail_path($path), $type, $callback);
    }

    public function dispatch($request, $response, $context) {
        if($this->dispatchBefore($request, $response, $context)) {
            $this->_dispatcher->dispatch($request, $response, $context);

            if($this->dispatchAfter($request, $response, $context)) {
                return true;
            }
        }

        return false;
    }

    private function dispatchBefore($request, $response, $context) {
        $beforeMatcher = $this->buildBeforeMatcher();

        $matchingPaths = $beforeMatcher->match($request->getPath());
        if($matchingPaths) {

            foreach($matchingPaths as $matchingPath) {
                $handler = $this->_beforeHandlers->getHandler($matchingPath, $request->getType());
                if($handler) {

                    $this->bindRequestPathParams($request, $matchingPath);

                    $handler = $handler->getCallback()->bindTo($context, $context);
                    $result = $handler($request, $response);

                    if($result === false) return false;
                }
            }
        }

        return true;
    }

    private function dispatchAfter($request, $response, $context) {
        $afterMatcher = $this->buildAfterMatcher();

        $matchingPaths = $afterMatcher->match($request->getPath());

        if($matchingPaths) {
            foreach($matchingPaths as $matchingPath) {
                $handler = $this->_afterHandlers->getHandler($matchingPath, $request->getType());
                if($handler) {

                    $this->bindRequestPathParams($request, $matchingPath);

                    $handler = $handler->getCallback()->bindTo($context, $context);
                    $result = $handler($request, $response);

                    if($result == false) return false;
                }
            }
        }

        return true;
    }





    private function buildBeforeMatcher() {
        $rootNode = new PathNode('/');
        foreach($this->_beforeHandlers->getAllRegisteredPaths() as $registeredPath) {
            $rootNode->addPath($registeredPath);
        }

        return new PathMatcher($rootNode);
    }

    private function buildAfterMatcher() {
        $rootNode = new PathNode('/');
        foreach($this->_afterHandlers->getAllRegisteredPaths() as $registeredPath) {
            $rootNode->addPath($registeredPath);
        }

        return new PathMatcher($rootNode);
    }

    private function bindRequestPathParams($request, $path) {
        $urlParts = Util::explode_path($request->getPath());
        $pathParts = Util::explode_path($path);

        foreach($pathParts as $i => $pathPart) {
            if(substr($pathPart, 0, 1) == '{') {
                $request->setParam( substr($pathPart, 1, strlen($pathPart) - 2), $urlParts[$i] );
            }
        }
    }

}

?>
