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
use BlocksAddon\TraitGeneral;

class ItemsList extends AbstractBlockLayout
{

    use TraitGeneral;


    public function getLabel()
    {
        return 'Items list'; // @translate
    }

    public function form(PhpRenderer $view, SiteRepresentation $site,
        ?SitePageRepresentation $page = null, ?SitePageBlockRepresentation $block = null
    ) {
        
        $defaults = $this->getConf('default_falues', 'itemslist');

        $data = $block ? $block->data() + $defaults : $defaults;

        $basicForm = $this->getFormElementManager()->get(\BlocksAddon\Form\ItemsListFieldset::class);
        $basicForm->setData($data);

        $queryForm = $this->getFormElementManager()->get(\BlocksAddon\Form\ItemsSearchQueryFieldset::class);
        $queryForm->setData($data);

        $html = $view->formCollection($basicForm);
        $html .= $view->blockThumbnailTypeSelect($block);
        $html .= $view->blockShowTitleSelect($block);
        $html .= '<hr style="color: #dfdfdf; width: 100%; border: 1px solid #dfdfdf;"/>';
        $html .= '<a href="#" class="expand" aria-label="expand"><h4>' . $view->translate('Search query for attachments of Items') . '</h4></a>';
        $html .= '<div class="collapsible">';
        $html .= $view->formCollection($queryForm);
        $html .= '</div>';
        return $html;

    }

    public function prepareRender(PhpRenderer $view): void
    {

        // $view->headLink()->appendStylesheet('//cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick.css');
        // $view->headLink()->appendStylesheet($view->assetUrl('slick/slick.css', 'BlocksAddon'));
        // $view->headLink()->appendStylesheet('//cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick-theme.min.css');
        // $view->headLink()->appendStylesheet($view->assetUrl('slick/slick-theme.css', 'BlocksAddon'));
        $view->headLink()->appendStylesheet($view->assetUrl('css/items-list.css', 'BlocksAddon'));
        // $view->headScript()->appendFile('//cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick.min.js');
        // $view->headScript()->appendFile($view->assetUrl('slick/slick.min.js', 'BlocksAddon'));

    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {

        $data = $block->data();

        if(!empty($data['query'])){
            parse_str($data['query'], $query);
        }

        if($data['site_attachments_only'] == 'true'){
            $query['site_id'] = $view->site->id();
        }
        if(!empty($data['entriesPerPage'])){
            $query['limit'] = $data['entriesPerPage'];
        }
        
        $response = $this->getApiManager()->search('items', $query);
        $attachments = $response->getContent();

        if (!$attachments) {
            return '';
        }

        $arrStyles = ['blockStyle', 'blockTitleStyle', 'listContentStyle', 'entrieStyle', 'textStyle', 'titleStyle', 'captionStyle', 'thumbnailStyle', 'buttonViewStyle', 'buttonViewStyleLnk'];
        foreach($arrStyles as $key){
            $styles[$key] = '';
            if(!empty($dataVal = $block->dataValue($key))){
                parse_str($dataVal, $$key);
                foreach($$key as $k => $v){
                    $styles[$key] .= "$k: $v;";
                }
            }
        }

        return $view->partial('common/block-layout/items-list', [
            'blockID' => $block->id(),
            'attachments' => $attachments,
            'data' => $data,
            'thumbnailType' => $block->dataValue('thumbnail_type', 'large'),
            'showTitleOption' => $block->dataValue('show_title_option', 'item_title'),
            'styles' => $styles,
        ]);

    }

}
