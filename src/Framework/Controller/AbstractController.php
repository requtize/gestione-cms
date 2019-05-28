<?php

namespace Gestione\Framework\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

abstract class AbstractController implements ContainerAwareInterface
{
    use ControllerTrait;
    use ContainerAwareTrait;
}
