<?php

return [

    'styles' => [


        'app' => [
            'compiled_path' => public_path('css/app.css'),
            'alias' => [
                '@' => resource_path('sass'),
            ],
            'required' => [
                'file' => '',
                'namespace' => '',
            ]
        ]
    ]
];
