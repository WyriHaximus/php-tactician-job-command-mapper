<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\JobCommand;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Exception;
use ReflectionClass;
use function WyriHaximus\listClassesInDirectory;
use WyriHaximus\Tactician\JobCommand\Annotations\Job;

final class Mapper implements MapperInterface
{
    /**
     * @var array
     */
    private $map = [];

    /** @var Reader */
    private $reader;

    /**
     * @param Reader|null $reader
     */
    public function __construct(?Reader $reader = null)
    {
        $this->reader = $reader ?? new AnnotationReader();
    }

    /**
     * @param  string $path
     * @return Mapper
     */
    public function map(string $path): Mapper
    {
        foreach (listClassesInDirectory($path) as $class) {
            $jobs = self::getJobsFromCommand($class);

            if (\count($jobs) === 0) {
                continue;
            }

            foreach ($jobs as $job) {
                $this->map[$job] = $class;
            }
        }

        return $this;
    }

    /**
     * @param  string $command
     * @param  Reader $reader
     * @return array
     */
    public function getJobsFromCommand(string $command): array
    {
        $annotation = $this->reader->getClassAnnotation(new ReflectionClass($command), Job::class);

        if (!($annotation instanceof Job)) {
            return [];
        }

        return $annotation->getJobs();
    }

    /**
     * @param  string $job
     * @return bool
     */
    public function hasCommand(string $job): bool
    {
        return isset($this->map[$job]);
    }

    /**
     * @param  string    $job
     * @throws Exception
     * @return string
     */
    public function getCommand(string $job): string
    {
        if (isset($this->map[$job])) {
            return $this->map[$job];
        }

        throw new Exception('No command known for job: ' . $job);
    }
}
