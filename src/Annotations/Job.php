<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\JobCommand\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Job
{
    /**
     * @var string
     */
    private $job;

    /**
     * @param array $jobs
     */
    public function __construct(array $jobs)
    {
        $this->job = current($jobs);
    }

    /**
     * @return string
     */
    public function getJob(): string
    {
        return $this->job;
    }
}
