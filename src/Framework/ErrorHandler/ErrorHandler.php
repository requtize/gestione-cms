<?php

namespace Gestione\Framework\ErrorHandler;

use Whoops\Run;
use Whoops\Util\Misc;
use Whoops\Handler\Handler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\CallbackHandler;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    public static function register(bool $debug, string $logsDirectory)
    {
        $whoops = new Run;

        if($debug)
        {
            error_reporting(-1);
            @ ini_set('display_errors', 'On');

            if(Misc::isAjaxRequest())
            {
                $whoops->pushHandler(new JsonResponseHandler);
            }
            else
            {
                $handler = new PrettyPageHandler;
                $handler->addResourcePath(__DIR__.'/Resources/styles');
                $handler->addCustomCss('error-style.css');
                $handler->setEditor(function($file, $line) {
                    $file = str_replace('\\', '/', $file);

                    return str_replace([
                        '{file}',
                        '{line}'
                    ], [
                        $file,
                        $line
                    ], 'subl://{file}:{line}');
                });

                $whoops->pushHandler($handler);
            }
        }
        else
        {
            error_reporting(0);
            @ ini_set('display_errors', 'Off');

            error_reporting(-1);
            @ ini_set('display_errors', 'On');

            $whoops->pushHandler(new CallbackHandler(function($exception) use ($logsDirectory) {
                while(ob_get_level() > 0)
                    ob_end_clean();

                $logfile = $logsDirectory.date('\/Y\/m\/d').'/log-'.date('Y-m-d').'.txt';
                $logdir  = pathinfo($logfile, PATHINFO_DIRNAME);

                if(is_dir($logdir) === false)
                    mkdir($logdir, 0777, true);

                file_put_contents($logfile,
                    date('Y-m-d H:i:s')."\n".
                    $exception->getMessage().', in file '.$exception->getFile().', on line '.$exception->getLine().".\n".
                    $exception->getTraceAsString()."\n\n",
                FILE_APPEND);

                $response = new Response(file_get_contents(__DIR__.'/Resources/prod-env-error-page.tpl'), Response::HTTP_SERVICE_UNAVAILABLE);
                $response->sendHeaders();
                $response->sendContent();

                return Handler::QUIT;
            }));
        }

        $whoops->register();
    }
}
