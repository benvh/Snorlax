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

    public static function build() {
        $type = $_SERVER['REQUEST_METHOD'];            
        $headers = Util::getallheaders();
        $path = Util::getrequestpath();
        $data = $_POST;
        $params = $_GET;
        $rawData = file_get_contents('php://input');

        if(strcasecmp($headers['Content-Type'], 'application/json') ){
            $data = json_decode($rawData);
        }

        return new Request($type, $path, $params, $headers, $data, $rawData);
    }

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

    public function getParams() {
        return $this->_params;
    }

    public function getParam($name) {
        return array_key_exists($name, $this->_params) ? $this->_params[$name] : '';
    }

}

?>