<?php
namespace BlocksAddon\Service\BlockLayout;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use BlocksAddon\Site\BlockLayout\PropertyListValues;

class PropertyListValuesFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
         $class = new PropertyListValues();
         $class->setServiceLocator($serviceLocator);
         return $class;
    }
}
