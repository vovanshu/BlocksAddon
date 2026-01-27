<?php
namespace BlocksAddon\Service\BlockLayout;

use Interop\Container\ContainerInterface;
use BlocksAddon\Site\BlockLayout\ItemsList;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ItemsListFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $class = new ItemsList();
        $class->setServiceLocator($serviceLocator);
        return $class;
    }
}
