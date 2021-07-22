<?php

use PHPUnit\Framework\TestCase;
use Springy\BusinessDaysCalculator;

class BusinessDaysTest extends TestCase
{
    protected const DEFAULT_DATE = '2020-12-04';

    public function testGetDate()
    {
        $date = new DateTime(self::DEFAULT_DATE);
        $bdCalc = new BusinessDaysCalculator(
            new DateTime(self::DEFAULT_DATE)
        );

        $this->assertEquals($bdCalc->getDate(), $date);
    }

    public function testGetHolidays()
    {
        $bdCalc = new BusinessDaysCalculator(
            new DateTime(self::DEFAULT_DATE)
        );

        $this->assertEquals($bdCalc->getHolidays(), []);
    }

    public function testAddBrazilianHolidays()
    {
        $holidays = [
            new DateTime('2021-01-01'),
            new DateTime('2021-02-15'),
            new DateTime('2021-02-16'),
            new DateTime('2021-04-02'),
            new DateTime('2021-04-04'),
            new DateTime('2021-04-21'),
            new DateTime('2021-05-01'),
            new DateTime('2021-06-03'),
            new DateTime('2021-09-07'),
            new DateTime('2021-10-12'),
            new DateTime('2021-11-02'),
            new DateTime('2021-11-15'),
            new DateTime('2021-12-25'),
        ];

        $bdCalc = new BusinessDaysCalculator(
            new DateTime(self::DEFAULT_DATE)
        );
        $bdCalc->addBrazilianHolidays(2021);

        $this->assertEquals($bdCalc->getHolidays(), $holidays);
    }

    public function testAddBusinessDays()
    {
        $wdate = new DateTime('2020-12-24');
        $rdate = new DateTime('2021-01-04');
        $bdCalc = new BusinessDaysCalculator(
            new DateTime(self::DEFAULT_DATE)
        );
        $bdCalc->addBrazilianHolidays(2021);
        $bdCalc->addBusinessDays(20);

        $this->assertLessThan($bdCalc->getDate(), $wdate);
        $this->assertEquals($bdCalc->getDate(), $rdate);
    }

    public function testSetDate()
    {
        $date = new DateTime('2020-12-03');
        $bdCalc = new BusinessDaysCalculator();

        $this->assertLessThan($bdCalc->getDate(), $date);

        $bdCalc->setDate(new DateTime('2020-12-03'));
        $this->assertEquals($bdCalc->getDate(), $date);
    }

    public function testIsBusinessDay()
    {
        $bdCalc = new BusinessDaysCalculator();
        $bdCalc->addBrazilianHolidays(2020);

        $bdCalc->setDate(new DateTime(self::DEFAULT_DATE));
        $this->assertTrue($bdCalc->isBusinessDay());

        $bdCalc->setDate(new DateTime('2020-12-25'));
        $this->assertFalse($bdCalc->isBusinessDay());
    }

    public function testStaticGetBusinessDate()
    {
        $date = new DateTime();
        $bdCalc = new BusinessDaysCalculator();
        $bdCalc->addBrazilianHolidays(
            (int) $date->format('Y')
        )->addBrazilianHolidays(
            (int) $date->format('Y') + 1
        )->addBusinessDays(10);

        $this->assertEquals(
            $bdCalc->getDate()->format('Y-m-d'),
            BusinessDaysCalculator::getBusinessDate(10)
        );
    }
}
