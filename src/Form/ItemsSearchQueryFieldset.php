<?php declare(strict_types=1);

namespace BlocksAddon\Form;

use Common\Form\Element as CommonElement;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Omeka\Form\Element as OmekaElement;

class ItemsSearchQueryFieldset extends Fieldset
{

    protected $needFields = [
        'entriesPerPage', 'site_attachments_only', 'query'
    ];

    public function init(): void
    {
                
        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][entriesPerPage]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Entries per page', // @translate
                'info' => 'The number of entries shown in page', // @translate
            ],
            'attributes' => [
                'min' => 0,
                'max' => 1000,
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

        $this->add([
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

        $this->add([
            'name' => 'o:block[__blockIndex__][o:data][query]',
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