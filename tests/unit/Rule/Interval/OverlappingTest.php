<?php
declare(strict_types=1);

namespace Interval\Rule\Interval;

use Interval\Interval;
use Mockery as m;

class OverlappingTest extends \PHPUnit\Framework\TestCase
{
    public function assertProvider()
    {
        return [
            [
                10, 20, //                                    ██████████████████
                30, 40, //                                                          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                10, 20, //                                    ██████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                10, 30, //                                    ███████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                10, 30, //                                    ███████████████████████
                20, 30, //                                                      ▒▒▒▒▒
                true,
            ],
            [
                10, 60, //                                    █████████████████████████████████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                10, 40, //                                    ███████████████████
                10, 40, //                                    ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 20, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 30, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 35, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                30, 40, //                                    ██████████████████
                30, 35, //                                    ▒▒▒▒
                true,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 60, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider assertProvider
     * @param mixed $firstStart
     * @param mixed $firstEnd
     * @param mixed $secondStart
     * @param mixed $secondEnd
     * @param mixed $expected
     */
    public function assert($firstStart, $firstEnd, $secondStart, $secondEnd, $expected)
    {
        $union  = new Overlapping();
        $result = $union->assert(new Interval($firstStart, $firstEnd), new Interval($secondStart, $secondEnd));
        $this->assertInternalType('bool', $result);
        $this->assertSame($expected, $result);
    }

    public function tearDown()
    {
        m::close();
    }
}
