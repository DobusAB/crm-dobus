<?php

namespace App\Dobus;

use App\Lead;

use App\Dobus\Status;

use Goutte;

class ManagesLeads {
    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function all()
    {
        return Lead::all();
    }

    public function create($lead)
    {
        $lead = $lead->all();
        $lead['status'] = $this->status->leadCreated();

        if ($lead['corporate_identity_number'] == null) {
            $lead['corporate_identity_number'] = '';
        }

        if ($lead['homepage'] == null) {
            $lead['email'] = null;

            if (!$this->leadExists($lead)) {
                return Lead::create($lead);
            }
        }

        // $lead['email'] = $this->getEmailFromHompage($lead['homepage']);

        if (!$this->leadExists($lead)) {
            return Lead::create($lead);
        }
    }

    private function leadExists($lead)
    {
        $lead = Lead::where('corporate_identity_number', $lead['corporate_identity_number'])->exists();
        // if ($lead['corporate_identity_number'] == '') {
        //     return false;
        // }

        if (!$lead) {
            return false;
        }

        return true;
    }

    private function getEmailFromHompage($homepage)
    {
        $this->homepage = $homepage;
         
        return $this->homepage()->findEmail()->get();
    }

    private function get()
    {
        if (!$this->emailFound) {
            $this->email = null;
        }
        return $this->email;
    }

    private function findEmail()
    {
        $crawler = Goutte::request('GET', $this->url);
        $count = $crawler->filter('a[href^="mailto:"]')->count();

        if ($count < 1) {
            $this->emailFound = false;
            return $this;
        } else {
            $this->emailFound = true;
        }

        $email = $crawler->filter('a[href^="mailto:"]')->first()->attr('href');
        $this->email = str_replace('mailto:', '', $email);

        return $this;
    }

    private function homepage()
    {
        $crawler = Goutte::request('GET', $this->homepage);
        $url = $crawler->filter('meta')->first()->attr('content');
        $this->url = str_replace('0;url=', '', $url);

        return $this;
    }
}
