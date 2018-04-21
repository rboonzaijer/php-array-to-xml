<?php

namespace RefactorStudio\PhpArrayToXml\Lib;

class XmlPatterns
{
    /**
     * Get a regex pattern for valid tag names
     *
     * @return string
     */
    public static function getValidXmlTagNamePattern()
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
    public static function getValidXmlTagNameChar()
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
    public static function getValidXmlTagStartPattern()
    {
        return '~^([:A-Z_a-z\\xC0-\\xD6\\xD8-\\xF6\\xF8-\\x{2FF}\\x{370}-\\x{37D}\\x{37F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}\\x{FDF0}-\\x{FFFD}\\x{10000}-\\x{EFFFF}])~ux';
    }
}