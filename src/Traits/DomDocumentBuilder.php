<?php

namespace RefactorStudio\PhpArrayToXml\Traits;

use DOMDocument;
use DOMElement;

trait DomDocumentBuilder
{
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

        $this->addArrayElements($root, $array);
    }

    /**
     * Converts arrays to DOMDocument elements
     *
     * @param DOMElement $parent
     * @param array $array
     */
    protected function addArrayElements(DOMElement $parent, $array = [])
    {
        if (is_array($array)) {
            foreach ($array as $name => $value) {
                if (!is_array($value)) {
                    // Create an XML element
                    $node = $this->createXmlElement($name, $value);
                    $parent->appendChild($node);
                } else {

                    if (array_key_exists('@value', $value)) {
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
                            $this->addArrayElements($node, $value['@value']);
                        }
                    } else {
                        // Create an empty XML element 'container'
                        $node = $this->createXmlElement($name, null);
                        $parent->appendChild($node);

                        // Add all the elements within the array to the 'container'
                        $this->addArrayElements($node, $value);
                    }
                }
            }
        }
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

        if (!$this->isValidXmlTag($name)) {
            $name = $this->replaceInvalidTagChars($name);

            if (!self::hasValidXmlTagStartingChar($name)) {
                $name = $this->prefixInvalidTagStartingChar($name);
            }
        }
        return $this->transformTagName($name);
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
            case self::LOWERCASE: {
                return strtolower($name);
            }
            case self::UPPERCASE: {
                return strtoupper($name);
            }
            default: {
                return $name;
            }
        }
    }
}