<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Gestione\Framework\ErrorHandler\ErrorHandler;
use Gestione\Component\HttpFoundation\Request;
use Gestione\Component\HttpKernel\Kernel;
use Gestione\Component\Stdlib\Parameters;

if(is_file(__DIR__.'/../vendor/autoload.php'))
    include __DIR__.'/../vendor/autoload.php';
else
    die('Autoload file not exists.');

(new Dotenv(false))->loadEnv(dirname(__DIR__).'/.env');

ErrorHandler::register(
    getenv('GESTIONE_APP_DEBUG') === 'true',
    realpath(__DIR__.'/../var/logs')
);

$request = Request::createFromGlobals();

/**
 * If someone goes to index.php directly, we reditect him to the page without it.
 */
if(strpos($_SERVER['REQUEST_URI'], 'index.php') !== false)
{
    $newUri = str_replace('/index.php', '/', $_SERVER['REQUEST_URI']);
    $newUri = str_replace('//', '/', $newUri);

    $response = new RedirectResponse($request->getSchemeAndHttpHost().$newUri, 301);
    $response->send();
    exit();
}

$kernel = new Kernel(new Parameters([
    'project-dir' => realpath(__DIR__.'/../'),
    'config-dir'  => realpath(__DIR__.'/../config'),
    'cache-dir'   => realpath(__DIR__.'/../var/cache'),
    'logs-dir'    => realpath(__DIR__.'/../var/logs'),
    'public-dir'  => realpath(__DIR__.'/../public'),
    'uploads-dir' => realpath(__DIR__.'/../uploads'),
    'environment' => getenv('GESTIONE_APP_ENV'),
    'debug'       => getenv('GESTIONE_APP_DEBUG') === 'true'
]));
$response = $kernel->handle($request);
$response->sendHeaders();
$response->sendContent();
$kernel->terminate($request, $response);
