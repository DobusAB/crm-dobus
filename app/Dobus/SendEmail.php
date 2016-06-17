<?php 
namespace App\Dobus;
use Newsletter;


class SendEmail {

	public function getUsers()
	{

	}

	public function send()
	{

		Newsletter::subscribe('christopher@dobus.se', ['FNAME'=>'Albin', 'LNAME'=>'Martinsson']);
		
        return "true";
	}


}
