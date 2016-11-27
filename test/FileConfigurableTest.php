<?php

namespace NordCode\RoboParameters\Test;

use NordCode\RoboParameters\FileConfigurable;
use NordCode\RoboParameters\Reader\ParameterReaderInterface;
use NordCode\RoboParameters\Reader\ReaderRegistry;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

class FileConfigurableTest extends BaseTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ReaderRegistry
     */
    private $readerRegistry;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ParameterReaderInterface
     */
    private $reader;

    /**
     * @var FileConfigurable
     */
    private $fixture;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->reader = $this->getMockForAbstractClass(ParameterReaderInterface::class);

        $this->readerRegistry = $this->getMock(ReaderRegistry::class, array('getInstanceForFormat'));
        $this->readerRegistry->method('getInstanceForFormat')->willReturn($this->reader);
        $this->fixture = $this->getMockForTrait(FileConfigurable::class);
        $this->callProtectedMethod($this->fixture, 'setReaderRegistry', [$this->readerRegistry]);
    }

    /**
     * @test
     */
    public function testPropertyOverriding()
    {
        $this->reader->method('readFromFile')->willReturnOnConsecutiveCalls(
            ['level1' => ['property1' => 'value']],
            ['level1' => ['property2' => 'value']]
        );

        // vfs is required because there are some nasty file_exists checks in FileReader::readFromFile()
        $vfsRoot = vfsStream::setup('root');
        $cfg1 = (new vfsStreamFile('config.dist.yml'))->at($vfsRoot);
        $cfg2 = (new vfsStreamFile('config.yml'))->at($vfsRoot);

        $this->callProtectedMethod($this->fixture, 'loadConfiguration', [$cfg1->url()]);
        $this->callProtectedMethod($this->fixture, 'loadConfiguration', [$cfg2->url()]);
        $this->assertEquals(
            ['property2' => 'value'],
            $this->callProtectedMethod($this->fixture, 'get', ['level1'])
        );
    }
}
