<?php declare(strict_types=1);

namespace Tests\Annotations;

use WyriHaximus\Tactician\JobCommand\Annotations\Job;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{
    public function provideJobs()
    {
        yield [
            [
                'job',
            ],
            'job',
        ];

        yield [
            [
                'jobA',
                'jobB',
            ],
            'jobA',
        ];
    }

    /**
     * @param array $jobs
     * @param string $expectedJob
     * @dataProvider provideJobs
     */
    public function testGetJob(array $jobs, string $expectedJob)
    {
        $this->assertSame($expectedJob, (new Job($jobs))->getJob());
    }
}
