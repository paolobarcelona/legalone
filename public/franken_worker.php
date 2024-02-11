<?php
// public/index.php

// Prevent worker script termination when a client connection is interrupted
ignore_user_abort(true);

// Boot your app
require '../vendor/autoload.php';

$myApp = new \App\Kernel('dev', false);
$myApp->boot();

// Handler outside the loop for better performance (doing less work)
$handler = static function () use ($myApp) {
        // Called when a request is received,
        // superglobals, php://input and the like are reset
        echo $myApp->handle($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
};
for (
    $nbRequests = 0, $running = true;
    $nbRequests < 5 && $running;
    ++$nbRequests
) {
    $running = \frankenphp_handle_request($handler);

    // Do something after sending the HTTP response
    $myApp->terminate();

    // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
    gc_collect_cycles();
}
// Cleanup
$myApp->shutdown();
