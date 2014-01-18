<?php
//ICKY STICKY GLUE
namespace Snorlax;

require 'lib/Util.php';
require 'lib/SplClassLoader.php';

$loader = new SplClassLoader('Snorlax', array('lib'));
$loader->register();


//wheels and cogs
$pathHandlerManager = new PathHandlerManager();
$requestBuilder = new RequestBuilder($pathHandlerManager);

function i_choose_you($context) {
    global $pathHandlerManager, $requestBuilder;

    $dispatcher = $pathHandlerManager->getDispatcher();
    $request = $requestBuilder->build();

    //Every request starts of with a 200 response code...
    $response = new Response();
    $response->setStatus(Response::OK);
    $response->setHeader('X-Content-Type-Options', 'nosniff');

    //so it shall be done!
    $dispatcher->dispatch($request, $response, $context);

    //render the response object
    foreach($response->getHeaders() as $header => $value) {
        header($header . ': ' . $value);
    }
    http_response_code($response->getStatus());

    echo $response->getBody();
}


function GET($path, $callback) {
    global $pathHandlerManager;

    $pathHandlerManager->addHandler(Util::trail_path($path), Request::GET, $callback);
}

function POST($path, $callback) {
    global $pathHandlerManager;

    $pathHandlerManager->addHandler(Util::trail_path($path), Request::POST, $callback);
}

function PUT($path, $callback) {
    global $pathHandlerManager;

    $pathHandlerManager->addHandler(Util::trail_path($path), Request::PUT, $callback);
}

function DELETE($path, $callback) {
    global $pathHandlerManager;

    $pathHandlerManager->addHandler(Util::trail_path($path), Request::DELETE, $callback);
}

function before($path, $type, $callback) {
    global $pathHandlerManager;
    $dispatcher = $pathHandlerManager->getDispatcher();

    $dispatcher->before($path, $type, $callback);
}

function after($path, $type, $callback) {
    global $pathHandlerManager;
    $dispatcher = $pathHandlerManager->getDispatcher();

    $dispatcher->after($path, $type, $callback);
}

?>
