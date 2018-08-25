<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\JobCommand\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Job
{
    /**
     * @var string[]
     */
    private $jobs = [];

    /**
     * Job constructor.
     * @param string[] $jobs
     */
    public function __construct(array $jobs)
    {
        if (!isset($jobs['value'])) {
            return;
        }

        if (is_string($jobs['value'])) {
            $this->jobs[] = $jobs['value'];

            return;
        }

        $this->jobs = $jobs['value'];
    }

    /**
     * @return array
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }
}
