<?php
namespace Snorlax;

class PathHandlerManager {

    private $_handlers;
    private $_dispatcher;

    public function __construct() {
        $this->_handlers = array();
    }

    public function addHandler($path, $type, $callback) {
        if(!array_key_exists($path, $this->_handlers)) {
            $this->_handlers[$path] = array();
            $this->_handlers[$path][$type] = array();
        } else if(!array_key_exists($type, $this->_handlers[$path])) {
            $this->_handlers[$path][$type] = array();
        }

        array_push($this->_handlers[$path][$type], new PathHandler($path, $type, $callback));
    }

    public function getAllRegisteredPaths() {
        $paths = array();

        foreach($this->_handlers as $path => $types) {
            array_push($paths, $path);
        }

        return $paths;
    }

    public function getHandler($path, $type) {
        if(array_key_exists($path, $this->_handlers)) {
            if(array_key_exists($type, $this->_handlers[$path])) {
                return $this->_handlers[$path][$type][0];
            }
        }
    }

    public function getHandlers($path, $type) {
        $handlers = array();
        foreach($this->_handlers as $handlersPath => $handlersArray) {

            if($path == $handlersPath) {
                if(array_key_exists($type, $handlersArray)) {
                    $handlers = array_merge($handlers, $handlersArray[$type]);
                }
            }
        }
        return $handlers;
    }

    public function getDispatcher() {
        if(!$this->_dispatcher) $this->_dispatcher = new MiddlewareDispatcher($this);
        return $this->_dispatcher;
    }

}
?>
