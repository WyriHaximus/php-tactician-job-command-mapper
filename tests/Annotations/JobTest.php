<?php declare(strict_types=1);

namespace Tests\Annotations;

use WyriHaximus\Tactician\JobCommand\Annotations\Job;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{
    public function provideJobs()
    {
        yield [
            [],
            [],
        ];

        yield [
            [
                'value' => 'job',
            ],
            [
                'job',
            ],
        ];

        yield [
            [
                'value' => [
                    'jobA',
                    'jobB',
                ],
            ],
            [
                'jobA',
                'jobB',
            ],
        ];
    }

    /**
     * @param string[] $jobs
     * @param string[] $expectedJob
     * @dataProvider provideJobs
     */
    public function testGetJob(array $jobs, array $expectedJob)
    {
        $this->assertSame($expectedJob, (new Job($jobs))->getJobs());
    }
}
