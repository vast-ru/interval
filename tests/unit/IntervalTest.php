<?php
declare(strict_types=1);

namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';
use Mockery as m;

class IntervalTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function constructorShouldThrowExceptionProvider()
    {
        return [
            [2, 1],
            ['string', 'string'],
        ];
    }

    /**
     * @test
     * @dataProvider constructorShouldThrowExceptionProvider
     * @param mixed $start
     * @param mixed $end
     * @return Interval
     */
    public function constructorShouldThrowException($start, $end)
    {
        $this->expectException(\Exception::class);
        return new \Interval\Interval($start, $end);
    }

    /**
     * @test
     */
    public function union()
    {
        $interval = new \Interval\Interval(1, 2);
        $this->assertEquals(new Intervals([new Interval(1, 3)]), $interval->union(new \Interval\Interval(2, 3)));
    }

    /**
     * @test
     */
    public function intersection()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertEquals(new Interval(3, 4), $interval->intersect(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function exclusion()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertEquals(new Intervals([new Interval(1, 3, false, true)]), $interval->exclude(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function includes()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->includes(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function overlaps()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertTrue($interval->overlaps(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isNeighborBeforeOf()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->isNeighborBefore(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isNeighborAfterOf()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->isNeighborAfter(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isBeforeOf()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->isBefore(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isAfter()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->isAfter(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function starts()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->starts(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function ends()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->ends(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function equals()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertFalse($interval->equals(new \Interval\Interval(3, 5)));
    }

    public function toStringProvider()
    {
        return [
            [1, 2, '[1, 2]'],
            [1.2, 2.2, '[1.2, 2.2]'],
            [new \DateTime('2016-01-01', new \DateTimeZone('UTC')), new \DateTime('2016-01-02', new \DateTimeZone('UTC')), '[2016-01-01T00:00:00+00:00, 2016-01-02T00:00:00+00:00]'],
            [-INF, +INF, ']-INF, +INF['],
            [-INF, 1, ']-INF, 1]'],
            [1, +INF, '[1, +INF['],
            [null, 1, ']-INF, 1]'],
            [1, null, '[1, +INF['],
        ];
    }

    /**
     * @test
     * @dataProvider toStringProvider
     * @param mixed $start
     * @param mixed $end
     * @param mixed $expected
     */
    public function toStringTest($start, $end, $expected)
    {
        $interval = new \Interval\Interval($start, $end);
        $this->assertSame($expected, $interval->__toString());
    }

    public function toComparableProvider()
    {
        return [
            [1, 1],
            [1.1, 1.1],
            ['1', '1'],
            ['a', 'a'],
            [true, true],
            [false, false],
            [new \DateTime('2016-01-01 10:00:00', new \DateTimeZone('UTC')), 1451642400],
            [INF, INF],
            [-INF, -INF],
        ];
    }

    /**
     * @test
     * @dataProvider toComparableProvider
     * @param mixed $boundary
     * @param mixed $expected
     */
    public function toComparable($boundary, $expected)
    {
        $this->assertSame($expected, Interval::toComparable($boundary));
    }

    public function toComparableExceptionProvider()
    {
        return [
            [[]],
            [new \stdClass()],
            [null],
        ];
    }

    /**
     * @test
     * @dataProvider toComparableExceptionProvider
     * @param mixed $boundary
     */
    public function toComparableException($boundary)
    {
        $this->expectException(\UnexpectedValueException::class);
        Interval::toComparable($boundary);
    }

    /**
     * @test
     */
    public function create()
    {
        $interval = Interval::create('[10, 15]');
        $this->assertSame(10, $interval->getStart()->getValue());
        $this->assertSame(15, $interval->getEnd()->getValue());
    }
}
