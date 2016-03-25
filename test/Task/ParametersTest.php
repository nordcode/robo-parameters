<?php

namespace NordCode\RoboParameters\Test\Task;

use NordCode\RoboParameters\Reader\ParameterReaderInterface;
use NordCode\RoboParameters\Reader\ReaderRegistry;
use NordCode\RoboParameters\Serializer\ParameterSerializerInterface;
use NordCode\RoboParameters\Serializer\SerializerRegistry;
use NordCode\RoboParameters\Task\Parameters;
use NordCode\RoboParameters\Test\BaseTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;

class ParametersTest extends BaseTestCase
{

    const PARAMETERS_FILE = 'parameters.yml';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Parameters
     */
    private $task;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ParameterReaderInterface
     */
    private $reader;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ParameterSerializerInterface
     */
    private $serializer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ReaderRegistry
     */
    private $readerRegistry;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SerializerRegistry
     */
    private $serializerRegistry;

    /**
     * @var vfsStreamDirectory
     */
    private $dir;

    protected function setUp()
    {
        parent::setUp();

        $this->dir = vfsStream::setup('root');
        $this->serializer = $this->getMockForAbstractClass(ParameterSerializerInterface::class);
        $this->reader = $this->getMockForAbstractClass(ParameterReaderInterface::class);

        $this->readerRegistry = $this->getMock(ReaderRegistry::class, array('getInstanceForFormat'));
        $this->readerRegistry->method('getInstanceForFormat')->willReturn($this->reader);

        $this->serializerRegistry = $this->getMock(SerializerRegistry::class, array('getInstanceForFormat'));
        $this->serializerRegistry->method('getInstanceForFormat')->willReturn($this->serializer);

        $this->task = new Parameters(vfsStream::url('root/' . self::PARAMETERS_FILE));
        $this->task
            ->setSerializerRegistry($this->serializerRegistry)
            ->setReaderRegistry($this->readerRegistry);
    }

    /**
     * @test
     * @expectedException \Robo\Exception\TaskException
     */
    public function writingFailsIfFileExistsAndOverrideIsNotSet()
    {
        $this->dir->addChild(new vfsStreamFile(self::PARAMETERS_FILE));
        $this->task->run();
    }

    /**
     * @test
     */
    public function testOverrideExisting()
    {
        $this->dir->addChild(new vfsStreamFile(self::PARAMETERS_FILE));
        $this->serializer->expects($this->once())->method('serialize');
        $this->task->overrideExisting();
        $this->task->run();
    }

    /**
     * @test
     */
    public function testSettingParameters()
    {
        $this->task->set(array('foo' => 'bar'));
        $this->expectOneSerializerCall(array('foo' => 'bar'));
        $this->task->run();
    }

    /**
     * @test
     */
    public function testReadingFromBoilerplate()
    {
        $boilerplate = new vfsStreamFile('boilerplate.yml');
        $this->dir->addChild($boilerplate);
        $this->task->useBoilerplate($boilerplate->url());
        $this->reader->expects($this->once())->method('readFromFile')->willReturn(array('foo' => 'bar'));

        $this->expectOneSerializerCall(array('foo' => 'bar'));

        $this->task->run();
    }

    /**
     * @test
     * @expectedException \Robo\Exception\TaskException
     */
    public function readingFromBoilerplateFailsOnMissingFile()
    {
        $this->task->useBoilerplate('nope');
        $this->task->run();
    }

    /**
     * @test
     */
    public function testReadingFromEnvironmentVariables()
    {
        putenv('ROBO_ABCDEFGHI=baz');
        $this->task->loadFromEnvironment(array('abcdefghi'));
        $this->task->envVariablePrefix('robo');

        $this->expectOneSerializerCall(array('abcdefghi' => 'baz'));

        $this->task->run();
    }

    /**
     * @test
     * @expectedException \Robo\Exception\TaskException
     */
    public function taskFailsOnMissingEnvironmentVariables()
    {
        $this->task->failOnMissingEnvVariables();
        $this->task->loadFromEnvironment(array('-foo'));
        $this->task->run();
    }

    /**
     * @test
     */
    public function taskFailsOnWritingFailure()
    {
        $this->dir->chmod(0);
        $result = $this->task->run();
        $this->assertFalse($result->wasSuccessful());
    }

    /**
     * @test
     */
    public function testWritingFileHeader()
    {
        $this->task->fileHeader(array(
            "This is the header"
        ));
        $this->expectOneSerializerCall(array(), "This is the header");
        $this->task->run();
    }

    /**
     * @param array $withParameters
     * @param string $withHeader
     */
    private function expectOneSerializerCall(array $withParameters, $withHeader = null)
    {
        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($withParameters, $withHeader);
    }
}
