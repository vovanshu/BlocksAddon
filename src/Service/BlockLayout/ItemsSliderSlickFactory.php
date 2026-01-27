<?php
namespace BlocksAddon\Service\BlockLayout;

use Interop\Container\ContainerInterface;
use BlocksAddon\Site\BlockLayout\ItemsSliderSlick;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ItemsSliderSlickFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $class = new ItemsSliderSlick();
        $class->setServiceLocator($serviceLocator);
        return $class;
    }
}
