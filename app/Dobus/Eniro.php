<?php

namespace App\Dobus;

use Goutte;

use App\Dobus\ValidatesEmail;

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

    public function getCompanies()
    {
        return $this->getAllCompanies()->withHomepage();
    }

    private function getAllCompanies()
    {
        $this->response = json_decode(file_get_contents(
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

        return $this;
    }

    private function withHomepage()
    {
        $this->results = [];

        foreach ($this->response['adverts'] as $item) {
            if ($item['homepage'] != null) {
                $item['email'] = $this->getHomepageUrl('https://www.hemsida24.se')->withEmail();

                if ($item['email'] != null) {
                    array_push($this->results, $item);
                }
            }
        }

        return $this->results;
    }

    private function getHomepageUrl($item)
    {
        // $crawler = Goutte::request('GET', $item);
        // $url = $crawler->filter('meta')->first()->attr('content');
        $url = $item;

        $this->url = str_replace('0;url=', '', $url);
        $this->url = str_replace('https://', 'http://', $this->url);
        $this->url = str_replace('http://', '', $this->url);
        $this->url = str_replace('www.', '', $this->url);

        return $this;
    }

    private function withEmail()
    {
        $validator = new ValidatesEmail();
        
        return $validator->findValidEmailFrom($this->url); // Här ska även city och company_name skickas in för att hämta dynamisk data från allabolags API
    }

    // public function findValidEmailFrom($url)
    // {
    //     $validator = new ValidatesEmail();

    //     return $validator->findValidEmailFrom($url);
    // }
}
