<?php
declare(strict_types=1);
namespace Interval\Operation\Intervals;
use Interval\Interval;
use Interval\Intervals;

/**
 * Class Exclusion
 * @package Interval\Operation\Interval
 */
class Exclusion
{
    /**
     * PHP magic function
     * @param Intervals $first
     * @param Intervals $second
     * @return array
     */
    public function __invoke(Intervals $first, Intervals $second)
    {
        return $this->compute($first, $second);
    }

    /**
     * Excludes an interval from another one. Exp
     *
     *      |_________________|
     *
     *             -
     *                  |_________________|
     *
     *          =
     *      |___________|
     *
     * @param Intervals $firstInterval
     * @param Intervals $secondInterval
     * @return Intervals
     */
    public function compute(Intervals $firstInterval, Intervals $secondInterval)
    {
        $first = $firstInterval->getArrayCopy();
        $second = $secondInterval->getArrayCopy();
        if (0 === count($second)) {
            return $firstInterval;
        }

        $countIntervalsToExclude = count($second);
        while ($countIntervalsToExclude > 0) {
            $intervalToExclude = array_shift($second);

            $newIntervals = [];

            /** @var Interval $interval */
            foreach ($first as $interval) {
                $newIntervals = array_merge($newIntervals, $interval->exclude($intervalToExclude)->getArrayCopy());
            }

            $first                   = $newIntervals;
            $countIntervalsToExclude = count($second);
        }

        return new Intervals($first);
    }
}