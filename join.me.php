<html>
<?php
define('JOINMEID', '');
define('JOINMEPASS', '');
define("ZDAPIKEY", '');
define("ZDUSER", '');
define("ZDURL", 'https://subdomain.zendesk.com/api/v2');

/* Note: the ZDURL needs to not have a trailing slash after v2 */

function curlWrap($url, $json, $action)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
	switch($action){
		case "POST":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "GET":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		default:
			break;
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($output);
	return $decoded;
}

switch ($_GET[skido])
	{
	case 1:
		$tid = $_GET[ticketid];
		$params = "email=".JOINMEID."&password=".JOINMEPASS;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_URL, "https://secure.join.me/API/requestAuthCode.aspx");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);
		$n = strpos($output, ":");
		$auth = substr($output, $n+2, strlen($output) - $n - 2);  
		$params = "authcode=".$auth;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_URL, "https://secure.join.me/API/requestCode.aspx");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);
		$output2 = explode(":", $output);
		$f = strpos($output, "TICKET:");
		$n = strpos($output, ":");
		$auth = substr($output, $n+2, $f - $n - 2); 
		echo "<p>Your session is created:</p>";
		$code = trim(substr($output2[1], 0, strpos($output2[1], "\n")));
		$ticket = trim($output2[2]);
		echo "<p><a href=\"http://www.hoobydo.com/foo/Code%20Testing/join.me.php?skido=3&ticketid=".$tid."&ticket=".$ticket."&code=".$code."\">Click here to send to customer.</a></p>";
	break;
	case 3:
		$tid = $_GET[ticketid];
		$ticket = $_GET[ticket];
		$code = $_GET[code];
		$link = "https://secure.join.me/download.aspx?code=".$code."&ticket=".$ticket;
		$data = "{\"ticket\":{\"comment\":{\"value\":\"Hello, A Zendesk representative would like to take a look at your computer screen. Please click this link: ".$link." and then download and run the application.\"}}}";
		$output = curlWrap('/tickets/'.$tid.'.json', $data, "PUT");		
		echo "<p>Ticket Updated!</p>";
		$data = "{\"ticket\":{\"comment\":{\"value\":\"Click here to view http://www.join.me/".$code."\", \"public\": false}}}";
		$output = curlWrap('/tickets/'.$tid.'.json', $data, "PUT");	
	break;
	default:
		$tid = $_GET[ticketid];
		echo "<p>Create Session: <a href=\"http://www.hoobydo.com/foo/Code%20Testing/join.me.php?ticketid=".$tid."&skido=1\" target=\"_self\">Join Me!</a></p>";
	break;
}
?>
</html>