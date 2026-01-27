<?php declare(strict_types=1);

namespace BlocksAddon\Form;

use Common\Form\Element as CommonElement;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Omeka\Form\Element as OmekaElement;

class ItemsListFieldset extends Fieldset
{

    protected $needFields = [
        'blockTitle', 'blockStyle', 'blockTitleStyle', 'listContentStyle', 'entrieStyle', 'textStyle', 'titleStyle', 'titleStyleLink', 'captionStyle', 'thumbnailStyle', 'captionShow', 'thumbnailShow', 'navByPage', 'itemTitleAsLink', 'buttonViewShow', 'buttonView', 'buttonViewStyle', 'buttonViewStyleLnk'
    ];

    public function init(): void
    {

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][blockTitle]',
            'type' => 'text',
            'options' => [
                'label' => 'Title block ', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][blockStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][blockTitleStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block title style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][listContentStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'List content style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][entrieStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Entrie wrapper style', // @translate
            ],
        ]);
        

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][textStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block text style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][titleStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Title style', // @translate
            ],
        ]);
        
        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][itemTitleAsLink]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Item title as link', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][titleStyleLink]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Title link style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][thumbnailShow]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Show attachment thumbnail', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][thumbnailStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Thumbnail style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][captionShow]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Show attachment caption', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][captionStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Caption style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][buttonViewShow]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Show button view', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][buttonView]',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Text button view', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][buttonViewStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Style button view', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][buttonViewStyleLnk]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Style link in button view', // @translate
            ],
        ]);

    }

    public function setNeedFields($needFields)
    {
        $this->needFields = $needFields;
    }

    public function getNeedFields()
    {
        return $this->needFields;
    }

    public function setData($data)
    {
        
        if($this->needFields){
            $vals = [];
            foreach($this->needFields as $key){
                if(isset($data[$key])){
                    $vals['o:block[__blockIndex__][o:data]['.$key.']'] = $data[$key];
                }else{
                    $vals['o:block[__blockIndex__][o:data]['.$key.']'] = '';
                }
            }
            $this->populateValues($vals);
        }else{
            return False;
        }
        
    }

}
