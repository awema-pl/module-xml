<?php
return [
    // this resources has been auto load to layout
    'dist' => [
        'js/main.js',
        'js/main.legacy.js',
        'css/main.css',
    ],
    'routes' => [

        // all routes is active
        'active' => true,

        // Administrator section.
        'admin' => [
            // section installations
            'installation' => [
                'active' => true,
                'prefix' => '/installation/xml',
                'name_prefix' => 'xml.admin.installation.',
                // this routes has beed except for installation module
                'expect' => [
                    'module-assets.assets',
                    'xml.admin.installation.index',
                    'xml.admin.installation.store',
                ]
            ],
            'setting' => [
                'active' => true,
                'prefix' => '/admin/xml/settings',
                'name_prefix' => 'xml.admin.setting.',
                'middleware' => [
                    'web',
                    'auth',
                    'can:manage_xml_settings'
                ]
            ],
        ],

        // User section
        'user' => [
            'source' => [
                'active' => true,
                'prefix' => '/xml/sources',
                'name_prefix' => 'xml.user.source.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
            'ceneosource' => [
                'active' => true,
                'prefix' => '/xml/ceneo-sources',
                'name_prefix' => 'xml.user.ceneosource.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'install_packages', 'manage_xml_settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Can merge permissions to module permission
    |--------------------------------------------------------------------------
    */
    'merge_permissions' => true,

    'installation' => [
        'auto_redirect' => [
            // user with this permission has been automation redirect to
            // installation package
            'permission' => 'install_packages'
        ]
    ],

    'database' => [
        'tables' => [
            'users' => 'users',
            'xml_settings' => 'xml_settings',
            'xml_sources' =>'xml_sources',
            'xml_ceneosources' =>'xml_ceneosources',
        ]
    ],

];
