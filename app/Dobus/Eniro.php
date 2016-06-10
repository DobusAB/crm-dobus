<?php

namespace App\Dobus;

class Eniro {
    private $profile;
    private $key;
    private $version;
    private $country;
    private $city;
    private $query;
    private $from;
    private $to;

    public function __construct($profile, $key, $version, $country, $city, $query, $from, $to)
    {
        $this->baseUrl = 'http://api.eniro.com/cs/proximity/basic?';
        $this->profile = $profile;
        $this->key = $key;
        $this->version = $version;
        $this->country = $country;
        $this->city = $city;
        $this->query = $query;
        $this->from = $from;
        $this->to = $to;
    }

    public function companies()
    {
        $response = json_decode(file_get_contents(
            $this->baseUrl .
            'profile=' . $this->profile .
            '&key=' . $this->key .
            '&country=' . $this->country .
            '&version=' . $this->version .
            '&search_word=' . $this->query .
            '&geo_area=' . $this->city .
            '&from_list=' . $this->from .
            '&to_list=' . $this->to
            ), true);

        return $response;
    }
}
