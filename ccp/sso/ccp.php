 <?php
 
function msTimeStamp() 
{
    return round(microtime(1) * 1000);
}  



function getCcpUserDomains($username, $ApiEndPoint)
{

// http://cloud2.cam.onelab.citrix.com:8080/client/api?command=listUsers&response=json&sessionkey=null&_=1417095470600

	$admin = 'admin';
	$domainid = "756a3f18-49ef-11e3-9349-001d096561b2";
	
	$apikey = 'ka0NgUHHqfzkY_GJi6m9A5ghYysDluDDB9jWJ1Lg-nSEmxktog6tU8MnUZQiEa6t5Kqgh6kIJEbiVPO8JcIbJQ';
	$secretKey = 'b-F-GBAgea0OAsUnDmAokIRgkQWMiDKCsu3YGHqHDzkm5euHRgDSiFepEgGF2J9QBO5vxHAvkhhORFLM7iHLcQ';

	$adminloginuri = getCcpLoginUrl($ApiEndPoint, $username, $domainid);
	
	//listUsersUri =  $ApiEndPoint . '?command=listUsers&response=json&sessionkey=' . $sessionKey  .  '&username=' . $username . '&_=' . $timestamp;
	
	$ApiParams = array();

	$ApiParams['command'] = "listUsers";
	
	$ApiParams['username'] = $username;
	$ApiParams['apikey'] = $apikey;
	$ApiParams['response'] = "json";
	
	$listUsersUri = createApiUri($ApiEndPoint, $ApiParams, $secretKey);
	
	$json = getCcpData($listUsersUri);

	$users = $json['listusersresponse']['user'];
	$count = count($users); 

	$domains = array();

	foreach ($users as $user) 
	{
		$domainid = $user['domainid'];
	  
		$ApiParams = array();
		$ApiParams['command'] = "listDomains";	
		$ApiParams['apikey'] = $apikey;
		$ApiParams['response'] = "json";
		$ApiParams['id'] = $domainid;
		
		$listDomainsUri = createApiUri($ApiEndPoint, $ApiParams, $secretKey);
		
		$domaindata = getCcpData($listDomainsUri);
		$path = $domaindata['listdomainsresponse']['domain'][0]['path'];
		
		$domains[$domainid] = str_replace('ROOT/','', $path);
	}
	
	//var_dump($domains);
	
	
	
	//die('');

	// TODO get user's configured domains
	
	
	//$domainid = "756a3f18-49ef-11e3-9349-001d096561b2";
	//$domainid = "1bcca0d5-00b3-4a96-baa9-309adb3ee0f8";
	//$domains = array();
	//$domains[0] = $domainid;
	
	return $domains;
}

function createApiUri($ApiEndPoint, $ApiParams, $secretKey)
{
	//var_dump($ApiParams);
			
	ksort($ApiParams);
	$queryString = http_build_query($ApiParams);
	$queryString = str_replace("+", "%20", $queryString);

	$hash = @hash_hmac("SHA1", strtolower($queryString), $secretKey, true);
	$base64encoded = base64_encode($hash);
	$sig = urlencode($base64encoded);

	$queryString .= "&signature=" . $sig;
	$url = $ApiEndPoint . "?" . $queryString;

	//$url = "http://cloud2.cam.onelab.citrix.com:8080/client/api?command=login&domainid=1bcca0d5-00b3-4a96-baa9-309adb3ee0f8&response=json&timestamp=1416499483523&username=pauld&signature=1PDqFmPYXcBUHvZcmTExRNh50uU%3D";
	 
	return 	$url;

}

function getCcpLoginUrl($ApiEndPoint, $username, $domainid)
{
	$secretkeyfile = 'ccp.txt';
	$secretKey = "";

	// get ccp secret
	if(is_file($secretkeyfile))
	{
		$lines = file($secretkeyfile);
		
		foreach($lines as $line) {
			$secretKey = $line;
			break;
		}
	}
	else
	{
		error_log("start.php: file not found: " . $secretkeyfile);
		die("internal error");
	}

	// construct login url	
	$timestamp = msTimeStamp();

	$ApiParams = array();

	$ApiParams['command'] = "login";
	$ApiParams['username'] = $username;
	$ApiParams['domainid'] = $domainid;
	$ApiParams['timestamp'] = strval($timestamp);
	$ApiParams['response'] = "json";
	
	//var_dump($ApiParams);
			
	/////ksort($ApiParams);
	//$queryString = http_build_query($ApiParams);
	//$queryString = str_replace("+", "%20", $queryString);

	//$hash = @hash_hmac("SHA1", strtolower($queryString), $secretKey, true);
	//$base64encoded = base64_encode($hash);
	//$sig = urlencode($base64encoded);

	//$queryString .= "&signature=" . $sig;
	//$url = $ApiEndPoint . "?" . $queryString;

	//$url = "http://cloud2.cam.onelab.citrix.com:8080/client/api?command=login&domainid=1bcca0d5-00b3-4a96-baa9-309adb3ee0f8&response=json&timestamp=1416499483523&username=pauld&signature=1PDqFmPYXcBUHvZcmTExRNh50uU%3D";
	 
	return 	createApiUri($ApiEndPoint, $ApiParams, $secretKey);
}

function getCcpData($url, $cookiefile = '')
{
	//$cookiefile = 'cookie.txt';
	
	// create a new cURL resource
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	if($cookiefile <> '')
	{
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile); // write cookie to file
	}

	// get data from url
	$data = curl_exec($ch);

	// close cURL resource, and free up system resources
	
	curl_close($ch);

	// decode json str
	$json = json_decode($data,true);
	
	return $json;
}

function getCcpLoginResponse($url)
{
	$cookiefile = 'cookie.txt';

	$json = getCcpData($url, $cookiefile);

	// if ccp has returned an error log and report
	if(isset($json['errorresponse'] ))
	{
		echo $json['errorresponse']['errortext'];
		echo '<br/>';
		
		error_log("start.php: No CloudPlatform account found: ");
		die("No CloudPlatform account found - contact administrator to create an account");
	}

	// extract user account info from loginresponse
	$fullusername = rawurlencode($json['loginresponse']['firstname']." ".$json['loginresponse']['lastname']);
	$sessionkey = $json['loginresponse']['sessionkey'];
	$timeout = $json['loginresponse']['timeout'];
	$userid = $json['loginresponse']['userid'];
	$timezone = $json['loginresponse']['timezone'];
	$timezoneoffset = $json['loginresponse']['timezoneoffset'];
	$account = $json['loginresponse']['account'];
	$role = $json['loginresponse']['type'];
	$username = $json['loginresponse']['username'];
	$domainid = $json['loginresponse']['domainid'];
	
	$jsessioniddomain = '';
	$jsessionid = '';
	$jsessionidname = 'JSESSIONID';
	$jsessionidpath = '';

	// get JSESSIONID from cookie
	if(is_file($cookiefile))
	{
		$lines = file($cookiefile);
		
		foreach($lines as $line) {
			// we only care for valid cookie def lines
			if($line[0] != '#' && substr_count($line, "\t") == 6) {

				// get tokens in an array
				$tokens = explode("\t", $line);

				// trim the tokens
				$tokens = array_map('trim', $tokens);
				
				$jsessioniddomain = $tokens[0];
				$jsessionid = $tokens[6];
				$jsessionidpath = $tokens[2];
			}
		}
		//cloud2.cam.onelab.citrix.com	FALSE	/client	FALSE	0	JSESSIONID	F53ABA0907509D1D30BF9CEA04F99EC3
		
	}
	else
	{
		error_log("start.php: cookie file not found: " . $cookiefile);
		die("internal error");
	}

	// set CCP cookies
	setcookie( 'JSESSIONID', $jsessionid, 0 , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setrawcookie( 'userfullname', $fullusername, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setcookie( 'sessionKey', rawurlencode($sessionkey), time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);

	setcookie( 'username', $username, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setcookie( 'account', $account, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setcookie( 'userid', $userid, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setcookie( 'domainid', $domainid, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setcookie( 'timezone', $timezone, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setcookie( 'timezoneoffset', $timezoneoffset, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);
	setcookie( 'role', $role, time()+$timeout , $jsessionidpath, '.cam.onelab.citrix.com', false, false);

}



?>