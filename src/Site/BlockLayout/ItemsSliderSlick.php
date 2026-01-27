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
use BlocksAddon\Common;

class ItemsSliderSlick extends AbstractBlockLayout
{

    use Common;

    public function getLabel()
    {
        return 'Items Slider (Slick)'; // @translate
    }

    public function form(PhpRenderer $view, SiteRepresentation $site,
        SitePageRepresentation $page = null, SitePageBlockRepresentation $block = null
    ) {
        $defaults = [
            'blockTitle' => '',
            'blockStyle' => '',
            'blockTitleStyle' => '',
            'sliderContentStyle' => '',
            'slideStyle' => '',
            'arrowRStyle' => '',
            'arrowLStyle' => '',
            'dotStyle' => '',
            'pplayStyle' => '',
            'slideText' => '',
            'slideTitle' => '',
            'slideTitleLink' => '',
            'slideCaption' => '',
            'slideThumbnail' => '',
            'perPage' => 1,
            'slidesToScroll' => 1,
            'verticalSlider' => 'false',
            'swipeToSlide' => 'true',
            'sliderRows' => 1,
            'slidesPerRow' => 1,
            'speedSlider' => 300,
            'zIndex' => 1000,
            'showCaption' => 'false',
            'showThumbnail' => 'false',            
            'floatCaption' => 'false',
            'slideCSSTextAlign' => 'center',
            'slideCSSStretch' => 'none',
            'breakPoint' => 820,
            'autoSlideDuration' => 0,
            'loop' => 'true',
            'draggable' => 'true',
            'fade' => 'false',
            'pauseOnHover' => 'true',
            'pauseOnFocus' => 'false',
            'centerMode' => 'false',
            'centerPadding' => 10,
            'displayArrows' => 'true',
            'displayDots' => 'true',
            'limitItems' => 10,
            'site_attachments_only' => 'true',
            'items_query' => '',
            'itemTitleAsLink' => 'true',
            'displayButtonView' => 'false',
            'textButtonView' => 'View', // @translate
            'styleButtonView' => '',
            'styleInButtonView' => '',
            'textLength' => 500,
            'titleLength' => 200,
            'captionLength' => 300,
        ];

        $data = $block ? $block->data() + $defaults : $defaults;

        $basicForm = new Form();

        $basicForm->add([
            'name' => 'o:block[__blockIndex__][o:data][blockTitle]',
            'type' => 'text',
            'options' => [
                'label' => 'Block title', // @translate
            ],
        ]);

        $basicForm->setData([
            'o:block[__blockIndex__][o:data][blockTitle]' => $data['blockTitle'],
        ]);

        $basicForm->prepare();

        $generalForm = new Form();

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][blockStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block style', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][blockTitleStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block title style', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][sliderContentStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Slider content style', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][verticalSlider]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Vertical slider', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][swipeToSlide]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Swipe to slide', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][perPage]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Items per slide', // @translate
                'info' => 'The number of items shown per carousel slide', // @translate
            ],
            'attributes' => [
                'min' => 1,
                'max' => 10,
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slidesToScroll]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Slides to scroll', // @translate
                'info' => 'The number of slides to scroll', // @translate
            ],
            'attributes' => [
                'min' => 1,
                'max' => 10,
            ],
        ]);


        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][sliderRows]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Rows slider', // @translate
            ],
            'attributes' => [
                'min' => 1,
                'max' => 10,
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slidesPerRow]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Slides to scroll per row', // @translate
            ],
            'attributes' => [
                'min' => 1,
                'max' => 10,
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][zIndex]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'zIndex', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][speedSlider]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Speed slider', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][breakPoint]',
            'type' => 'text',
            'options' => [
                'label' => 'Breakpoint', // @translate
                'info' => 'Display one item per slide when carousel width drops below given pixel width. Adjust for mobile display, carousels with many items per page, etc.', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][autoSlideDuration]',
            'type' => 'text',
            'options' => [
                'label' => 'Auto slide duration', // @translate
                'info' => 'Time in milliseconds to pause before auto advance (set to 0 to turn off)', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][loop]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Infinite loop', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        // disable fade if more than one item per page since it doesn't display correctly
        if ($data['perPage'] > 1) {
            $disabledFade = true;
            $fade = 'false';
        } else {
            $disabledFade = false;
            $fade = $data['fade'];
        }

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][draggable]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Draggable', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][pauseOnHover]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Pause on hover', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][pauseOnFocus]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Pause on focus', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][pplayStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Play-Pause button style', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][centerMode]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Center mode', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][speedSlider]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Speed slider', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][displayArrows]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Display arrows', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][arrowRStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Right Arrow style', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][arrowLStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Left Arrow style', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][displayDots]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Display dots', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);
        
        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][dotStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Dot style', // @translate
            ],
        ]);

        $generalForm->add([
            'name' => 'o:block[__blockIndex__][o:data][fade]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Fade between slides', // @translate
                'info' => 'Note: only works with 1 item per slide', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
            'attributes' => [
                'disabled' => $disabledFade,
            ],
        ]);

        $generalForm->setData([
            'o:block[__blockIndex__][o:data][blockStyle]' => $data['blockStyle'],
            'o:block[__blockIndex__][o:data][blockTitleStyle]' => $data['blockTitleStyle'],
            'o:block[__blockIndex__][o:data][sliderContentStyle]' => $data['sliderContentStyle'],
            'o:block[__blockIndex__][o:data][arrowRStyle]' => $data['arrowRStyle'],
            'o:block[__blockIndex__][o:data][arrowLStyle]' => $data['arrowLStyle'],
            'o:block[__blockIndex__][o:data][dotStyle]' => $data['dotStyle'],
            'o:block[__blockIndex__][o:data][pplayStyle]' => $data['pplayStyle'],
            'o:block[__blockIndex__][o:data][perPage]' => $data['perPage'],
            'o:block[__blockIndex__][o:data][verticalSlider]' => $data['verticalSlider'],
            'o:block[__blockIndex__][o:data][swipeToSlide]' => $data['swipeToSlide'],
            'o:block[__blockIndex__][o:data][sliderRows]' => $data['sliderRows'],
            'o:block[__blockIndex__][o:data][slidesPerRow]' => $data['slidesPerRow'],
            'o:block[__blockIndex__][o:data][speedSlider]' => $data['speedSlider'],
            'o:block[__blockIndex__][o:data][zIndex]' => $data['zIndex'],
            'o:block[__blockIndex__][o:data][slidesToScroll]' => $data['slidesToScroll'],
            'o:block[__blockIndex__][o:data][breakPoint]' => $data['breakPoint'],
            'o:block[__blockIndex__][o:data][autoSlideDuration]' => $data['autoSlideDuration'],
            'o:block[__blockIndex__][o:data][loop]' => $data['loop'],
            'o:block[__blockIndex__][o:data][fade]' => $fade,
            'o:block[__blockIndex__][o:data][draggable]' => $data['draggable'],
            'o:block[__blockIndex__][o:data][pauseOnHover]' => $data['pauseOnHover'],
            'o:block[__blockIndex__][o:data][pauseOnFocus]' => $data['pauseOnFocus'],
            'o:block[__blockIndex__][o:data][centerMode]' => $data['centerMode'],
            'o:block[__blockIndex__][o:data][centerPadding]' => $data['centerPadding'],
            'o:block[__blockIndex__][o:data][displayArrows]' => $data['displayArrows'],
            'o:block[__blockIndex__][o:data][displayDots]' => $data['displayDots'],
        ]);

        $generalForm->prepare();

        $slideForm = new Form();
        
        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Slide style', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideText]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block text style', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][textLength]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'General text length limit', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideTitle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Title style', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideTitleLink]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Title link style', // @translate
            ],
        ]);
      
        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][titleLength]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Title length limit', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][showThumbnail]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Show attachment thumbnail', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideThumbnail]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Thumbnail style', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][showCaption]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Show attachment caption', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideCaption]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Caption style', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][captionLength]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Caption length limit', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][floatCaption]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Overlay title/caption', // @translate
                'info' => 'Place title/caption over image (may require adjusting theme CSS text settings)', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideCSSTextAlign]',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Text align', // @translate
                'value_options' => [
                    'left' => 'Left', // @translate
                    'center' => 'Center', // @translate
                    'right' => 'Right', // @translate
                ],
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][slideCSSStretch]',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Stretch image', // @translate
                'value_options' => [
                    'none' => 'None', // @translate
                    'width' => 'Fill width', // @translate
                    'height' => 'Fill height', // @translate
                    'entire' => 'Fill entire slide', // @translate
                ],
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][itemTitleAsLink]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Item title as link', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][displayButtonView]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Display button view', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][textButtonView]',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Text button view', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][styleButtonView]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Style button view', // @translate
            ],
        ]);

        $slideForm->add([
            'name' => 'o:block[__blockIndex__][o:data][styleInButtonView]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Style in button view', // @translate
            ],
        ]);

        $slideForm->setData([
            'o:block[__blockIndex__][o:data][slideStyle]' => $data['slideStyle'],
            'o:block[__blockIndex__][o:data][showCaption]' => $data['showCaption'],
            'o:block[__blockIndex__][o:data][showThumbnail]' => $data['showThumbnail'],
            'o:block[__blockIndex__][o:data][floatCaption]' => $data['floatCaption'],
            'o:block[__blockIndex__][o:data][slideCSSTextAlign]' => $data['slideCSSTextAlign'],
            'o:block[__blockIndex__][o:data][slideCSSStretch]' => $data['slideCSSStretch'],
            'o:block[__blockIndex__][o:data][itemTitleAsLink]' => $data['itemTitleAsLink'],
            'o:block[__blockIndex__][o:data][displayButtonView]' => $data['displayButtonView'],
            'o:block[__blockIndex__][o:data][textButtonView]' => $data['textButtonView'],
            'o:block[__blockIndex__][o:data][styleButtonView]' => $data['styleButtonView'],
            'o:block[__blockIndex__][o:data][styleInButtonView]' => $data['styleInButtonView'],
            'o:block[__blockIndex__][o:data][slideText]' => $data['slideText'],
            'o:block[__blockIndex__][o:data][slideTitle]' => $data['slideTitle'],
            'o:block[__blockIndex__][o:data][slideTitleLink]' => $data['slideTitleLink'],
            'o:block[__blockIndex__][o:data][slideCaption]' => $data['slideCaption'],
            'o:block[__blockIndex__][o:data][slideThumbnail]' => $data['slideThumbnail'],
            'o:block[__blockIndex__][o:data][textLength]' => $data['textLength'],
            'o:block[__blockIndex__][o:data][titleLength]' => $data['titleLength'],
            'o:block[__blockIndex__][o:data][captionLength]' => $data['captionLength'],
        ]);

        $slideForm->prepare();

        $queryForm = new Form();
        
        $queryForm->add([
            'name' => 'o:block[__blockIndex__][o:data][limitItems]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Limit Items', // @translate
                'info' => 'The number of items shown in carousel', // @translate
            ],
            'attributes' => [
                'min' => 1,
                'max' => 50,
            ],
        ]);

        // $apiManager = $this->serviceLocator->get('Omeka\ApiManager');
        // $itemsets = $apiManager->search('item_sets', ['sort_by' => 'name'])->getContent();
        // $valueOptions[] = 'None';
        // foreach ($itemsets as $itemset) {
        //     $valueOptions[$itemset->id()] = $itemset->displayTitle();
        // }

        // $queryForm->add([
        //     'name' => 'o:block[__blockIndex__][o:data][item_set_id]',
        //     'type' => Element\Select::class,
        //     'attributes' => [
        //         'class' => 'chosen-select',
        //         'data-placeholder' => 'Select a Item Set', // @translate
        //         'multiple' => true,
        //     ],
        //     'options' => [
        //         'label' => 'Item Set', // @translate
        //         'info' => 'Select Item Set', // @translate
        //         'value_options' => $valueOptions,
        //     ],
        // ]);

        $queryForm->add([
            'name' => 'o:block[__blockIndex__][o:data][site_attachments_only]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Items site attachments only', // @translate
                'info' => 'Items site attachments only', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        // $queryForm->add([
        //     'name' => 'o:block[__blockIndex__][o:data][sort_by]',
        //     'type' => Element\Select::class,
        //     'options' => [
        //         'label' => 'Sort by', // @translate
        //         'value_options' => [
        //             '' => 'None', // @translate
        //             'created' => 'Created', // @translate
        //         ],
        //     ],
        // ]);

        // $queryForm->add([
        //     'name' => 'o:block[__blockIndex__][o:data][sort_order]',
        //     'type' => Element\Select::class,
        //     'options' => [
        //         'label' => 'Sort order', // @translate
        //         'value_options' => [
        //             'asc' => 'Ascending', // @translate
        //             'desc' => 'Descending', // @translate
        //         ],
        //     ],
        // ]);

        $queryForm->add([
            'name' => 'o:block[__blockIndex__][o:data][items_query]',
            'type' => OmekaElement\Query::class,
            'options' => [
                'label' => 'Search query', // @translate
                'info' => 'The search to be performed when the topic is clicked', // @translate
                'query_resource_type' => 'items',
                'query_partial_excludelist' => ['common/advanced-search/site'],
            ],
            'attributes' => [
                'data-sidebar-id' => 'items-data-query'
            ]
        ]);

        $queryForm->setData([
            'o:block[__blockIndex__][o:data][limitItems]' => $data['limitItems'],
            'o:block[__blockIndex__][o:data][site_attachments_only]' => $data['site_attachments_only'],
            // 'o:block[__blockIndex__][o:data][item_set_id]' => $data['item_set_id'],
            // 'o:block[__blockIndex__][o:data][sort_by]' => $data['sort_by'],
            // 'o:block[__blockIndex__][o:data][sort_order]' => $data['sort_order'],
            'o:block[__blockIndex__][o:data][items_query]' => $data['items_query'],
        ]);

        $queryForm->prepare();

        $html = '';
        $html .= $view->formCollection($basicForm);
        $html .= '<hr style="color: #dfdfdf; width: 100%; border: 1px solid #dfdfdf;"/><a href="#" class="expand" aria-label="expand"><h4>' . $view->translate('General options') . '</h4></a>';
        $html .= '<div class="collapsible">';
        $html .= $view->formCollection($generalForm);
        $html .= '</div><hr style="color: #dfdfdf; width: 100%; border: 1px solid #dfdfdf;"/>';
        $html .= '<a href="#" class="expand" aria-label="expand"><h4>' . $view->translate('Slide options') . '</h4></a>';
        $html .= '<div class="collapsible">';
        $html .= $view->blockThumbnailTypeSelect($block);
        $html .= $view->blockShowTitleSelect($block);
        $html .= $view->formCollection($slideForm);
        $html .= '</div><hr style="color: #dfdfdf; width: 100%; border: 1px solid #dfdfdf;"/>';
        $html .= '<a href="#" class="expand" aria-label="expand"><h4>' . $view->translate('Search query for attachments of Items') . '</h4></a>';
        $html .= '<div class="collapsible">';
        $html .= $view->formCollection($queryForm);
        $html .= '</div>';
        // $html .= $view->blockAttachmentsForm($block);
        return $html;
    }

    public function prepareRender(PhpRenderer $view): void
    {

        // $view->headLink()->appendStylesheet('//cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick.css');
        $view->headLink()->appendStylesheet($view->assetUrl('slick/slick.css', 'BlocksAddon'));
        // $view->headLink()->appendStylesheet('//cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick-theme.min.css');
        $view->headLink()->appendStylesheet($view->assetUrl('slick/slick-theme.css', 'BlocksAddon'));
        $view->headLink()->appendStylesheet($view->assetUrl('css/items-block-slick.css', 'BlocksAddon'));
        // $view->headScript()->appendFile('//cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick.min.js');
        $view->headScript()->appendFile($view->assetUrl('slick/slick.min.js', 'BlocksAddon'));

    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {

        if(!empty($items_query = $block->dataValue('items_query'))){
            parse_str($items_query, $query);
        }

        if($block->dataValue('site_attachments_only') == 'true'){
            $query['site_id'] = $view->site->id();
            // $query['site_attachments_only'] = true;
        }
        $query['limit'] = $block->dataValue('limitItems');        
        
        $response = $this->getApiManager()->search('items', $query);
        $attachments = $response->getContent();

        if (!$attachments) {
            return '';
        }

        $arrStyles = ['blockStyle', 'blockTitleStyle', 'sliderContentStyle', 'slideStyle', 'arrowRStyle', 'arrowLStyle', 'dotStyle', 'pplayStyle', 'slideText', 'slideTitle', 'slideTitleLink', 'slideCaption', 'slideThumbnail', 'styleButtonView', 'styleInButtonView'];
        foreach($arrStyles as $key){
            $styles[$key] = '';
            if(!empty($dataVal = $block->dataValue($key))){
                if(stripos($dataVal, '=') !== False){
                    parse_str($dataVal, $$key);
                    foreach($$key as $k => $v){
                        $styles[$key] .= "$k: $v;";
                    }
                }else{
                    $styles[$key] = $dataVal;
                }
            }
        }

        $thumbnailType = $block->dataValue('thumbnail_type', 'large');
        $showTitleOption = $block->dataValue('show_title_option', 'item_title');

        return $view->partial('common/block-layout/items-slider-slick', [
            'blockID' => $block->id(),
            'attachments' => $attachments,
            'blockTitle' => $block->dataValue('blockTitle'),
            'perPage' => $block->dataValue('perPage'),
            'slidesToScroll' => $block->dataValue('slidesToScroll'),
            'thumbnailType' => $thumbnailType,
            'showTitleOption' => $showTitleOption,
            'speedSlider' => $block->dataValue('speedSlider'),
            'verticalSlider' => $block->dataValue('verticalSlider'),
            'swipeToSlide' => $block->dataValue('swipeToSlide'),
            'sliderRows' => $block->dataValue('sliderRows'),
            'slidesPerRow' => $block->dataValue('slidesPerRow'),
            'zIndex' => $block->dataValue('zIndex'),
            'showThumbnail' => $block->dataValue('showThumbnail'),
            'showCaption' => $block->dataValue('showCaption'),
            'floatCaption' => $block->dataValue('floatCaption'),
            'slideCSSTextAlign' => $block->dataValue('slideCSSTextAlign'),
            'slideCSSStretch' => $block->dataValue('slideCSSStretch'),
            'breakPoint' => $block->dataValue('breakPoint'),
            'autoSlideDuration' => $block->dataValue('autoSlideDuration'),
            'loop' => $block->dataValue('loop'),
            'fade' => $block->dataValue('fade'),
            'draggable' => $block->dataValue('draggable'),
            'pauseOnHover' => $block->dataValue('pauseOnHover'),
            'pauseOnFocus' => $block->dataValue('pauseOnFocus'),
            'centerMode' => $block->dataValue('centerMode'),
            'displayArrows' => $block->dataValue('displayArrows'),
            'displayDots' => $block->dataValue('displayDots'),
            'itemTitleAsLink' => $block->dataValue('itemTitleAsLink'),
            'displayButtonView' => $block->dataValue('displayButtonView'),
            'textButtonView' => $block->dataValue('textButtonView'),
            'textLengthLimit' => $block->dataValue('textLength'),
            'titleLengthLimit' => $block->dataValue('titleLength'),
            'captionLengthLimit' => $block->dataValue('captionLength'),
            'styleButtonView' => $styles['styleButtonView'],
            'styleInButtonView' => $styles['styleInButtonView'],
            'blockStyle' => $styles['blockStyle'],
            'blockTitleStyle' => $styles['blockTitleStyle'],
            'sliderContentStyle' => $styles['sliderContentStyle'],
            'slideStyle' => $styles['slideStyle'],
            'arrowRStyle' => $styles['arrowRStyle'],
            'arrowLStyle' => $styles['arrowLStyle'],
            'dotStyle' => $styles['dotStyle'],
            'pplayStyle' => $styles['pplayStyle'],
            'slideText' => $styles['slideText'],
            'slideTitle' => $styles['slideTitle'],
            'slideTitleLink' => $styles['slideTitleLink'],
            'slideCaption' => $styles['slideCaption'],
            'slideThumbnail' => $styles['slideThumbnail'],
            'styles' => $styles,
        ]);

    }

}
