<?php declare(strict_types=1);

namespace BlocksAddon\Form;

use Common\Form\Element as CommonElement;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Omeka\Form\Element as OmekaElement;

class PropertieListValuesFieldset extends Fieldset
{

    public function init(): void
    {

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][blockTitle]',
            'type' => 'text',
            'options' => [
                'label' => 'Block title', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][blockTitleUrl]',
            'type' => 'text',
            'options' => [
                'label' => 'Title Url', // @translate
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
            'name' => 'o:block[__blockIndex__][o:data][blockStyle]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block style', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][blockStyleList]',
            'type' => 'textarea',
            'options' => [
                'label' => 'Block list style ', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][showTotalValues]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Show total values', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][navPageForValues]',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Nav by page for values', // @translate
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
        ]);

        $this->add([
                'name' => 'o:block[__blockIndex__][o:data][propertyId]',
                'type' => OmekaElement\PropertySelect::class,
                'options' => [
                    'label' => 'Properties', // @translate
                    'empty_option' => '[None]', // @translate
                    'term_as_value' => false,
                ],
                'attributes' => [
                    // 'id' => 'reference-args-properties',
                    'required' => true,
                    'class' => 'chosen-select',
                    // 'multiple' => 'multiple',
                    // 'data-placeholder' => 'Select properties…', // @translate
                    // 'data-fieldset' => 'args',
                ],
            ]);


        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][sort_by]',
            'type' => 'select',
            'options' => [
                'label' => 'sort_by', // @translate
                'value_options' => [
                    'alphabetic' => 'Alphabetic', // @translate
                    'total' => 'Total', // @translate
                    // '' //
                ],
            ],
            'attributes' => [
                'class' => 'chosen-select',
            ],
        ]);

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][sort_order]',
            'type' => 'select',
            'options' => [
                'label' => 'sort_order', // @translate
                'value_options' => [
                    'asc' => 'Asc', // @translate
                    'desc' => 'Desc', // @translate
                    // '' //
                ],
            ],
            'attributes' => [
                'class' => 'chosen-select',
            ],
        ]);

    }

}
