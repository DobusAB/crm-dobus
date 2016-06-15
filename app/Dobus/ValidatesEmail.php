<?php

namespace App\Dobus;

/**
* Validates email with Neverbounce API
*/
class ValidatesEmail
{
	private $token;

	public function getToken()
	{
		$ch = curl_init();                    // initiate curl
		$url = "https://api.neverbounce.com/v3/access_token"; // where you want to post data
		$params = [
			'grant_type' => 'client_credentials', 
			'scope' => 'basic user', 
			'client_id' => 'ZSpl6nmK', 
			'client_secret' => 'YM#s!CVOjf91Rbi'
		];

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, true);  // tell curl you want to post something
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format

		$output = curl_exec ($ch); // execute

		curl_close ($ch); // close curl handle

		$this->token = $output;

		return $output;
	}

	public function findValidEmailFrom($url)
	{
		// return $this->getToken();
		return 'christopher.' . $url;
	}
}