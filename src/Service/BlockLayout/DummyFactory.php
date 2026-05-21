<?php
namespace BlocksAddon\Service\BlockLayout;

use Interop\Container\ContainerInterface;
use BlocksAddon\Site\BlockLayout\Dummy;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DummyFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $serviceLocator, $requestedName, ?array $options = null)
    {
        return new Dummy();
    }
}
