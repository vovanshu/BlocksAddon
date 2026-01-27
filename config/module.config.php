<?php
namespace BlocksAddon;

return [
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
            [
                'type' => 'gettext',
                'base_dir' => OMEKA_PATH . '/files/languages/BlocksAddon',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'block_layouts' => [
        'factories' => [
            'propertylistvalues' => Service\BlockLayout\PropertyListValuesFactory::class,
            'itemssliderslick' => Service\BlockLayout\ItemsSliderSlickFactory::class,
            'itemslist' => Service\BlockLayout\ItemsListFactory::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\PropertyListValuesFieldset::class => Form\PropertyListValuesFieldset::class,
            Form\ItemsListFieldset::class => Form\ItemsListFieldset::class,
        ],
        'factories' => [
            'Omeka\Form\BlockLayoutDataForm' => Service\Form\BlockLayoutDataFormFactory::class,
        ]
    ],
    'BlocksAddon' => [
        'config' => [
            'developing' => True,
            // 'backups' => OMEKA_PATH.'/files/backups/BlocksAddon/',
            // 'path_permissions' => dirname(__DIR__).'/data/permissions',
            'options' =>  [
            ],
            'default_falues' => [
                'itemslist' => [
                    'blockTitle' => '',
                    'blockStyle' => '',
                    'blockTitleStyle' => '',
                    'listContentStyle' => '',
                    'entrieStyle' => '',
                    'textStyle' => '',
                    'titleStyle' => '',
                    'titleStyleLink' => '',
                    'captionStyle' => '',
                    'thumbnailStyle' => '',
                    'captionShow' => 'false',
                    'thumbnailShow' => 'false',
                    'entriesPerPage' => 10,
                    'navByPage' => 'false',
                    'site_attachments_only' => 'true',
                    'query' => '',
                    'itemTitleAsLink' => 'true',
                    'buttonViewShow' => 'false',
                    'buttonView' => 'View', // @translate
                    'buttonViewStyle' => '',
                    'buttonViewStyleLnk' => '',
                ]
            ]
        ]
    ]
];
