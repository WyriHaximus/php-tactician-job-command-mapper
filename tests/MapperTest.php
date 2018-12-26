<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Tactician\JobCommand;

use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\App\Commands\AwesomesauceCommand;
use Test\App\Commands\SauceCommand;
use WyriHaximus\Tactician\JobCommand\Mapper;

/**
 * @internal
 */
class MapperTest extends TestCase
{
    public function testMap(): void
    {
        $path = \dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'test-app' . \DIRECTORY_SEPARATOR . 'Commands' . \DIRECTORY_SEPARATOR;
        $map = (new Mapper())->map($path);

        self::assertFalse($map->hasCommand('soya'));
        self::assertFalse($map->hasCommand('beans'));
        self::assertTrue($map->hasCommand('sauce'));
        self::assertTrue($map->hasCommand('awesomesauce'));
        self::assertTrue($map->hasCommand('sauceawesome'));
        self::assertSame(
            AwesomesauceCommand::class,
            $map->getCommand('awesomesauce')
        );
        self::assertSame(
            AwesomesauceCommand::class,
            $map->getCommand('sauceawesome')
        );
    }

    public function commandsProvider()
    {
        yield [
            SauceCommand::class,
            ['sauce'],
        ];

        yield [
            AwesomesauceCommand::class,
            ['awesomesauce', 'sauceawesome'],
        ];

        yield [
            stdClass::class,
            [],
        ];
    }

    /**
     * @param string $command
     * @param array  $jobs
     * @dataProvider commandsProvider
     */
    public function testGetJobFromCommand(string $command, array $jobs): void
    {
        $result = (new Mapper())->getJobsFromCommand($command, new AnnotationReader());
        $this->assertSame($jobs, $result);
    }

    public function testGetCommandFailure(): void
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('No command known for job: void');

        (new Mapper())->getCommand('void');
    }
}
