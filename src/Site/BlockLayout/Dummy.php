<?php
namespace BlocksAddon\Site\BlockLayout;

use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
// use Omeka\Form\Element\ItemSetSelect;
use Omeka\Form\Element as OmekaElement;

class Dummy extends AbstractBlockLayout
{

    public function getLabel()
    {
        return 'Block disabled';
    }

    public function form(PhpRenderer $view, SiteRepresentation $site,
        ?SitePageRepresentation $page = null, ?SitePageBlockRepresentation $block = null
    ) {

        return '<div class="dummy_block">Block disabled</div>';

    }

    public function prepareRender(PhpRenderer $view): void
    {

    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {

        return '<div class="dummy_block">Block disabled</div>';

    }

}
