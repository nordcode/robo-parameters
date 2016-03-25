<?php

namespace NordCode\RoboParameters\Test\Reader;

use NordCode\RoboParameters\Reader\ParameterReaderInterface;
use NordCode\RoboParameters\Test\BaseTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

abstract class ReaderTestCase extends BaseTestCase
{

    /**
     * @var ParameterReaderInterface
     */
    protected $fixture;

    protected function setUp()
    {
        parent::setUp();
        $this->fixture = $this->getFixture();
    }

    /**
     * @test
     */
    public function testReadFromFile()
    {
        $vfsRoot = vfsStream::setup('root');
        $testFile = new vfsStreamFile('test');
        $testFile->setContent($this->getTestFileContent());
        $vfsRoot->addChild($testFile);

        $this->assertEquals($this->getExpectedResultArray(), $this->fixture->readFromFile($testFile->url()));
    }

    /**
     * @return ParameterReaderInterface
     */
    abstract protected function getFixture();

    /**
     * @return string
     */
    abstract protected function getTestFileContent();

    /**
     * @return array
     */
    abstract protected function getExpectedResultArray();
}
