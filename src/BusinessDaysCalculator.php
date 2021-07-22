<?php

/**
 * Business days date calculator.
 *
 * @copyright 2020 Fernando Val
 * @author    Fernando Val <fernando.val@gmail.com>
 * @license   https://github.com/springy-framework/business-days-calculator/blob/main/LICENSE MIT
 *
 * @version   1.0.0
 */

namespace Springy;

use DateTime;

/**
 * Business days date calculator class.
 */
class BusinessDaysCalculator
{
    /** @var DateTime */
    private $date = null;
    /** @var array */
    private $daysOff = [];
    /** @var array */
    private $holidays = [];

    // Week day constants
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;
    public const SUNDAY = 7;

    /**
     * Constructor.
     *
     * @param DateTime $startDate Date to start calculations from.
     * @param DateTime $holidays  Array of holidays, holidays are no conisdered business days.
     * @param int      $daysOff   Array of days of the week which are not business days.
     */
    public function __construct(
        DateTime $date = null,
        array $holidays = [],
        array $daysOff = [self::SATURDAY, self::SUNDAY]
    ) {
        $this->date = $date ?? new DateTime();
        $this->holidays = $holidays;
        $this->daysOff = $daysOff;
    }

    /**
     * Returns the brazilian holidays, including Carnival, Passion, Easter and Corpus Christi.
     *
     * @param int $year
     *
     * @return array
     */
    private function brazilianHolidays(int $year): array
    {
        $fixHolidays = [
            1  => [1],
            2  => [],
            3  => [],
            4  => [21],
            5  => [1],
            6  => [],
            7  => [],
            8  => [],
            9  => [7],
            10 => [12],
            11 => [2, 15],
            12 => [25],
        ];

        $brHolidays = [
            $this->carnivalDate((int) $year)->modify('-1 day'),     // Carnival (monday)
            $this->carnivalDate((int) $year),                       // Carnival
            $this->easterDate((int) $year)->modify('-2 days'),      // Passion friday
            $this->easterDate((int) $year),                         // Easter
            $this->corpusChristDate((int) $year),                   // Corpus Christi
        ];

        foreach ($fixHolidays as $month => $days) {
            foreach ($days as $day) {
                $brHolidays[] = new DateTime('@' . mktime(0, 0, 0, $month, $day, $year));
            }
        }

        asort($brHolidays);

        return $brHolidays;
    }

    /**
     * Returns the carnival date for the given year.
     *
     * @param int $year
     *
     * @return DateTime
     */
    private function carnivalDate(int $year): DateTime
    {
        $cdate = $this->easterDate($year);
        $cdate->modify('-47 days');

        return $cdate;
    }

    /**
     * Returns the Corpus Christi date for the given year.
     *
     * @param int $year
     *
     * @return DateTime
     */
    private function corpusChristDate(int $year): DateTime
    {
        $date = $this->easterDate($year);
        $date->modify('+60 days');

        return $date;
    }

    /**
     * Returns the easter date for the given year.
     *
     * @param int $year
     *
     * @return DateTime
     */
    private function easterDate(int $year): DateTime
    {
        $date = new DateTime('@' . mktime(0, 0, 0, 3, 21, $year));
        $date->modify('+' . easter_days($year) . ' days');

        return $date;
    }

    /**
     * Adds brasilian holidays to the holidays array.
     *
     * @param int|null $year
     *
     * @return self
     */
    public function addBrazilianHolidays(int $year): self
    {
        $this->holidays = array_merge($this->holidays, $this->brazilianHolidays($year));

        return $this;
    }

    /**
     * Adds business day to current date.
     *
     * @param int $businessDays number of business days to add.
     *
     * @return DateTime the new date.
     */
    public function addBusinessDays(int $businessDays): DateTime
    {
        $iterator = 0;

        while ($iterator < $businessDays) {
            $this->date->modify('+1 day');

            if ($this->isBusinessDay()) {
                $iterator += 1;
            }
        }

        return $this->getDate();
    }

    /**
     * Gets the current date.
     *
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Gets the holidays array.
     *
     * @return array an array of DateTime
     */
    public function getHolidays(): array
    {
        return $this->holidays;
    }

    /**
     * Sets new date.
     *
     * @param DateTime $date
     *
     * @return self
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Checks whether the current date is a business day.
     *
     * @return bool
     */
    public function isBusinessDay(): bool
    {
        // Check if is a day off.
        if (in_array((int) $this->date->format('N'), $this->daysOff)) {
            return false;
        }

        // Check if is a holliday
        foreach ($this->holidays as $day) {
            if ($this->date->format('Y-m-d') == $day->format('Y-m-d')) {
                return false;
            }
        }

        // Date is a business day.
        return true;
    }

    /**
     * Computes the date using given business days.
     *
     * @param int $days
     *
     * @return string Returns a date in Y-m-d format.
     */
    public static function getBusinessDate($days): string
    {
        $businessDays = new self();

        $year = (int) $businessDays->getDate()->format('Y');

        return $businessDays
            ->addBrazilianHolidays($year)
            ->addBrazilianHolidays($year + 1)
            ->addBusinessDays($days)
            ->format('Y-m-d');
    }
}
