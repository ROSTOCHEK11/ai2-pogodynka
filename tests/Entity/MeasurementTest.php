<?php

namespace App\Tests\Entity;

use App\Entity\WeatherData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new WeatherData();

//        $measurement->setTemperature('0');
//        $this->assertEquals(32, $measurement->getFahrenheit());
//        $measurement->setTemperature('-100');
//        $this->assertEquals(-148, $measurement->getFahrenheit());
//        $measurement->setTemperature('100');
//        $this->assertEquals(212, $measurement->getFahrenheit());

        $measurement->setTemperature($celsius);
        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit(), "Expected $expectedFahrenheit Fahrenheit for $celsius Celsius, got {$measurement->getFahrenheit()}");

    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['0.5', '32.9'],
            ['7.2', '44.96'],
            ['10.3', '50.54'],
            ['-17.5', '0.5'],
            ['17.5', '63.5'],
            ['-100', '-148'],
            ['-100.1', '-148.18'],
            ['100', '212'],
            ['100.1', '212.18'],
        ];
    }


}
