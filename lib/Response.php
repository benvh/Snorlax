<?php
namespace Snorlax;

class Response {

    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;

    private $_status;
    private $_body;
    private $_headers;

    public function __construct() {
        $this->_headers = array();
        $this->_body = '';
    }

    public function setStatus($statusCode) {
        $this->_status = $statusCode;
    }

    public function setHeader($header, $value) {
        $this->_headers[$header] = $value;
    }

    public function getHeader($header) {
        if(array_key_exists($header, $this->_headers)) {
            return $this->_headers[$header];
        }
        return '';
    }

    public function getHeaders() {
        return $this->_headers;
    }

    public function getStatus() {
        return $this->_status;
    }

    /**
     * Write stuff to the response body
     */
    public function write($stuff) {
        $this->_body .= $stuff;
    }

    /**
     * reset the response body conten
     */
    public function clear() {
        $this->_body = '';
    }

    public function getBody() {
        return $this->_body;
    }

}
?>
