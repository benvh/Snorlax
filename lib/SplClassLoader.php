<?php

namespace Snorlax;

class SplClassLoader {
    private static $NAMESPACE_SEPARATOR = "\\";
    private $_namespace;
    private $_includeDirectories;

    public function __construct($namespace, $includeDirectories) {
        $this->_namespace = $namespace;
        if(is_array($includeDirectories)) {
            $baseDir = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
            $this->_includeDirectories = array_map( function($dir) use($baseDir) {
                return realpath($baseDir . DIRECTORY_SEPARATOR . $dir);
            }, $includeDirectories);
        } else {
            $this->_includeDirectories = array();
        }
    }

    public function register() {
        spl_autoload_register(array($this, 'load'));
    }

    public function load($class) {
        $namespace = '';
        $className = $class;

        if(($namespacePos = strripos($class, static::$NAMESPACE_SEPARATOR)) !== false) {
            $namespace = substr($class, 0, $namespacePos);
            $className = substr($class, $namespacePos + 1);

            $namespace = preg_replace("/" . preg_quote($this->_namespace) . "(" . preg_quote(static::$NAMESPACE_SEPARATOR) . ")?/", '', $namespace);
        }

        $file = strtolower( str_replace(static::$NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $namespace) ) . DIRECTORY_SEPARATOR . $className . '.php';

        foreach($this->_includeDirectories as $dir) {
            if(file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
                require $dir . DIRECTORY_SEPARATOR . $file;
                break;
            }
        }
    }
}

?>
