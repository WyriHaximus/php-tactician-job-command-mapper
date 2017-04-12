<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Tactician\JobCommand;

use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\App\Commands\AwesomesauceCommand;
use WyriHaximus\Tactician\JobCommand\Mapper;

class MapperTest extends TestCase
{
    public function testMap()
    {
        $mapper = new Mapper();
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR;
        $namespace = 'Test\App\Commands';
        $mapper->map($path, $namespace);

        self::assertFalse($mapper->hasCommand('sauce'));
        self::assertTrue($mapper->hasCommand('awesomesauce'));
        self::assertSame(
            AwesomesauceCommand::class,
            $mapper->getCommand('awesomesauce')
        );
    }

    public function commandsProvider()
    {
        yield [
            AwesomesauceCommand::class,
            'awesomesauce',
        ];

        yield [
            stdClass::class,
            '',
        ];
    }

    /**
     * @param string $command
     * @param string $job
     * @dataProvider commandsProvider
     */
    public function testGetJobFromCommand(string $command, string $job)
    {
        $result = (new Mapper())->getJobFromCommand($command, new AnnotationReader());
        $this->assertSame($job, $result);
    }

    public function testGetCommandFailure()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('No command known for job: void');

        (new Mapper())->getCommand('void');
    }
}
