<?php

$script = "<script>console.log('Hello World!');</script>";

return [
    'id' => 123,
    'scripts' => [
        [
            '@value' => $script,
            '@cdata' => true,
            '@attr' => [
                'type' => 'javascript',
                'id' => 1
            ]
        ],
        [
            '@value' => $script,
            '@cdata' => false,
            '@attr' => [
                'type' => 'javascript',
                'id' => 25
            ]
        ],
        [
            '@value' => $script,
            '@cdata' => true,
            '@attr' => [
                'type' => 'javascript',
                'id' => 303
            ]
        ]
    ],
    'MyScriptWithoutCDATA' => $script,
    'MyCDATAScript' => [
        '@value' => $script,
        '@cdata' => true,
        '@attr' => [
            'type' => 'javascript'
        ]
    ]
];
