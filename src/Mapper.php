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

            $job = self::getJobFromCommand($class, $reader);

            if (!strlen($job) === 0) {
                continue;
            }

            $this->map[$job] = $class;
        }

        return $this;
    }

    /**
     * @param string $command
     * @param Reader $reader
     * @return string
     */
    public function getJobFromCommand(string $command, Reader $reader)
    {
        $annotation = $reader->getClassAnnotation(new ReflectionClass($command), Job::class);

        if (!($annotation instanceof Job)) {
            return '';
        }

        return $annotation->getJob();
    }

    /**
     * @param string $job
     * @return bool
     */
    public function hasCommand(string $job)
    {
        return isset($this->map[$job]);
    }

    /**
     * @param string $job
     * @return mixed
     * @throws Exception
     */
    public function getCommand(string $job)
    {
        if (isset($this->map[$job])) {
            return $this->map[$job];
        }

        throw new Exception('No command known for job: ' . $job);
    }
}
