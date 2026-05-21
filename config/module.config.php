<?php
namespace BlocksAddon;

return [
    'permissions' => [
        'labels' => [
            'settings_vocabularyaddon' => 'Settings Blocks Addition', // @translate
        ],
        'rules' => [
            'modules' => [
                'BlocksAddon\Controller\Admin\SettingsController' => [
                    'settings_blocksaddon' => [
                        'edit', 'backups', 'backuping', 'restore-confirm', 'restore'
                    ],
                ],
            ],
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'BlocksAddon' => Service\ControllerPlugin\GeneralPluginFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            'BlocksAddon' => Service\ControllerPlugin\GeneralPluginFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\Admin\SettingsController::class => Service\Controller\Admin\SettingsControllerFactory::class,
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
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'blocks-addon-settings' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/blocks-addon-settings[/:action][/:name]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'name' => '[.a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'BlocksAddon\Controller\Admin',
                                '__CONTROLLER__' => 'Settings',
                                'controller' => Controller\Admin\SettingsController::class,
                                'action' => 'edit',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'BlocksAddon' => [
        'backups' => OMEKA_PATH.'/files/backups/BlocksAddon/',
        'settings' =>  [],
        'default_values' => [
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
];
