<?php
declare(strict_types=1);
namespace UnitTest\Interval\Operation\Interval;
use Interval\Interval;
use Interval\Operation\Interval\Intersection;
use Interval\Operation\Interval\Union;
use \Mockery as m;
class IntersectionTest extends \PHPUnit\Framework\TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function computeProvider()
    {
        return [
            [
                10, 20, //                                    ██████████████████
                30, 40, //                                                          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                null, //                                      no interval
            ],
            [
                10, 20, //                                    ██████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                null, //                                      empty interval
            ],
            [
                10, 30, //                                    ███████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [20, 30], //                                                    ▓▓▓▓▓
            ],
            [
                10, 60, //                                    █████████████████████████████████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [20, 40], //                                                    ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 40, //                                    ███████████████████
                10, 40, //                                    ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [10, 40] , //                                 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, //                                    ██████████████████
                10, 20, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                null, //                  no interval
            ],
            [
                30, 40, //                                    ██████████████████
                10, 30, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                null,   //                empty interval
            ],
            [
                30, 40, //                                    ██████████████████
                10, 35, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [30, 35], //                                  ▓▓▓▓
            ],
            [
                30, 40, //                                    ██████████████████
                10, 60, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [30, 40], //                                  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ]
        ];
    }

    /**
     * @dataProvider computeProvider
     * @param $firstStart
     * @param $firstEnd
     * @param $secondStart
     * @param $secondEnd
     * @test
     */
    public function compute($firstStart, $firstEnd, $secondStart, $secondEnd, $expected)
    {

        $first = m::mock('\Interval\Interval');
        $first->shouldReceive('getComparableStart')->andReturn($firstStart);
        $first->shouldReceive('getStart')->andReturn($firstStart);
        $first->shouldReceive('getComparableEnd')->andReturn($firstEnd);
        $first->shouldReceive('getEnd')->andReturn($firstEnd);

        $second = m::mock('\Interval\Interval');
        $second->shouldReceive('getComparableStart')->andReturn($secondStart);
        $second->shouldReceive('getStart')->andReturn($secondStart);
        $second->shouldReceive('getComparableEnd')->andReturn($secondEnd);
        $second->shouldReceive('getEnd')->andReturn($secondEnd);

        $union = new Intersection();
        $interval = $union->compute($first, $second);
        if (is_null($expected)) {
            $this->assertNull($interval);
        } else {
            $this->assertInstanceOf(\Interval\Interval::class, $interval);
            $this->assertSame($expected[0], $interval->getStart());
            $this->assertSame($expected[1], $interval->getEnd());
        }
    }
}