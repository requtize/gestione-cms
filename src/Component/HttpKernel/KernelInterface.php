<?php

namespace Gestione\Component\HttpKernel;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gestione\Component\HttpKernel\Module\ModulesCollection;

interface KernelInterface
{
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true);
    public function terminate(Request $request, Response $response);
    public function getStartTime(): int;
    public function getModules(): ModulesCollection;
    public function getProjectDir(): string;
    public function getConfigDir(): string;
    public function getCacheDir(): string;
}
