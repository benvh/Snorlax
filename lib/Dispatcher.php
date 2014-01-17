<?php
namespace Snorlax;

class Dispatcher {

    private $_requestHandlerManager;

    public function __construct($requestHandlerManager) {
        $this->_requestHandlerManager = $requestHandlerManager;
        $this->_context = new Context(5);
    }

    public function dispatch($request, $context) {
        $handlers = $this->_requestHandlerManager->getHandlers($request->getPath(), $request->getType());
        if(!empty($handlers)) {
            foreach($handlers as $handler) {
                $handler = $handler->bindTo($context, $context);
                $handler($request);
            }
        } else {
            //404 UP IN YO FACE
            echo '<h1>404 - Page not found</h1>';
        }
    }
}

?>