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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format

		$output = curl_exec ($ch); // execute

		curl_close ($ch); // close curl handle

		$token = json_decode($output, true);

		return $token['access_token'];
	}

	public function findValidEmailFrom($url, $companyName, $city)
	{
		// return $url;
		$token = $this->getToken();
		$email = $this->validate($url, $companyName, $city, $token);

		return $email;
		/**
		 * x 1. Get a new Neverbounce API token.
		 * 2. Get name of company CEO from Allabolag API.
		 * x 3. Validate possible email addresses until a match is found.
		 * x 4. Return valid email address
		 */
	}

	private function validate($url, $companyName, $city, $token)
	{
		// $allaBolagResponse = json_decode(file_get_contents(
		// 	'http://www.allabolag.se/ws/BIWS/service.php?' . 
		// 	'key=BIWS5da249669cc4a8f114d4929ab768' . 
		// 	'&type=fetch' . 
		// 	'&query=ba_postort:HALMSTAD%20AND%20jurnamn:fristil' . 
		// 	'&fields=orgnr,jurnamn,ba_postort,oms_X,anst_X,mgmt,ba_gatuadress,ba_postnr,ba_lan,riktnrtelnr,bolord'
		// ), true);

		$allaBolagResponse = simplexml_load_file(
			'http://www.allabolag.se/ws/BIWS/service.php?' . 
			'key=BIWS5da249669cc4a8f114d4929ab768' . 
			'&type=fetch' . 
			'&query=ba_postort:' . $city . ' AND jurnamn:' . $companyName . 
			'&fields=orgnr,jurnamn,ba_postort,oms_X,anst_X,mgmt,ba_gatuadress,ba_postnr,ba_lan,riktnrtelnr,bolord'
		);
		
		$records = $allaBolagResponse->records[0];
		$results = [];
		foreach ($records as $record) {
			array_push($results, $record->mgmt);
		}
		// return dd($results);
		if (empty($results)) {
			return 'Kunde inte hitta någon kontaktperson från allabolag';
		}
		// return $results[0];

		// $contactPerson = str_replace(',', '', strtolower($results[0]));

		// return $contactPerson;
		$contactPerson = strtolower($results[0]);
		// $cases = explode(' ', $contactPerson);
		$cases = explode(',', $contactPerson);
        if(count($cases) > 1)
        {
            $cases = explode(' ', $contactPerson);
            $cases[0] = str_replace(',', '', $cases[0]);
            $this->firstname = [$cases];
            $this->lastname = ucfirst($cases[0]);
        } else{
            $cases = explode(' ', $contactPerson);
            $lastname = $cases[1];
            $firstname = $cases[0];
            $cases[0] = $lastname;
            $cases[1] = $firstname;
            $this->firstname = ucfirst($firstname);
            $this->lastname = ucfirst($lastname);
        }
        // return dd($cases);

		$casesCount = count($cases);
		for ($i = 1; $i < $casesCount; $i++) { 
			array_push($cases, $cases[$i] . '.' . $cases[0]);
		}
		// return dd($cases);

		foreach ($cases as $case) {
			$postdata = http_build_query(
				[
					'access_token' => $token,
					'email' => $case . '@' . $url
				]
			);

			$opts = ['http' =>
				[
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => $postdata
				]
			];

			$context = stream_context_create($opts);

			$response = json_decode(file_get_contents('https://api.neverbounce.com/v3/single', false, $context), true);

			if ($response['result'] == 0) {
				$email = $case . '@' . $url;
				$firstname = explode('@', $email);

				if (strpos($firstname[0], '.') !== false) {
					$firstname = explode('.', $email);
				}

	            return [
	            	'firstname' => ucfirst($firstname[0]),
	            	'lastname' => $this->lastname,
	            	'email' => $case . '@' . $url
	            ];
	        }
    	}
	}
}