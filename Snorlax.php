<?php

namespace Snorlax;

require 'lib/Util.php';
require 'lib/SplClassLoader.php';

$loader = new SplClassLoader('Snorlax', array('lib'));
$loader->register();


//wheels and cogs
$requestHandlerManager = new RequestHandlerManager();
$dispatcher = new Dispatcher($requestHandlerManager);

$request = Request::build();

function i_choose_you($context) {
    global $dispatcher, $request;

    $dispatcher->dispatch($request, $context);
}

function GET($path, $callback) {
    global $requestHandlerManager;

    $requestHandlerManager->addHandler(Util::trail_path($path), Request::GET, $callback);
}

function POST($path, $callback) {
    global $requestHandlerManager;

    $requestHandlerManager->addHandler(Util::trail_path($path), Request::POST, $callback);
}

function PUT($path, $callback) {
    global $requestHandlerManager;

    $requestHandlerManager->addHandler(Util::trail_path($path), Request::PUT, $callback);
}

function DELETE($path, $callback) {
    global $requestHandlerManager;

    $requestHandlerManager->addHandler(Util::trail_path($path), Request::DELETE, $callback);
}

?>