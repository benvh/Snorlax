<?php
namespace Snorlax;

/**
 * Some kind of container for 'request' related stuff
 */
class Request {

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    private $_type;
    private $_path;
    private $_params;
    private $_headers;
    private $_data;
    private $_rawData;

    public function __construct($type, $path, $params, $headers, $data, $rawData) {
        $this->_type = $type;
        $this->_path = $path;
        $this->_params = $params;
        $this->_headers = $headers;
        $this->_data = $data;
        $this->_rawData = $rawData;
    }

    public function getHeader($name) {
        return array_key_exists($name, $this->_headers) ? $this->_headers[$name] : '';
    }

    public function getHeaders() {
        return $this->_headers;
    }

    public function getType() {
        return $this->_type;
    }

    public function getPath() {
        return $this->_path;
    }

    public function getInternalPath() {
        return $this->_internalPath;
    }

    public function getParams() {
        return $this->_params;
    }

    public function getParam($name) {
        return array_key_exists($name, $this->_params) ? $this->_params[$name] : '';
    }

    public function setParam($name, $value) {
        $this->_params[$name] = $value;
    }

    public function getData() {
        return $this->_data;
    }

}

?>
