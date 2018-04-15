<?php

use PHPUnit\Framework\TestCase;
use RefactorStudio\PhpArrayToXml\PhpArrayToXml;

class PhpArrayToXmlNodeValidationTest extends TestCase
{
    protected $valid_node_names = [
        'node',
        'NoDe',
        'NODE',
        'no.de',
        'no-de',
        'no_de',
        'no:de',
        'no..de',
        'no--de',
        'no__de',
        'no::de',
        'n_o-d:e',
        'no5de',
        '_node',
        'node-',
        'node_',
        'node:',
        '_node',
        '_node_',
        '_node-',
        ':node',
    ];

    protected $invalid_node_names = [
        'no!de',
        'no@de',
        'no#de',
        'no$de',
        'no%de',
        'no^de',
        'no&de',
        'no*de',
        'no(de',
        'no)de',
        'no{de',
        'no}de',
        'no[de',
        'no]de',
        'no|de',
        ' node ',
        'node ',
        'no de',
    ];

    protected $node_names_with_invalid_starting_characters = [
        -1,
        0,
        123,
        '-1',
        '123',
        '123node',
        '-node',
        '!node',
        '@node',
        '#node',
        '$node',
        '%node',
        '^node',
        '&node',
        '*node',
        '(node',
        ')node',
        '{node',
        '}node',
        '[node',
        ']node',
        '|node',
        "'node'",
        '"node"',
        '`node`',
        ' node',
    ];

    public function test_valid_node_starting_characters()
    {
        foreach($this->valid_node_names as $node) {
            $this->assertTrue(PhpArrayToXml::hasValidNodeStart($node), 'Not a valid starting character in: ' . $node);
        }
    }

    public function test_valid_node_name()
    {
        foreach($this->valid_node_names as $node) {
            $this->assertTrue(PhpArrayToXml::isValidNodeName($node), 'This is not a valid node name: ' . $node);
        }
    }

    public function test_valid_node_character_except_starting_char()
    {
        $chars = ['a', '_', ':'];
        foreach($chars as $char) {
            $this->assertTrue(PhpArrayToXml::isValidNodeNameChar($char));
        }
    }

    public function test_invalid_node_starting_characters()
    {
        foreach($this->node_names_with_invalid_starting_characters as $node) {
            $this->assertFalse(PhpArrayToXml::hasValidNodeStart($node), 'Valid starting character found in: ' . $node);
        }
    }

    public function test_invalid_node_name()
    {
        $nodes = array_merge($this->node_names_with_invalid_starting_characters, $this->invalid_node_names);

        foreach($nodes as $node) {
            $this->assertFalse(PhpArrayToXml::isValidNodeName($node), 'This is a valid node name: ' . $node);
        }
    }

    public function test_invalid_node_character_except_starting_char()
    {
        $chars = [' ', '<', '>', '&', '|', '@'];
        foreach($chars as $char) {
            $this->assertFalse(PhpArrayToXml::isValidNodeNameChar($char));
        }
    }
}
