<?php

namespace NordCode\RoboParameters\Test\Reader;

use NordCode\RoboParameters\Reader\SymfonyXmlReader;

class SymfonyXmlReaderTest extends ReaderTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new SymfonyXmlReader();
    }

    /**
     * @inheritDoc
     */
    protected function getTestFileContent()
    {
        return <<<EOL
<?xml version="1.0" encoding="UTF-8" ?>
<!--
    Taken from http://symfony.com/doc/current/components/dependency_injection/parameters.html#array-parameters
-->
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <parameters>
        <parameter key="my_mailer.gateways" type="collection">
            <parameter>mail1</parameter>
            <parameter>mail2</parameter>
            <parameter>mail3</parameter>
        </parameter>
        <parameter key="my_multilang.language_fallback">
            <parameter key="en" type="collection">
                <parameter>en</parameter>
                <parameter>fr</parameter>
            </parameter>
            <parameter key="fr" type="collection">
                <parameter>fr</parameter>
                <parameter>en</parameter>
            </parameter>
        </parameter>
        <parameter key="empty"/>
    </parameters>
</container>
EOL;
    }

    /**
     * @inheritDoc
     */
    protected function getExpectedResultArray()
    {
        return array(
            'parameters' => array(
                'my_mailer.gateways' => array('mail1', 'mail2', 'mail3'),
                'my_multilang.language_fallback' => array(
                    'en' => array('en', 'fr'),
                    'fr' => array('fr', 'en')
                ),
                'empty' => null
            )
        );
    }
}
