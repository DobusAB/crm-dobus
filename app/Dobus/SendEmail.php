<?php 
namespace App\Dobus;
use Newsletter;
use App\Lead;

class SendEmail {

	public function getUsers()
	{
		$leads = Lead::all();
		foreach ($leads as $lead) {
			$lead->contact_person = explode(' ', $lead->contact_person);
			// return $lead->industry_id;
			$this->send($lead->email, $lead->contact_person[0], $lead->contact_person[1], $lead->industry_id);
			// return "true";
		}
		
	}

	public function send($email, $firstName, $lastName, $listName)
	{
		// Newsletter::subscribe('christopher@dobus.se', ['FNAME'=>'Albin', 'LNAME'=>'Martinsson']);
		Newsletter::subscribe($email, ['FNAME'=>$firstName, 'LNAME'=>$lastName], $listName);
        // return "true";
	}


}
