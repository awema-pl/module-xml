<?php

return [
    'merge_to_navigation' => true,

    'navs' => [
        'sidebar' =>[
            [
                'name' => 'XML',
                'link' => '/xml/sources',
                'icon' => 'report',
                'key' => 'xml::menus.xml',
                'children_top' => [
                    [
                        'name' => 'All XML sources',
                        'link' => '/xml/sources',
                        'key' => 'xml::menus.sources',
                    ],
                    [
                        'name' => 'XML Ceneo sources',
                        'link' => '/xml/ceneo-sources',
                        'key' => 'xml::menus.ceneosources',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'All XML sources',
                        'link' => '/xml/sources',
                        'key' => 'xml::menus.sources',
                    ],
                    [
                        'name' => 'XML Ceneo sources',
                        'link' => '/xml/ceneo-sources',
                        'key' => 'xml::menus.ceneosources',
                    ],
                ],
            ]
        ],
        'adminSidebar' =>[
            [
                'name' => 'Settings',
                'link' => '/admin/xml/settings',
                'icon' => 'speed',
                'permissions' => 'manage_xml_settings',
                'key' => 'xml::menus.xml',
                'children_top' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/xml/settings',
                        'key' => 'xml::menus.settings',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/xml/settings',
                        'key' => 'xml::menus.settings',
                    ],
                ],
            ]
        ]
    ]
];
