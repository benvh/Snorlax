<?php
namespace Snorlax;

//ICKY STICKY CODE
class PathResolver {

    private $_root;
    private $_pathStack;

    public function __construct($root) {
        $this->_root = $root;
    }

    /**
     * Tries to resolve a path to a SINGLE matching internal path.
     * returns a resolved path or false if nothing was found.
     */
    public function resolve($path) {
        $this->_pathStack = array();

        if($path == '/') {
            return '/';
        } else {
            if($this->visit($this->_root, $path)) {
                $resolvedPath = '/' . implode('/', array_map(function($node) { return $node->getName(); }, $this->_pathStack)) . '/';
                return $resolvedPath;
            } else {
                return false;
            }
        }
        return false;
    }

    //GGGNNAAAAAA
    //TODO: Make this thing better?
    private function visit($node, $path) {
        $part = substr($path, 1, strpos($path, '/', 1) - 1);
        $newPath = substr($path, strlen($part) + 1);

        $matchingNodes = array('nameNode' => false, 'variableNode' => false, 'wildcardNode' => false); //also the actual order in which they are checked!

        /*
         * There are only 3 possible matching nodes...
         * 1. The one matching the actual path name (nodeNode)
         * 2. A variable node, looks like this -> {stuff}  (variableNode)
         * 3. A wildcard node, which is a *. The wildcard node will match itself and anything beyond it.
         */
        foreach($node->getChildren() as $name => $child) {
            if($name == $part) {
                $matchingNodes['nameNode'] = $child;
            } else if(substr($name, 0, 1) == '{') {
                if(!$matchingNodes['variableNode']) $matchingNodes['variableNode'] = array();
                array_push($matchingNodes['variableNode'], $child);
            } else if($name == '*') {
                $matchingNodes['wildcardNode'] = $child;
            }
        }

        foreach($matchingNodes as $nodeName => $node) {
            if($node) {
                if($nodeName == 'variableNode') {
                    foreach($node as $variableNode) {
                        array_push($this->_pathStack, $variableNode);
                        if($newPath != '/' && !$this->visit($variableNode, $newPath)) {
                            array_pop($this->_pathStack);
                        } else {
                            return true;
                        }
                    }
                } else {
                    array_push($this->_pathStack, $node);
                    if($newPath != '/' && !$this->visit($node, $newPath)) {
                        array_pop($this->_pathStack);
                    } else {
                        return true;
                    }
                }
            }

        }

        return false;
    }

}

?>
