<?php

$script = <<<EOL
<script>
  console.log('Hello World!');
  console.log("Hello World!");
</script>
EOL;

return [
    'id' => 123,
    'script' => [
        '@value' => $script,
        '@cdata' => true
    ],
    'MyArray' => [
        [
            '@value' => $script,
            '@cdata' => true,
        ],
        [
            '@value' => [
                'MyChildKey' => [
                    '@value' => $script,
                    '@cdata' => true
                ]
            ],
        ],
        'children' => [
            '@value' => [
                'child-1' => [
                    '@value' => $script,
                    '@cdata' => true
                ],
                'child-2' => [
                    '@value' => $script,
                    '@cdata' => true
                ],
                'child-3' => 'Just a value'
            ],
        ],
        'MyKeyName' => [
            '@value' => $script,
            '@cdata' => true,
        ],
    ]
];
