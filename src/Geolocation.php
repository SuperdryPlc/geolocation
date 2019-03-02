<?php

namespace superdry\geolocation;

/**
 * Geolocation
 */
class Geolocation
{
    // API URL
    const API_URL = 'maps.googleapis.com/maps/api/geocode/json';

    private $api_key;
    private $https = true;

    public function __construct($api_key = null)
    {
        if ($api_key) {
            $this->api_key = $api_key;
            $this->https = true;
        }
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return mixed
     * @throws GeolocationException
     */
    public function getAddress($latitude, $longitude)
    {
        $addressSuggestions = $this->getAddresses($latitude, $longitude);
        return $addressSuggestions[0];
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return array
     * @throws GeolocationException
     */
    public function getAddresses($latitude, $longitude)
    {

        if ($this->api_key) {

            // init results
            $addresses = [];

            // define result
            $addressSuggestions = $this->doCall([
                'latlng' => $latitude . ',' . $longitude,
                'sensor' => 'false'
            ]);

            // loop addresses
            foreach ($addressSuggestions as $key => $addressSuggestion) {
                // init address
                $address = [];

                // define label
                $address['label'] = isset($addressSuggestion->formatted_address) ?
                    $addressSuggestion->formatted_address : null;

                // define address components by looping all address components
                foreach ($addressSuggestion->address_components as $component) {
                    $address['components'][] = [
                        'long_name' => $component->long_name,
                        'short_name' => $component->short_name,
                        'types' => $component->types
                    ];
                }

                $addresses[$key] = $address;
            }

            return $addresses;
        }
        return $this->testAddress();
    }

    /**
     * @param array $parameters
     * @return mixed
     * @throws GeolocationException
     */
    protected function doCall($parameters = [])
    {
        if (!function_exists('curl_init')) {
            throw new GeolocationException("This method requires cURL (http://php.net/curl), it seems like the extension isn't installed.");
        }

        $url = 'https://' . self::API_URL . '?';

        foreach ($parameters as $key => $value) {
            $url .= $key . '=' . urlencode($value) . '&';
        }

        $url = trim($url, '&');

        if ($this->api_key) {
            $url .= '&key=' . $this->api_key;
        }

        // init curl
        $curl = curl_init();

        // set options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }

        // execute
        $response = curl_exec($curl);

        // fetch errors
        $errorNumber = curl_errno($curl);
        $errorMessage = curl_error($curl);

        // close curl
        curl_close($curl);

        if ($errorNumber != '') {
            throw new GeolocationException($errorMessage);
        }

        $response = json_decode($response);
        return $response->results;
    }

    private function testAddress()
    {
        return [
            '0' => [
                'label' => 'Test Address',
                'components' => [
                    'long_name' => '',
                    'short_name' => '',
                    'types' => ''
                ]
            ]
        ];
    }

    /**
     *
     *
     * Get coordinates latitude/longitude
     *
     * @return array  The latitude/longitude coordinates
     * @param  string $street [optional]
     * @param  string $streetNumber [optional]
     * @param  string $city [optional]
     * @param  string $zip [optional]
     * @param  string $country [optional]
     * @return array
     * @throws GeolocationException
     */
    public function getCoordinates(
        $street = null,
        $streetNumber = null,
        $city = null,
        $zip = null,
        $country = null
    ) {
        // init item
        $item = [];

        // add street
        if (!empty($street)) {
            $item[] = $street;
        }

        // add street number
        if (!empty($streetNumber)) {
            $item[] = $streetNumber;
        }

        // add city
        if (!empty($city)) {
            $item[] = $city;
        }

        // add zip
        if (!empty($zip)) {
            $item[] = $zip;
        }

        // add country
        if (!empty($country)) {
            $item[] = $country;
        }

        // define value
        $address = implode(' ', $item);

        // define result
        $results = $this->doCall([
            'address' => $address,
            'sensor' => 'false'
        ]);

        if ($this->api_key) {

            return [
                'latitude' => array_key_exists(0, $results) ? (float)$results[0]->geometry->location->lat : null,
                'longitude' => array_key_exists(0, $results) ? (float)$results[0]->geometry->location->lng : null
            ];

        }
        return $this->testCoordinates();

    }

    private function testCoordinates()
    {
        return [
            'latitude' => '51.037249600000003',
            'longitude' => '3.7094974999999999',
        ];
    }
}

class GeolocationException extends \Exception
{
}
