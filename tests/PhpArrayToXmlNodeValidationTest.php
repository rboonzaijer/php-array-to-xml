<?php

use PHPUnit\Framework\TestCase;
use RefactorStudio\PhpArrayToXml\PhpArrayToXml;

class PhpArrayToXmlNodeValidationTest extends TestCase
{
    protected $valid_tag_names = [
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

    protected $invalid_tag_names = [
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

    protected $tag_names_with_invalid_starting_characters = [
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

    public function test_valid_tag_starting_characters()
    {
        foreach($this->valid_tag_names as $tag) {
            $this->assertTrue(PhpArrayToXml::hasValidXmlTagStartingChar($tag), 'Not a valid starting character in: ' . $tag);
        }
    }

    public function test_valid_tag_name()
    {
        foreach($this->valid_tag_names as $tag) {
            $this->assertTrue(PhpArrayToXml::isValidXmlTag($tag), 'This is not a valid tag name: ' . $tag);
        }
    }

    public function test_valid_tag_character_except_starting_char()
    {
        $chars = ['a', '_', ':'];
        foreach($chars as $char) {
            $this->assertTrue(PhpArrayToXml::isValidXmlTagChar($char));
        }
    }

    public function test_invalid_tag_starting_characters()
    {
        foreach($this->tag_names_with_invalid_starting_characters as $tag) {
            $this->assertFalse(PhpArrayToXml::hasValidXmlTagStartingChar($tag), 'Valid starting character found in: ' . $tag);
        }
    }

    public function test_invalid_tag_name()
    {
        $tags = array_merge($this->tag_names_with_invalid_starting_characters, $this->invalid_tag_names);

        foreach($tags as $tag) {
            $this->assertFalse(PhpArrayToXml::isValidXmlTag($tag), 'This is a valid tag name: ' . $tag);
        }
    }

    public function test_invalid_tag_character_except_starting_char()
    {
        $chars = [' ', '<', '>', '&', '|', '@'];
        foreach($chars as $char) {
            $this->assertFalse(PhpArrayToXml::isValidXmlTagChar($char));
        }
    }
}
