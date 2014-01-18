<?php
namespace Snorlax;


class Util {

    public static function trail_path($path) {
        if(substr($path, -1) != '/') return $path . '/';
        return $path;
    }

    public static function explode_path($path) {
        return array_values( array_filter( explode('/', $path) ) );
    }

    public static function implode_path($pathParts) {
        return '/' . implode('/', $pathParts) . '/';
    }

    public static function getallheaders() {
        $headers = array();

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$name] = $value;
            } else if ($name == "CONTENT_TYPE") {
                $headers["Content-Type"] = $value;
            } else if ($name == "CONTENT_LENGTH") {
                $headers["Content-Length"] = $value;
            }
        }

        return $headers;
    }

    public static function getrequestpath() {
        $path = $_SERVER['REQUEST_URI'];
        if(($params = strpos($path, '?'))) {
            $path = substr($path, 0, $params);
        }

        if(empty($path)) {
            $path = '/';
        } else {
            $path = static::trail_path($path);
        }

        return $path;
    }

}

?>
