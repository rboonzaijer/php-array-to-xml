<?php

use PHPUnit\Framework\TestCase;
use RefactorStudio\PhpArrayToXml\PhpArrayToXml;

class PhpArrayToXmlGettersSettersTest extends TestCase
{
    /** @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getDefaultRootName */
    public function test_it_should_get_the_default_root_name()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals('root', $class->getDefaultRootName());
    }

    /** @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getDefaultTagName */
    public function test_it_should_get_the_default_tag_name()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals('node', $class->getDefaultTagName());
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setVersion
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getVersion
     */
    public function test_it_should_get_and_set_the_version()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals('1.0', $class->getVersion());

        // Custom
        $class->setVersion('1.1');
        $this->assertEquals('1.1', $class->getVersion());
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setEncoding
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getEncoding
     */
    public function test_it_should_get_and_set_the_encoding()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals('UTF-8', $class->getEncoding());

        // Custom
        $class->setEncoding('ISO-8859-1');
        $this->assertEquals('ISO-8859-1', $class->getEncoding());
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setFormatOutput
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getFormatOutput
     */
    public function test_it_should_get_and_set_the_format_output()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals(false, $class->getFormatOutput());

        // Custom
        $class->setFormatOutput(true);
        $this->assertEquals(true, $class->getFormatOutput());
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setCustomRootName
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getCustomRootName
     */
    public function test_it_should_get_and_set_the_custom_root_name()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals(null, $class->getCustomRootName());

        // Custom
        $class->setCustomRootName('MyCustomRootName');
        $this->assertEquals('MyCustomRootName', $class->getCustomRootName());
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setCustomTagName
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getCustomTagName
     */
    public function test_it_should_get_and_set_the_custom_tag_name()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals(null, $class->getCustomTagName());

        // Custom
        $class->setCustomTagName('MyCustomTagName');
        $this->assertEquals('MyCustomTagName', $class->getCustomTagName());
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setCustomTagName
     */
    public function test_it_should_give_an_exception_when_setting_an_invalid_custom_tag_name()
    {
        $this->expectException(Exception::class);

        $class = new PhpArrayToXml();
        $class->setCustomTagName(123);
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setCustomRootName
     */
    public function test_it_should_give_an_exception_when_setting_an_invalid_custom_root_name()
    {
        $this->expectException(Exception::class);

        $class = new PhpArrayToXml();
        $class->setCustomRootName(123);
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setSeparator
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getSeparator
     */
    public function test_it_should_get_and_set_the_separator()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals('_', $class->getSeparator());

        // Custom
        $class->setSeparator('-');
        $this->assertEquals('-', $class->getSeparator());
    }

    /**
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::setTransformTags
     * @covers \RefactorStudio\PhpArrayToXml\PhpArrayToXml::getTransformTags
     */
    public function test_it_should_get_and_set_the_key_transform_method()
    {
        $class = new PhpArrayToXml();

        // Default
        $this->assertEquals(null, $class->getTransformTags());

        // Custom
        $class->setTransformTags(PhpArrayToXml::LOWERCASE);
        $this->assertEquals(PhpArrayToXml::LOWERCASE, $class->getTransformTags());

        $class->setTransformTags(PhpArrayToXml::UPPERCASE);
        $this->assertEquals(PhpArrayToXml::UPPERCASE, $class->getTransformTags());

        $class->setTransformTags(null);
        $this->assertEquals(null, $class->getTransformTags());
    }
}
