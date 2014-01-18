<?php
namespace Snorlax;

class PathNode {

    private $_name;
    private $_children;

    public static function build($path) {
        $pathParts = Util::explode_path($path);

        $root = new PathNode($pathParts[0]);
        $node = $root;

        for($i = 1; $i < count($pathParts); $i++) {
            $node->addChild($pathParts[$i]);
            $node = $node->getChild($pathParts[$i]);
        }

        return $root;
    }

    public function __construct($name) {
        $this->_name = $name;
        $this->_children = array();
    }

    public function getName() {
        return $this->_name;
    }

    public function hasChildren() {
        return count($this->_children) > 0;
    }

    public function getChildren() {
        return $this->_children;
    }

    public function getChild($name) {
        if(array_key_exists($name, $this->_children)) {
            return $this->_children[$name];
        }
    }

    public function addChild($name) {
        $this->_children[$name] = new PathNode($name);
    }

    public function addPath($path) {
        $pathParts = Util::explode_path($path);

        $node = $this;
        foreach($pathParts as $part) {
            if(!$node->getChild($part)) {
                $node->addChild($part);
            }
            $node = $node->getChild($part);
        }

    }

}

?>
