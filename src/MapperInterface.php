<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\JobCommand;

interface MapperInterface
{
    public function map(string $path): Mapper;

    public function getJobsFromCommand(string $command): array;

    public function hasCommand(string $job): bool;

    public function getCommand(string $job): string;
}
