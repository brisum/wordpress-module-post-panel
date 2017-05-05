<?php

$modulePath = realpath(__DIR__ . '/../');

return [
    'preference' => [

    ],

    'virtualType' => [
        'Brisum\Wordpress\PostPanel\View' => [
            'type' => 'Brisum\Lib\View',
            'shared' => true,
            'arguments' => [
                'dirTemplate' => ['value' => $modulePath]
            ]
        ],
    ],

    'type' => [
        'Brisum\Wordpress\PostPanel\Panel' => [
            'shared' => true
        ],
        'Brisum\Wordpress\PostPanel\Panel\TaxonomyEdit' => [
            'shared' => false,
            'arguments' => [
                'view' => [
                    'type' => 'object',
                    'value' => 'Brisum\Wordpress\PostPanel\View'
                ]
            ]
        ],
    ]
];
