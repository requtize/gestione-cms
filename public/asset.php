<?php

if(isset($_GET['action'])) {
    if($_GET['action'] == 'direct-access') {
        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 File not found.</h1>';

        exit;
    }
}

$basepath = realpath(__DIR__.'/../public');
$basepath = ! $basepath ? '/' : rtrim($basepath, '/');
$path     = isset($_GET['filepath']) ? $_GET['filepath'] : null;
$path     = '/'.trim($path, '/');
$path     = str_replace(' ', '+', $path);

$filepath  = $basepath.$path;
$filepath  = preg_replace('#[^a-z0-9\-\/\._:\+,=\\\\]#i', '', $filepath);
$filepath  = realpath($filepath);

if(! $filepath || strpos($filepath, $basepath) !== 0) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>404 File not found.</h1>';

    exit;
}

header_remove('X-Powered-By');

header('Pragma: public');
header('Cache-Control: max-age='.$cacheTime);
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $cacheTime));
header('X-Content-Type-Options: nosniff');
header('Content-Type: '.guessFileMimetype($filepath));
readfile($filepath);


function guessFileMimetype($filepath) {
    $forcedMimetypes = [
        'txt'  => 'text/plain',
        'htm'  => 'text/html',
        'html' => 'text/html',
        'php'  => 'text/html',
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'xml'  => 'application/xml',
        'png'  => 'image/png',
        'jpe'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
        'ico'  => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif'  => 'image/tiff',
        'svg'  => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai'  => 'application/postscript',
        'eps' => 'application/postscript',
        'ps'  => 'application/postscript',
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',
        'mp3' => 'audio/mpeg',
        'qt'  => 'video/quicktime',
        'mov' => 'video/quicktime',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
    ];

    $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

    return isset($forcedMimetypes[$extension]) ? $forcedMimetypes[$extension] : mime_content_type($filepath);
}
