<?php
namespace BlocksAddon\Service\Form;

use BlocksAddon\Form\BlockLayoutDataForm;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class BlockLayoutDataFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $form = new BlockLayoutDataForm;
        $form->setCurrentTheme($services->get('Omeka\Site\ThemeManager')->getCurrentTheme());
        $form->setViewHelpers($services->get('ViewHelperManager'));
        $form->setServiceLocator($services);
        return $form;
    }
}
