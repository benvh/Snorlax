<?php
namespace Snorlax;

//ICKY STICKY CODE
class PathMatcher {

    private $_root;
    private $_matches;

    public function __construct($rootNode) {
        $this->_root = $rootNode;
    }

    /*
     * Tries find all matching internal paths for a given path.
     */
    public function match($path) {
        $this->_matches = array();

        $nodeStack = array();

        if($path == '/') {
            if($wildcard = $this->_root->getChild('*')) array_push($this->_matches, '/*/');
        } else {
            $this->visit($this->_root, $nodeStack, $path);
        }

        return $this->_matches;
    }

    //GGGNNAAAAAA
    //TODO: Make this thing better?
    private function visit($node, &$nodeStack, $path) {

        $part = substr($path, 1, strpos($path, '/', 1) - 1);
        $newPath = substr($path, strlen($part) + 1);

        $matchingNodes = array('nameNode' => false, 'variableNode' => false, 'wildcardNode' => false); //also the actual order in which they are checked!

        //
        $jump = function($node, &$stack, $path) {
            array_push($stack, $node);
            if($path != '/' && !$this->visit($node, $stack, $path)) {
                array_pop($stack);
                return false;
            }

            if($path == '/') {
                array_push($this->_matches,  Util::implode_path( array_map(function($n) { return $n->getName(); }, $stack) ));
            }
            array_pop($stack);

            return true;
        };

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

        if($matchingNodes['wildcardNode']) {
            array_push($nodeStack, $matchingNodes['wildcardNode']);
            array_push($this->_matches,  Util::implode_path( array_map(function($n) { return $n->getName(); }, $nodeStack) ));
            array_pop($nodeStack);
        }

        foreach($matchingNodes as $nodeName => $node) {
            if($node) {
                if($nodeName == 'variableNode') {
                    foreach($node as $variableNode) {
                        //BLURGH
                        if($jump($variableNode, $nodeStack, $newPath)) {
                            return true;
                        }
                    }
                } else if($nodeName == 'nameNode') {
                    //BLURGH
                    $matched = false;
                    if($jump($node, $nodeStack, $newPath)) {
                        return true;
                    }
                }
            }

        }

        return false;
    }

}


?>
