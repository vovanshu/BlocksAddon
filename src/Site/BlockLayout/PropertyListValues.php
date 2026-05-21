<?php
namespace BlocksAddon\Site\BlockLayout;

use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\View\Renderer\PhpRenderer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
// use Omeka\Form\Element\ItemSetSelect;
use Omeka\Form\Element as OmekaElement;
// use Omeka\Form\Element\PropertySelect;
use BlocksAddon\TraitGeneral;

class PropertyListValues extends AbstractBlockLayout
{

    use TraitGeneral;

    public function getLabel()
    {
        return 'List of Property Values'; // @translate
    }

    public function form(PhpRenderer $view, SiteRepresentation $site,
        ?SitePageRepresentation $page = null, ?SitePageBlockRepresentation $block = null
    ) {
        $defaults = [
            'blockTitle' => '',
            'blockTitleStyle' => '',
            'blockTitleUrl' => '',
            'blockStyle' => '',
            'blockStyleList' => '',
            'showTotalValues' => 'false',
            'navPageForValues' => 'false',
            'propertyId' => '',
            'sort_by' => 'alphabetic',
            'sort_order' => 'asc',
            'limit' => 10,
            'site_attachments_only' => 'false',
            'query' => '',
        ];

        $data = $block ? $block->data() + $defaults : $defaults;

        $formElementManager = $this->getServiceLocator()->get('FormElementManager');
        $blockFieldset = \BlocksAddon\Form\PropertieListValuesFieldset::class;
        $basicForm = $formElementManager->get($blockFieldset);

        $basicForm->populateValues([
            'o:block[__blockIndex__][o:data][blockTitle]' => $data['blockTitle'],
            'o:block[__blockIndex__][o:data][blockTitleStyle]' => $data['blockTitleStyle'],
            'o:block[__blockIndex__][o:data][blockTitleUrl]' => $data['blockTitleUrl'],
            'o:block[__blockIndex__][o:data][blockStyle]' => $data['blockStyle'],
            'o:block[__blockIndex__][o:data][blockStyleList]' => $data['blockStyleList'],
            'o:block[__blockIndex__][o:data][showTotalValues]' => $data['showTotalValues'],
            'o:block[__blockIndex__][o:data][navPageForValues]' => $data['navPageForValues'],
            'o:block[__blockIndex__][o:data][propertyId]' => $data['propertyId'],
            'o:block[__blockIndex__][o:data][sort_by]' => $data['sort_by'],
            'o:block[__blockIndex__][o:data][sort_order]' => $data['sort_order'],
        ]);

        // $basicForm->prepare();

        $queryForm = new Form();
        
        $queryForm->add([
            'name' => 'o:block[__blockIndex__][o:data][limit]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Limit Entries', // @translate
                'info' => 'The number of values shown in list per page', // @translate
            ],
            'attributes' => [
                'min' => 0,
                'max' => 100,
            ],
        ]);

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

        $queryForm->add([
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

        $queryForm->setData([
            'o:block[__blockIndex__][o:data][limit]' => $data['limit'],
            'o:block[__blockIndex__][o:data][site_attachments_only]' => $data['site_attachments_only'],
            'o:block[__blockIndex__][o:data][query]' => $data['query'],
        ]);

        $queryForm->prepare();

        $html = '';
        $html .= $view->formCollection($basicForm);
        $html .= '<a href="#" class="expand" aria-label="expand"><h4>' . $view->translate('Search query for attachments of Items') . '</h4></a>';
        $html .= '<div class="collapsible">';
        $html .= $view->formCollection($queryForm);
        $html .= '</div>';
        return $html;

    }

    public function prepareRender(PhpRenderer $view): void
    {

        $view->headLink()->appendStylesheet($view->assetUrl('css/property-list-values.css', 'BlocksAddon'));

    }
    
    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {


        $args = [];
        $data = $block->data();

        // $EasyMeta = $this->getServiceLocator()->get('Common\EasyMeta');

        // $propertyId = $EasyMeta->propertyId($data['propertyId']);

        $properties = $this->getApiManager()->read('properties', ['id' => $data['propertyId']])->getContent();

        
        // $properties->itemCount()
        if(!empty($data['query'])){
            parse_str($data['query'], $query);
        }
        
        // if(!empty($data['limit'])){
        //     $data['is_public'] = $data['limit'];
        // }
        if(isset($query['is_public'])){
            $data['is_public'] = $query['is_public'];
        }
        $list = $this->listDataForPropertiy($data['propertyId'], $data);

        $routeMatch = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();

        $siteSlug = $routeMatch->getParam('site-slug')
            // ?? (($site = $plugins->get('Laminas\View\Helper\ViewModel')->getRoot()->getVariable('site')) ? $site->slug() : null)
            // TODO Store the default site slug, not only the default site id.
            // If no default site, get the first public one.
            ?? (($site = $this->getApiManager()->searchOne('sites', ['id' => $this->getSettings()->get('default_site'), 'sort_by' => 'is_public', 'sort_order' => 'desc'])->getContent()) ? $site->slug() : null);
            

        // echo '<pre>';
        // print_r($data);
        // print_r($query);
        // print_r($list);
        // print_r($routeMatch->getParams());
        // print_r(get_class_methods($EasyMeta));

        // print_r(get_class_methods($properties));
        // echo '</pre>';
        
        // $apiManager = $this->getServiceLocator()->get('Omeka\ApiManager');

        // if($block->dataValue('site_attachments_only') == 'true'){
        //     $query['site_id'] = $view->site->id();
        //     // $query['site_attachments_only'] = true;
        // }
        // $query['limit'] = $block->dataValue('limit');        
        
        // $response = $apiManager->search('items', $query);
        // $attachments = $response->getContent();

        // if (!$attachments) {
        //     return '';
        // }

        $arrStyles = ['blockStyle', 'blockTitleStyle', 'blockStyleList'];
        foreach($arrStyles as $key){
            $styles[$key] = '';
            if(!empty($data[$key])){
                parse_str($data[$key], $$key);
                foreach($$key as $k => $v){
                    $styles[$key] .= "$k: $v;";
                }
            }
        }

        // $thumbnailType = $block->dataValue('thumbnail_type', 'large');
        // $showTitleOption = $block->dataValue('show_title_option', 'item_title');

        return $view->partial('common/block-layout/property-list-values', [
            'siteSlug' => $siteSlug,
            'term' => $properties->term(),
            'list' => $list,
            'blockID' => $block->id(),
            'blockTitle' => $data['blockTitle'],
            'blockTitleUrl' => $data['blockTitleUrl'],
            'blockStyle' => $styles['blockStyle'],
            'blockTitleStyle' => $styles['blockTitleStyle'],
            'blockStyleList' => $styles['blockStyleList'],
            'showTotalValues' => $data['showTotalValues'],
        ]);

    }


    
    /**
     * Get the list of used values for a list of properties, the total for each
     * one and the first item.
     *
     * @param int $propertyId
     * @return array Associative list of references, with the total, the first
     * record, and the first character, according to the parameters.
     */
    protected function listDataForPropertiy($propertyId, $data)
    {
        if (empty($propertyId)) {
            return [];
        }

        $qb = $this->getConnection()->createQueryBuilder();
        $expr = $qb->expr();

        // TODO This is no more the case.
        // TODO Check if ANY_VALUE can be replaced by MIN in order to remove it.
        // Note: Doctrine ORM requires simple label, without quote or double quote:
        // "o:label" is not possible, neither "count". Use of Doctrine DBAL now.

        // Dbal expr() and orm expr() don't have the same methods, for example
        // count(), substring(), upper(), etc. It has only the common comparison
        // operators.

        $qb
            // "Distinct" avoids to count duplicate values in properties in a
            // resource: we count resources, not properties.
            ->distinct()
            ->from('value', 'value')
            ->where($expr->eq('value.property_id', $propertyId))
            ->groupBy('val');

        // if(!empty($args['limit'])){
        //     $qb->setMaxResults($args['limit']);
        // }

        // The values should be distinct for each type.
        // $table = 'item';
        // if (empty($table)) {
        //     $qb
        //         ->innerJoin('value', 'resource', 'resource', $expr->eq('resource.id', 'value.resource_id'))
        //         ->leftJoin('value', 'resource', 'value_resource', $expr->eq('value_resource.id', 'value.value_resource_id'));
        // } else {
            $entityClass = $this->getApiAdapterManager('items')->getEntityClass();
            $qb
                ->innerJoin('value', 'resource', 'resource', $expr->eq('resource.id', 'value.resource_id'))
                ->innerJoin('value', 'item', 'vrs', $expr->eq('vrs.id', 'value.resource_id'))
                // It is not possible to use two left joins here.
                ->leftJoin('value', 'resource', 'value_resource', $expr->and(
                    $expr->eq('value_resource.id', 'value.value_resource_id'),
                    $expr->eq('value_resource.resource_type', ':entity_class')
                ))
                ->setParameter('entity_class', $entityClass, ParameterType::STRING);
        // }

        // This filter is used by properties only and normally already included
        // in the select, but it allows to simplify it.
        $mainTypes = [
            'value' => 'value.value',
            // Output the linked resource title, not the linked resource id.
            'resource' => 'value_resource.title',
            // 'resource' => 'value.value_resource_id',
            'uri' => 'value.uri',
        ];
        // if ($this->optionsCurrent['filters']['main_types']) {
        //     $mainTypes = array_intersect_key($mainTypes, $this->optionsCurrent['filters']['main_types']);
        // }
        $mainTypesString = count($mainTypes) === 1
            ? reset($mainTypes)
            : 'COALESCE(' . implode(', ', $mainTypes) . ')';

        // if ($this->process === 'initials') {
        //     if ($this->optionsCurrent['locale']) {
        //         $qb
        //             ->select(
        //                 // TODO Doctrine doesn't manage left() and convert(), but we may not need to convert. Anyway convert should be only for diacritics.
        //                 // 'CONVERT(UPPER(LEFT(refmeta.text, 1)) USING latin1) AS val',
        //                 $val = "UPPER(LEFT(refmeta.text, {$this->optionsCurrent['_initials']})) AS val"
        //             )
        //             ->innerJoin('value', 'reference_metadata', 'refmeta', $expr->eq('refmeta.value_id', 'value.id'))
        //             ->andWhere($expr->in('refmeta.lang', ':locales'))
        //             ->setParameter('locales', $this->optionsCurrent['locale'], Connection::PARAM_STR_ARRAY)
        //         ;
        //     } else {
        //         // TODO Doctrine doesn't manage left() and convert(), but we may not need to convert.
        //         $qb
        //             ->select(
        //                 // 'CONVERT(UPPER(LEFT($mainTypesString, $this->optionsCurrent['_initials'])) USING latin1) AS val',
        //                 $val = $this->supportAnyValue
        //                     ? "ANY_VALUE(UPPER(LEFT($mainTypesString, {$this->optionsCurrent['_initials']}))) AS val"
        //                     : "UPPER(LEFT($mainTypesString, {$this->optionsCurrent['_initials']})) AS val"
        //             )
        //         ;
        //     }
        // } else {
        //     if ($this->optionsCurrent['locale']) {
        //         $qb
        //             ->select(
        //                 $val = 'refmeta.text AS val'
        //             )
        //             ->innerJoin('value', 'reference_metadata', 'refmeta', $expr->eq('refmeta.value_id', 'value.id'))
        //             ->andWhere($expr->in('refmeta.lang', ':locales'))
        //             ->setParameter('locales', $this->optionsCurrent['locale'], Connection::PARAM_STR_ARRAY)
        //         ;
        //     } else {
                $qb->select("$mainTypesString AS val");
        //     }
        // }

        // if ($this->optionsCurrent['output'] !== 'values') {
            $qb->addSelect('COUNT(resource.id) AS total');
        // }

        $this->filterByVisibility($qb, $data);

        $result = $qb->execute()->fetchAllAssociative();

        $result = array_combine(
            array_column($result, 'val'),
            array_column($result, 'total')
        );

        if($data['sort_by'] == 'total'){
            if($data['sort_order'] == 'desc'){
                arsort($result, SORT_NUMERIC);
            }else{
                asort($result, SORT_NUMERIC);
            }
        }else{
            if($data['sort_order'] == 'desc'){
                krsort($result, SORT_STRING);
            }else{
                ksort($result, SORT_STRING);
            }
        }   
        
        if(!empty($data['limit'])){
            $result = array_slice($result, 0, $data['limit']);
        //     $qb->setMaxResults($args['limit']);
        }

        return $result;
        // return $this
        //     // ->filterByVisibility($qb, 'properties')
        //     // ->filterByMainType($qb)
        //     // ->filterByDataType($qb)
        //     // ->filterByLanguage($qb)
        //     // ->filterByBeginOrEnd($qb, substr($val, 0, -7))
        //     // ->manageOptions($qb, 'properties', ['mainTypesString' => $mainTypesString])
        //     ->outputMetadata($qb, 'properties');
    }


    
    protected function filterByVisibility($qb, $data)
    {
        if ($this->getAcl()->userIsAllowed(\Omeka\Entity\Resource::class, 'view-all')) {
            return $this;
        }
        $expr = $qb->expr();
        if($this->getCurrentUserID()){
                
            $qb->andWhere($expr->or('resource.is_public = 1', 'resource.owner_id = :user_id'))
                ->andWhere($expr->or('value.is_public = 1', 'value.resource_id = (SELECT r.id FROM resource r WHERE r.owner_id = :user_id AND r.id = value.resource_id)'))
                ->setParameter('user_id', (int) $this->getCurrentUserID(), ParameterType::INTEGER);

        }else{
            if(isset($data['is_public'])){
                $qb->andWhere($expr->eq('resource.is_public', ':isPublic'))
                    ->setParameter('isPublic', (int) $data['is_public'], ParameterType::INTEGER);;
            }else{
                $qb->andWhere('resource.is_public = 1');
            }
            $qb->andWhere('value.is_public = 1');
        }

    }

}
