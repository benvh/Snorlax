<?php
namespace Snorlax;

class RequestBuilder {

    private $_pathHandlerManager; //we don't need this?

    public function __construct($pathHandlerManager) {
        $this->_pathHandlerManager = $pathHandlerManager;
    }

    public function build() {
        $type = $_SERVER['REQUEST_METHOD'];
        $headers = Util::getallheaders();
        $path = Util::getrequestpath();
        $data = $_POST;
        $params = $_GET;
        $rawData = file_get_contents('php://input');

        if(strcasecmp($headers['Content-Type'], 'application/json') == 0){
            $data = json_decode($rawData);
        }



        return new Request($type, $path, $params, $headers, $data, $rawData);
    }

}
?>
