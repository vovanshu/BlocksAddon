<?php
namespace BlocksAddon;

if (!class_exists(\Common\TraitModule::class)) {
    require_once dirname(__DIR__) . '/Common/TraitModule.php';
}

if (!class_exists(Common::class)) {
    require_once __DIR__ . '/Common.php';
}

use Laminas\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Omeka\Module\AbstractModule;
use Omeka\Form\Element\LengthCssDataType;
use Common\TraitModule;
use BlocksAddon\Common;

class Module extends AbstractModule
{

    use TraitModule;

    use Common;

    const NAMESPACE = __NAMESPACE__;

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {

        // Copy ItemsBlockSlick-related data for the CopyResources module.
        // $sharedEventManager->attach(
        //     '*',
        //     'copy_resources.sites.post',
        //     function (Event $event) {
        //         $copyResources = $event->getParam('copy_resources');
        //         $siteCopy = $event->getParam('resource_copy');

        //         $copyResources->revertSiteBlockLayouts($siteCopy->id(), 'itemsblockslick');
        //     }
        // );

        // $sharedEventManager->attach(
        //     '*',
        //     'form.add_elements',
        //     [$this, 'changeBlockLayoutDataForm']
        // );

        $sharedEventManager->attach(
            '*',
            'block_layout.inline_styles',
            [$this, 'changeInlineStyles']
        );

    }

    public function changeBlockLayoutDataForm(Event $event)
    {

        print_r(get_class_methods($event));

    }

    public function changeInlineStyles(Event $event)
    {

        $block = $event->getTarget();
        $inlineStyles = $event->getParam('inline_styles');

        // Validate a CSS <hex-color>.
        $isValidHexColor = fn ($hexColor) => preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $hexColor);
        // Validate a CSS <length>
        $isValidLength = fn ($length) => preg_match(sprintf('/%s/', LengthCssDataType::PATTERN), $length);
        // Prepare a CSS <length> for use in an inline style. Note that we convert bare numbers as pixels.
        $prepareLength = fn ($length) => is_numeric($length) ? sprintf('%spx', $length) : $length;

        $width = $block->layoutDataValue('width');
        if (is_string($width) && $isValidLength($width)) {
            $inlineStyles[] = sprintf('width: %s', $prepareLength($width));
        }
       
        // $backgroundColor = $block->layoutDataValue('background_color');
        // if ($backgroundColor && $isValidHexColor($backgroundColor)) {
        //     $inlineStyles[] = sprintf('background-color: %s', $backgroundColor);
        // }

        // $backgroundImageAsset = $block->layoutDataValue('background_image_asset');
        // if ($backgroundImageAsset) {
        //     $asset = $view->api()->searchOne('assets', ['id' => $backgroundImageAsset])->getContent();
        //     if ($asset) {
        //         $inlineStyles[] = sprintf('background-image: url("%s")', $view->escapeCss($asset->assetUrl()));
        //     }
        // }
        
        // $paddingTop = $block->layoutDataValue('padding_top');
        // if (is_string($paddingTop) && $isValidLength($paddingTop)) {
        //     $inlineStyles[] = sprintf('padding-top: %s', $prepareLength($paddingTop));
        // }

        $event->setParam('inline_styles', $inlineStyles);

    }

}
