<?php

namespace App\Dobus;

use Goutte;

use App\Dobus\ValidatesEmail;

/**
* Gets contact person email for a company
*/
class GetsEmail
{
	private function getHomepageUrl($item)
    {
        $crawler = Goutte::request('GET', $item);
        $url = $crawler->filter('meta')->first()->attr('content');
        // $url = $item;

        $this->url = str_replace('0;url=', '', $url);
        $this->url = str_replace('https://', 'http://', $this->url);
        $this->url = str_replace('http://', '', $this->url);
        $this->url = str_replace('www.', '', $this->url);

        return $this->url;
    }

    public function withEmail($request, $companyName)
    {
        $url = $this->getHomepageUrl($request->homepage);
        $city = $request->city;

        $validator = new ValidatesEmail();
        
        return $validator->findValidEmailFrom($url, $companyName, $city); // Här ska även city och company_name samt homepage skickas in för att hämta dynamisk data från allabolags API
    }
}