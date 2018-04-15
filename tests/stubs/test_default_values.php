<?php

return [
    'StringTrue' => 'true',
    'StringFalse' => 'false',
    'BooleanTrue' => true,
    'BooleanFalse' => false,
    'StringNull' => 'null',
    'StringEmpty' => '',
    'RealNull' => null,
    'Float' => 0.987654321,
    'Integer' => 12345,
    'String' => 'Example',
    'CData' => [
        '@value' => '<span>CData Example</span>',
        '@cdata' => true
    ],
    'Attributes' => [
        '@value' => 'Test',
        '@attr' => [
            'AttrBooleanTrue' => true,
            'AttrBooleanFalse' => false,
            'AttrString' => 'StringValue',
            'AttrStringNull' => 'null',
            'AttrStringEmpty' => '',
            'AttrRealNull' => null,
            'AttrFloat' => 0.987654321,
            'AttrInteger' => 12345
        ]
    ],
    'CDataWithAttributes' => [
        '@value' => '<span>Value</span>',
        '@cdata' => true,
        '@attr' => [
            'Attr1' => 'First',
            'Attr2' => 'Second'
        ]
    ]
];