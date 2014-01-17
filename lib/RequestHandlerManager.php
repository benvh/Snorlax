<?php
namespace Snorlax;

class RequestHandlerManager {

    private $_handlers;

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

        array_push($this->_handlers[$path][$type], $callback);
    }

    public function getHandlers($path, $type) {
        if(array_key_exists($path, $this->_handlers)) {
            if(array_key_exists($type, $this->_handlers[$path])) {
                return $this->_handlers[$path][$type];       
            }
        }
        return null;         
    }

}
?>