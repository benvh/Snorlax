<?php
namespace Snorlax;

/**
 * Simple container for the path handler callbacks. Just to keep the path and type info...
 */
class PathHandler {

    private $_path;
    private $_pathParams;
    private $_type;
    private $_callback;

    public function __construct($path, $type, $callback) {
        $this->_path = $path;
        $this->_type = $type;
        $this->_callback = $callback;

        $this->_pathParams = array();
        $pathParts = array_filter( explode('/', $path) );

        foreach($pathParts as $pathPart) {
            if(preg_match('/\{.*\}/', $pathPart)) {
                array_push($this->_pathParams, substr($pathPart, 1, strlen($pathPart) - 2));
            }
        }
    }

    public function getPath() {
        return $this->_path;
    }

    public function getType() {
        return $this->_type;
    }

    public function getCallback() {
        return $this->_callback;
    }

    public function getPathParams() {
        return $this->_pathParams;
    }
}

?>
