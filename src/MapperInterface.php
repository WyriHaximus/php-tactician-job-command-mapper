<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\JobCommand;

use Doctrine\Common\Annotations\Reader;

interface MapperInterface
{
    public function map(string $path, string $namespace): Mapper;

    public function getJobsFromCommand(string $command, Reader $reader): array;

    public function hasCommand(string $job): bool;

    public function getCommand(string $job): string;
}
