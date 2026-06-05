<?php

return [
    'routes' => [
        [
            'name' => 'settings#load',
            'url' => '/settings/load',
            'verb' => 'GET'
        ],
        [
            'name' => 'settings#getOptions',
            'url' => '/settings/options',
            'verb' => 'GET'
        ],
        [
            'name' => 'settings#save',
            'url' => '/settings/save',
            'verb' => 'POST'
        ],
        [
            'name' => 'settings#saveAdmin',
            'url' => '/settings/save-admin',
            'verb' => 'POST'
        ],
        [
            'name' => 'verse#getVerse',
            'url' => '/api/verse',
            'verb' => 'GET'
        ],
    ]
];
