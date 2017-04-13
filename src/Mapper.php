<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\JobCommand;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use WyriHaximus\Tactician\JobCommand\Annotations\Job;

final class Mapper
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @param string $path
     * @param string $namespace
     * @return Mapper
     */
    public function map(string $path, string $namespace): Mapper
    {
        $reader = new AnnotationReader();

        $directory = new RecursiveDirectoryIterator($path);
        $directory = new RecursiveIteratorIterator($directory);

        foreach ($directory as $node) {
            if (!is_file($node->getPathname())) {
                continue;
            }

            $file = substr($node->getPathname(), strlen($path));
            $file = ltrim($file, DIRECTORY_SEPARATOR);
            $file = rtrim($file, '.php');

            $class = $namespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $file);

            if (!class_exists($class)) {
                continue;
            }

            $jobs = self::getJobsFromCommand($class, $reader);

            if (count($jobs) === 0) {
                continue;
            }

            foreach ($jobs as $job) {
                $this->map[$job] = $class;
            }
        }

        return $this;
    }

    /**
     * @param string $command
     * @param Reader $reader
     * @return array
     */
    public function getJobsFromCommand(string $command, Reader $reader): array
    {
        $annotation = $reader->getClassAnnotation(new ReflectionClass($command), Job::class);

        if (!($annotation instanceof Job)) {
            return [];
        }

        return $annotation->getJobs();
    }

    /**
     * @param string $job
     * @return bool
     */
    public function hasCommand(string $job): bool
    {
        return isset($this->map[$job]);
    }

    /**
     * @param string $job
     * @return string
     * @throws Exception
     */
    public function getCommand(string $job): string
    {
        if (isset($this->map[$job])) {
            return $this->map[$job];
        }

        throw new Exception('No command known for job: ' . $job);
    }
}
