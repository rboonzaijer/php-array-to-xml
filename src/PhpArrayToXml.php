<?php

namespace RefactorStudio\PhpArrayToXml;

use DOMDocument;
use DOMElement;

class PhpArrayToXml
{
    const LOWERCASE = 'lowercase';
    const UPPERCASE = 'uppercase';

    protected $_doc;
    protected $_version = '1.0';
    protected $_encoding = 'UTF-8';
    protected $_default_root_name = 'root';
    protected $_custom_root_name = null;
    protected $_default_tag_name = 'node';
    protected $_custom_tag_name = null;
    protected $_separator = '_';
    protected $_transform_tags = null;
    protected $_format_output = false;
    protected $_numeric_tag_suffix = null;
    protected $_default_boolean_value_true = 'true';
    protected $_default_boolean_value_false = 'false';
    protected $_default_null_value = null;

    /**
     * Set the version of the XML (Default = '1.0')
     *
     * @param string $value
     * @return PhpArrayToXml
     */
    public function setVersion($value = '1.0')
    {
        $this->_version = $value;

        return $this;
    }

    /**
     * Get the version of the XML
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Set the encoding of the XML (Default = 'UTF-8')
     *
     * @param string $value
     * @return PhpArrayToXml
     */
    public function setEncoding($value = 'UTF-8')
    {
        $this->_encoding = $value;

        return $this;
    }

    /**
     * Get the encoding of the XML
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    /**
     * Set the format output of the XML
     *
     * @param bool $value
     * @return PhpArrayToXml
     */
    public function setFormatOutput($value = true)
    {
        $this->_format_output = ($value === true ? true : false);

        return $this;
    }

    /**
     * Alias for setFormatOutput(true)
     *
     * @return PhpArrayToXml
     */
    public function prettify()
    {
        $this->setFormatOutput(true);

        return $this;
    }

    /**
     * Get the format output of the XML
     *
     * @return bool
     */
    public function getFormatOutput()
    {
        return $this->_format_output;
    }

    /**
     * Set the custom root name of the XML
     *
     * @param $value
     * @return PhpArrayToXml
     * @throws \Exception
     */
    public function setCustomRootName($value)
    {
        if (!$this->isValidXmlTag($value)) {
            throw new \Exception('Not a valid root name: ' . $value);
        }

        $this->_custom_root_name = $value;

        return $this;
    }

    /**
     * Get the custom root name of the XML
     *
     * @return string
     */
    public function getCustomRootName()
    {
        return $this->_custom_root_name;
    }

    /**
     * Get the default root name of the XML
     *
     * @return string
     */
    public function getDefaultRootName()
    {
        return $this->_default_root_name;
    }

    /**
     * Set the custom tag name of the XML (only used for inner arrays)
     *
     * @param $value
     * @return PhpArrayToXml
     * @throws \Exception
     */
    public function setCustomTagName($value)
    {
        if (!$this->isValidXmlTag($value)) {
            throw new \Exception('Not a valid tag name: ' . $value);
        }

        $this->_custom_tag_name = $value;

        return $this;
    }

    /**
     * Get the custom tag name of the XML (only used for inner arrays)
     *
     * @return string
     */
    public function getCustomTagName()
    {
        return $this->_custom_tag_name;
    }

    /**
     * Get the default tag name of the XML (only used for inner arrays)
     *
     * @return string
     */
    public function getDefaultTagName()
    {
        return $this->_default_tag_name;
    }

    /**
     * Set the value for the separator that will be used to replace special characters in tag names
     *
     * @param $value
     * @return PhpArrayToXml
     */
    public function setSeparator($value)
    {
        $this->_separator = $value;

        return $this;
    }

    /**
     * Get the value for the separator that will be used to replace special characters in tag names
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->_separator;
    }

    /**
     * Set the transformation method for tag names
     * Possible values:
     * - null
     * - 'uppercase'
     * - 'lowercase'
     *
     * @param $value
     * @return PhpArrayToXml
     */
    public function setTransformTags($value = null)
    {
        switch($value) {
            case self::LOWERCASE:
            case self::UPPERCASE: {
                $this->_transform_tags = $value;
                break;
            }
            default: {
                if($value === null) {
                    $this->_transform_tags = null;
                }
            }
        }

        return $this;
    }

    /**
     * Get the transformation method for tag names
     *
     * @return string
     */
    public function getTransformTags()
    {
        return $this->_transform_tags;
    }

    /**
     * Set the numeric tag suffix
     *
     * @param null|string $value
     * @return PhpArrayToXml
     */
    public function setNumericTagSuffix($value = null)
    {
        $this->_numeric_tag_suffix = $value;

        if($value === true || $value === false) {
            $this->_numeric_tag_suffix = '';
        }
        return $this;
    }

    /**
     * Get the numeric tag suffix
     *
     * @return null
     */
    public function getNumericTagSuffix()
    {
        return $this->_numeric_tag_suffix;
    }

    /**
     * Cast real boolean (true) values to a given string
     *
     * @param string $value
     * @return PhpArrayToXml
     */
    public function setCastBooleanValueTrue($value = 'true')
    {
        $this->_default_boolean_value_true = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCastBooleanValueTrue()
    {
        return $this->_default_boolean_value_true;
    }

    /**
     * Cast real boolean (false) values to a given string
     *
     * @param string $value
     * @return PhpArrayToXml
     */
    public function setCastBooleanValueFalse($value = 'false')
    {
        $this->_default_boolean_value_false = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCastBooleanValueFalse()
    {
        return $this->_default_boolean_value_false;
    }

    /**
     * Cast real null values to a given string
     *
     * @param string $value
     * @return PhpArrayToXml
     */
    public function setCastNullValue($value = null)
    {
        $this->_default_null_value = $value;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCastNullValue()
    {
        return $this->_default_null_value;
    }

    /**
     * Validate if a given value has a proper tag starting character to be used in XML
     *
     * @param null|string $value
     * @return bool
     */
    public static function hasValidXmlTagStartingChar($value = null)
    {
        if(preg_match(self::getValidXmlTagStartPattern(), $value) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Validate if a given value is a valid tag character
     *
     * @param null|string $value
     * @return bool
     */
    public static function isValidXmlTagChar($value = null)
    {
        if(preg_match(self::getValidXmlTagNameChar(), $value) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Validate if a given value is a proper tag name to be used in XML
     *
     * @param null|string $value
     * @return bool
     */
    public static function isValidXmlTag($value = null)
    {
        if(empty($value) || is_int($value)) {
            return false;
        }

        if(preg_match(self::getValidXmlTagNamePattern(), $value) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Convert an array to XML
     *
     * @param array $array
     * @return string
     */
    public function toXmlString($array = [])
    {
        $this->_doc = new DOMDocument($this->getVersion(), $this->getEncoding());
        $this->_doc->formatOutput = $this->getFormatOutput();

        $root = $this->_doc->createElement($this->createValidRootName($this->getCustomRootName()));

        $this->_doc->appendChild($root);

        $this->addArrayElements($root, $array);

        return $this->_doc->saveXML();
    }

    /**
     * Get a regex pattern for valid tag names
     *
     * @return string
     */
    protected static function getValidXmlTagNamePattern()
    {
        return '~
            # XML 1.0 Name symbol PHP PCRE regex <http://www.w3.org/TR/REC-xml/#NT-Name>
            (?(DEFINE)
                (?<NameStartChar> [:A-Z_a-z\\xC0-\\xD6\\xD8-\\xF6\\xF8-\\x{2FF}\\x{370}-\\x{37D}\\x{37F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}\\x{FDF0}-\\x{FFFD}\\x{10000}-\\x{EFFFF}])
                (?<NameChar>      (?&NameStartChar) | [.\\-0-9\\xB7\\x{0300}-\\x{036F}\\x{203F}-\\x{2040}])
                (?<Name>          (?&NameStartChar) (?&NameChar)*)
            )
            ^(?&Name)$
            ~ux';
    }

    /**
     * Get a regex pattern for valid tag chars
     *
     * @return string
     */
    protected static function getValidXmlTagNameChar()
    {
        return '~
            (?(DEFINE)
                (?<NameStartChar> [:A-Z_a-z\\xC0-\\xD6\\xD8-\\xF6\\xF8-\\x{2FF}\\x{370}-\\x{37D}\\x{37F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}\\x{FDF0}-\\x{FFFD}\\x{10000}-\\x{EFFFF}])
                (?<NameChar>      (?&NameStartChar) | [.\\-0-9\\xB7\\x{0300}-\\x{036F}\\x{203F}-\\x{2040}])
            )
            ^(?&NameChar)$
            ~ux';
    }

    /**
     * Get a regex pattern for valid tag starting characters
     *
     * @return string
     */
    protected static function getValidXmlTagStartPattern()
    {
        return '~^([:A-Z_a-z\\xC0-\\xD6\\xD8-\\xF6\\xF8-\\x{2FF}\\x{370}-\\x{37D}\\x{37F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}\\x{FDF0}-\\x{FFFD}\\x{10000}-\\x{EFFFF}])~ux';
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
                    $node = $this->createElement($name, $value);
                    $parent->appendChild($node);
                } else {

                    if(array_key_exists('@value', $value)) {
                        $cdata = array_key_exists('@cdata', $value) && $value['@cdata'] === true ? true : false;
                        $attributes = array_key_exists('@attr', $value) && is_array($value['@attr']) ? $value['@attr'] : [];

                        if(!is_array($value['@value'])) {
                            // Create an XML element
                            $node = $this->createElement($name, $value['@value'], $cdata, $attributes);
                            $parent->appendChild($node);
                        } else {
                            // Create an empty XML element 'container'
                            $node = $this->createElement($name, null);

                            foreach($attributes as $attribute_name => $attribute_value) {
                                $node->setAttribute($attribute_name, $this->normalizeAttributeValue($attribute_value));
                            }

                            $parent->appendChild($node);

                            // Add all the elements within the array to the 'container'
                            $this->addArrayElements($node, $value['@value']);
                        }
                    }
                    else {
                        // Create an empty XML element 'container'
                        $node = $this->createElement($name, null);
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
        if($value === true) {
            return $this->getCastBooleanValueTrue();
        }

        if($value === false) {
            return $this->getCastBooleanValueFalse();
        }

        if($value === null) {
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
        if($value === true) {
            return 'true';
        }

        if($value === false) {
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
    protected function createElement($name, $value = null, $cdata = false, $attributes = [])
    {
        $name = $this->createValidTagName($name);

        if($cdata === true) {
            $element = $this->_doc->createElement($name);
            $element->appendChild($this->_doc->createCDATASection($value));

            foreach($attributes as $attribute_name => $attribute_value) {
                $element->setAttribute($attribute_name, $this->normalizeAttributeValue($attribute_value));
            }

            return $element;
        }

        $element = $this->_doc->createElement($name, $this->normalizeValue($value));

        foreach($attributes as $attribute_name => $attribute_value) {
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
        if(empty($name) || $this->isNumericKey($name)) {
            $key = $name;

            if ($this->isValidXmlTag($this->getCustomTagName())) {
                $name = $this->getCustomTagName();
            } else {
                $name = $this->transformTagName($this->getDefaultTagName());
            }

            if($this->getNumericTagSuffix() !== null) {
                $name = $name . (string)$this->getNumericTagSuffix() . $key;
            }
            return $name;
        }

        if(!$this->isValidXmlTag($name)) {
            $name = $this->replaceInvalidTagChars($name);

            if(!self::hasValidXmlTagStartingChar($name)) {
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
        return '_' . substr($value, 1);
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
        for($i=0; $i < strlen($value); $i++) {
            if(!self::isValidXmlTagChar($value[$i])) {
                $pattern .= "\\$value[$i]";
            }
        }

        if(!empty($pattern)) {
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
        switch($this->getTransformTags()) {
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
