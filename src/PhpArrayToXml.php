<?php

namespace RefactorStudio\PhpArrayToXml;

use RefactorStudio\PhpArrayToXml\Lib\XmlPatterns;
use RefactorStudio\PhpArrayToXml\Traits\DomDocumentBuilder;

class PhpArrayToXml
{
    use DomDocumentBuilder;

    const LOWERCASE = 'lowercase';
    const UPPERCASE = 'uppercase';

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
            throw new \Exception('Not a valid root name: '.$value);
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
            throw new \Exception('Not a valid tag name: '.$value);
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
        switch ($value) {
            case self::LOWERCASE:
            case self::UPPERCASE: {
                $this->_transform_tags = $value;
                break;
            }
            default: {
                if ($value === null) {
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
     * @param null|boolean|string $value
     * @return PhpArrayToXml
     */
    public function setNumericTagSuffix($value = null)
    {
        $this->_numeric_tag_suffix = $value;

        if (is_bool($value) === true) {
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
        if (preg_match(XmlPatterns::getValidXmlTagStartPattern(), $value) === 1) {
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
        if (preg_match(XmlPatterns::getValidXmlTagNameChar(), $value) === 1) {
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
        if (empty($value) || is_int($value)) {
            return false;
        }

        if (preg_match(XmlPatterns::getValidXmlTagNamePattern(), $value) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Get the UPPERCASE constant
     *
     * @return string
     */
    protected function getConstantUpperCase()
    {
        return self::UPPERCASE;
    }

    /**
     * Get the LOWERCASE constant
     *
     * @return string
     */
    protected function getConstantLowerCase()
    {
        return self::LOWERCASE;
    }

    /**
     * Convert an array to XML
     *
     * @param array $array
     * @return string
     */
    public function toXmlString($array = [])
    {
        $this->createDomDocument($array);

        return $this->_doc->saveXML();
    }
}
