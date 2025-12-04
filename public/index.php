<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

// Start output buffering early to catch any deprecation warnings
ob_start();

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$request = Request::capture();

// For AJAX/JSON requests, suppress display of errors to keep JSON clean
if ($request->ajax() || $request->wantsJson()) {
    $oldDisplayErrors = ini_get('display_errors');
    ini_set('display_errors', '0');
}

$response = $kernel->handle($request);

// Clean any output that was captured before sending response
if ($request->ajax() || $request->wantsJson()) {
    ob_end_clean();
    if (isset($oldDisplayErrors)) {
        ini_set('display_errors', $oldDisplayErrors);
    }
} else {
    ob_end_flush();
}

$response->send();

$kernel->terminate($request, $response);
