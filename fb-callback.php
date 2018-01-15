<?php if(!session_id()) {
    session_start();
}
ini_set('max_execution_time', 0);
 date_default_timezone_set("Asia/Bangkok");
require_once __DIR__ . '/vendor/autoload.php'; 
$fb = new Facebook\Facebook([
  'app_id' => '1479643915624407', // Replace {app-id} with your app id
  'app_secret' => 'bcbdfe909b17ee0ff5b8fae004075dbe',
  'default_graph_version' => 'v2.2',
  
    'persistent_data_handler'=>'session'
  ]);

$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
echo '<h3>Access Token</h3>';
echo $accessToken->getValue();


// get list of groups managed by user
	try {
		$requestGroups = $fb->get('/me/groups/',$accessToken->getValue());
		$groups = $requestGroups->getGraphEdge()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
  		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	echo "<br>";
	foreach ($groups as $key) {
		echo "++++++++++++++++++<br/>";
		echo $key['name'] . "<br>";
		echo "--------comments----<br/>";
		
		try {
  // Returns a `Facebook\FacebookResponse` object
		  $response = $fb->get(
			'/'.$key['id']."/feed",$accessToken->getValue()
		  );
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
		$graphNode = $response->getGraphEdge()->asArray();
		
		
		//get comments----
		foreach ($graphNode as $key2) {
		echo "++++++++++++++++++<br/>";
		echo $key2['id'] . "<br>";
		echo "--------comments----<br/>";
		
		try {
  // Returns a `Facebook\FacebookResponse` object
		  $response2 = $fb->get(
			'/'.$key2['id']."/comments",$accessToken->getValue()
		  );
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
		$graphNode2 = $response2->getGraphEdge()->asArray();
		echo "<pre>".print_r($graphNode2);
		}
		
		
		//print_r($graphNode);
/* handle the result */
echo "--------commentsend----<br/>";
		
		
	}
// get list of page managed by user
	try {
  // Returns a `FacebookFacebookResponse` object
  $response = $fb->get(
    '/me/accounts?fields=access_token,id,name,likes,country_page_likes',$accessToken->getValue()
  );
} catch(FacebookExceptionsFacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(FacebookExceptionsFacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
$graphNode = $response->getGraphEdge()->asArray();
echo "<pre>".print_r($graphNode);
echo "<br/>";
	foreach ($graphNode as $key) {
		//print_r($key);
		echo  "==============<br>";
		echo "id = ".$key['id']. "<br>";
		echo "name = ".$key['name']. "<br>";
		echo "access_token = ".$key['access_token']. "<br>";
		echo "page like = ".$key['country_page_likes']. "<br>";
		
		
	}


// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('1479643915624407'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
?>