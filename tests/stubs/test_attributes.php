<?php

return [
    'id' => 123,
    'names' => [
        '@attr' => [
            'title' => 'Name',
            'category' => 'names'
        ],
        '@value' => [
            [
                '@attr' => [
                    'category' => 'names',
                    'family' => 'Doe'
                ],
                '@value' => 'John Doe'
            ],
            [
                '@attr' => [
                    'category' => 'names',
                    'family' => 'Doe'
                ],
                '@value' => 'Jane Doe'
            ],
            'OtherKey' => [
                '@attr' => [
                    'other_tag' => 'None'
                ],
                '@value' => 'My Example'
            ],
        ]
    ],
    'OtherValues' => [
        'value',
        [
            '@value' => 'value',
            '@attr' => [
                'message' => 'duplicate',
                'second_message' => 'another duplicate'
            ]
        ],
        'value',
    ],
    'value',
    [
        '@attr' => [
            'tag' => 'example'
        ],
        '@value' => null
    ]
];
