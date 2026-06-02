<?php
namespace BlocksAddon;

require_once __DIR__ . '/src/TraitGeneral.php';
require_once __DIR__ . '/src/TraitModule.php';

use Zend\EventManager\SharedEventManagerInterface;
use Laminas\EventManager\Event;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\ModuleManager\ModuleEvent;
use Omeka\Module\AbstractModule;
use Omeka\Permissions\Acl;
use Omeka\Form\Element\LengthCssDataType;
use BlocksAddon\TraitGeneral;
use BlocksAddon\TraitModule;

class Module extends AbstractModule
{

    use TraitGeneral;
    use TraitModule;

    public function onEventMergeConfig(ModuleEvent $event): void
    {

        if(file_exists(OMEKA_PATH . '/config/custom.config.php')){
            $custom_config = include OMEKA_PATH . '/config/custom.config.php';
            if(!empty($custom_config) && !empty($custom_config['BlocksAddon']['block_layouts_disabled'])){
                /** @var \Laminas\ModuleManager\Listener\ConfigListener $configListener */
                $configListener = $event->getParam('configListener');
                // At this point, the config is read only, so it is copied and replaced.
                $config = $configListener->getMergedConfig(false);
                if(!empty($config['block_layouts']['invokables'])){
                    foreach($config['block_layouts']['invokables'] as $key => $obj){
                        if(in_array($key, $custom_config['BlocksAddon']['block_layouts_disabled'])){
                            $config['block_layouts']['invokables'][$key] = Site\BlockLayout\Dummy::class;
                        }
                    }
                }
                if(!empty($config['block_layouts']['factories'])){
                    foreach($config['block_layouts']['factories'] as $key => $obj){
                        if(in_array($key, $custom_config['BlocksAddon']['block_layouts_disabled'])){
                            $config['block_layouts']['factories'][$key] = Service\BlockLayout\DummyFactory::class;
                        }
                    }
                }
                $configListener->setMergedConfig($config);
            }
        }

    }

    protected function addDefAclRules()
    {

        $this->getAcl()->deny(
            null,
            [
                Controller\Admin\SettingsController::class
            ]
        );

        $this->getAcl()->deny(
            Acl::ROLE_SITE_ADMIN,
            [
                Controller\Admin\SettingsController::class
            ]
        );

    }

    public function getConfigForm(PhpRenderer $renderer)
    {

        return $this->redirecToURL($renderer->url('admin/blocks-addon-settings', ['action' => 'edit']));
        
    }

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


        $sharedEventManager->attach(
            '*',
            'view.layout',
            [$this, 'handleViewLayout'],
            -1001
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

    public function handleViewLayout(Event $event): void
    {

        $view = $event->getTarget();
        $view->headLink()->appendStylesheet($view->assetUrl('css/blocksaddon.css', 'BlocksAddon'));
        $view->headScript()->appendFile($view->assetUrl('js/blocksaddon.js', 'BlocksAddon'));

    }
}
