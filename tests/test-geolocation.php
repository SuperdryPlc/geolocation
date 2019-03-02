<?php

namespace superdry\geolocation\tests;

use PHPUnit\Framework\TestCase;

use superdry\geolocation\Geolocation;

class GeolocationTest extends TestCase
{
    private $api;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->api = new Geolocation();
    }

    public function testGettingLatitudeAndLongitudeFromAddress()
    {
        $street = 'Test Street';
        $streetNumber = '1';
        $city = 'London';
        $zip = '12345';
        $country = 'GBR';

        $result = $this->api->getCoordinates(
            $street,
            $streetNumber,
            $city,
            $zip,
            $country
        );

        $this->assertEquals(51.037249600000003, $result['latitude']);
        $this->assertEquals(3.7094974999999999, $result['longitude']);
    }

    public function testGetAddressFromLatitudeAndLongitude()
    {
        $latitude = 51.0363935;
        $longitude = 3.7121008;

        $result = $this->api->getAddress(
            $latitude,
            $longitude
        );

        $this->assertEquals('Test Address', $result['label']);
        $this->assertEquals('array', gettype($result['components']));
    }
}
