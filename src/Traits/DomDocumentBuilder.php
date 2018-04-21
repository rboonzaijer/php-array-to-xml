<?php

namespace RefactorStudio\PhpArrayToXml\Traits;

use DOMDocument;
use DOMElement;

trait DomDocumentBuilder
{
    protected $_doc;

    abstract public function getEncoding();
    abstract public function getVersion();
    abstract public function getFormatOutput();
    abstract public function getCustomRootName();
    abstract public function getCastBooleanValueTrue();
    abstract public function getCastBooleanValueFalse();
    abstract public function getCastNullValue();
    abstract public function getCustomTagName();
    abstract public function getDefaultTagName();
    abstract public function getNumericTagSuffix();
    abstract public function getSeparator();
    abstract public function getDefaultRootName();
    abstract public function getTransformTags();
    abstract protected function getConstantUpperCase();
    abstract protected function getConstantLowerCase();
    abstract public static function isValidXmlTag($value);
    abstract public static function isValidXmlTagChar($value);
    abstract public static function hasValidXmlTagStartingChar($value);

    /**
     * Creates a DOMDocument from an array
     *
     * @param array $array
     */
    protected function createDomDocument($array = [])
    {
        $this->_doc = new DOMDocument($this->getVersion(), $this->getEncoding());
        $this->_doc->formatOutput = $this->getFormatOutput();

        $root = $this->_doc->createElement($this->createValidRootName($this->getCustomRootName()));

        $this->_doc->appendChild($root);

        $this->createElementsFromArray($root, $array);
    }

    /**
     * Converts arrays to DOMDocument elements
     *
     * @param DOMElement $parent
     * @param array $array
     */
    protected function createElementsFromArray(DOMElement $parent, $array = [])
    {
        foreach ($array as $name => $value) {
            if (!is_array($value)) {
                // Create an XML element
                $node = $this->createXmlElement($name, $value);
                $parent->appendChild($node);
            } else {
                if (array_key_exists('@value', $value)) {
                    $this->createAdvancedXmlElement($parent, $value, $name);
                } else {
                    // Create an empty XML element 'container'
                    $node = $this->createXmlElement($name, null);
                    $parent->appendChild($node);

                    // Add all the elements within the array to the 'container'
                    $this->createElementsFromArray($node, $value);
                }
            }
        }
    }

    /**
     * Create an 'advanced' XML element, when the array has '@value' in it
     *
     * @param DOMElement $parent
     * @param $value
     * @param $name
     * @return DOMElement
     */
    protected function createAdvancedXmlElement(DOMElement $parent, $value, $name): DOMElement
    {
        $cdata = array_key_exists('@cdata', $value) && $value['@cdata'] === true ? true : false;
        $attributes = array_key_exists('@attr', $value) && is_array($value['@attr']) ? $value['@attr'] : [];

        if (!is_array($value['@value'])) {
            // Create an XML element
            $node = $this->createXmlElement($name, $value['@value'], $cdata, $attributes);

            $parent->appendChild($node);
        } else {
            // Create an empty XML element 'container'
            $node = $this->createXmlElement($name, null);

            foreach ($attributes as $attribute_name => $attribute_value) {
                $node->setAttribute($attribute_name, $this->normalizeAttributeValue($attribute_value));
            }
            $parent->appendChild($node);

            // Add all the elements within the array to the 'container'
            $this->createElementsFromArray($node, $value['@value']);
        }
        return $node;
    }

    /**
     * Normalize a value (replace some characters)
     *
     * @param $value
     * @return null|string
     */
    protected function normalizeValue($value)
    {
        if ($value === true) {
            return $this->getCastBooleanValueTrue();
        }

        if ($value === false) {
            return $this->getCastBooleanValueFalse();
        }

        if ($value === null) {
            return $this->getCastNullValue();
        }

        return $value;
    }

    /**
     * Normalize an attribute value (replace some characters)
     *
     * @param $value
     * @return string
     */
    protected function normalizeAttributeValue($value)
    {
        if ($value === true) {
            return 'true';
        }

        if ($value === false) {
            return 'false';
        }

        return $value;
    }

    /**
     * See if a value matches an integer (could be a integer within a string)
     *
     * @param $value
     * @return bool
     */
    protected function isNumericKey($value)
    {
        $pattern = '~^(0|[1-9][0-9]*)$~ux';

        return preg_match($pattern, $value) === 1;
    }

    /**
     * Creates an element for DOMDocument
     *
     * @param $name
     * @param null|string $value
     * @param bool $cdata
     * @param array $attributes
     * @return DOMElement
     */
    protected function createXmlElement($name, $value = null, $cdata = false, $attributes = [])
    {
        $name = $this->createValidTagName($name);

        if ($cdata === true) {
            $element = $this->_doc->createElement($name);
            $element->appendChild($this->_doc->createCDATASection($value));

            foreach ($attributes as $attribute_name => $attribute_value) {
                $element->setAttribute($attribute_name, $this->normalizeAttributeValue($attribute_value));
            }

            return $element;
        }

        $element = $this->_doc->createElement($name, $this->normalizeValue($value));

        foreach ($attributes as $attribute_name => $attribute_value) {
            $element->setAttribute($attribute_name, $this->normalizeAttributeValue($attribute_value));
        }

        return $element;
    }

    /**
     * Creates a valid tag name
     *
     * @param null|string $name
     * @return string
     */
    protected function createValidTagName($name = null)
    {
        if (empty($name) || $this->isNumericKey($name)) {
            return $this->createValidTagNameFromNumericValue($name);
        }

        if (!$this->isValidXmlTag($name)) {
            $name = $this->makeTagNameValid($name);
        }
        return $this->transformTagName($name);
    }

    /**
     * Make a tag name valid (replace invalid characters including starting characters)
     *
     * @param $name
     * @return null|string|string[]
     */
    protected function makeTagNameValid($name)
    {
        $name = $this->replaceInvalidTagChars($name);

        if (!self::hasValidXmlTagStartingChar($name)) {
            $name = $this->prefixInvalidTagStartingChar($name);
        }
        return $name;
    }

    /**
     * Create a valid tag name from a numeric value
     *
     * @param $name
     * @return null|string
     */
    protected function createValidTagNameFromNumericValue($name)
    {
        $key = $name;

        if ($this->isValidXmlTag($this->getCustomTagName())) {
            $name = $this->getCustomTagName();
        } else {
            $name = $this->transformTagName($this->getDefaultTagName());
        }

        if ($this->getNumericTagSuffix() !== null) {
            $name = $name.(string) $this->getNumericTagSuffix().$key;
        }
        return $name;
    }

    /**
     * If a tag has an invalid starting character, use an underscore as prefix
     *
     * @param $value
     * @return string
     */
    protected function prefixInvalidTagStartingChar($value)
    {
        return '_'.substr($value, 1);
    }

    /**
     * Replace invalid tag characters
     *
     * @param $value
     * @return null|string|string[]
     */
    protected function replaceInvalidTagChars($value)
    {
        $pattern = '';
        for ($i = 0; $i < strlen($value); $i++) {
            if (!self::isValidXmlTagChar($value[$i])) {
                $pattern .= "\\$value[$i]";
            }
        }

        if (!empty($pattern)) {
            $value = preg_replace("/[{$pattern}]/", $this->getSeparator(), $value);
        }
        return $value;
    }

    /**
     * Creates a valid root name
     *
     * @param null|string $name
     * @return string
     */
    protected function createValidRootName($name = null)
    {
        if (is_string($name)) {
            $name = preg_replace("/[^_a-zA-Z0-9]/", $this->getSeparator(), $name);
        }
        if ($this->isValidXmlTag($name)) {
            return $name;
        }
        return $this->transformTagName($this->getDefaultRootName());
    }

    /**
     * Transforms a tag name (only when specified)
     *
     * @param null|string $name
     * @return null|string
     */
    protected function transformTagName($name = null)
    {
        switch ($this->getTransformTags()) {
            case $this->getConstantLowerCase(): {
                return strtolower($name);
            }
            case $this->getConstantUpperCase(): {
                return strtoupper($name);
            }
            default: {
                return $name;
            }
        }
    }
}