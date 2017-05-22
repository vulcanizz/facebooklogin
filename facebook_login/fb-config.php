<?php
require_once __DIR__ . '/src/facebook/autoload.php';
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;


$appId = '131191310771020';
$appSecret = 'ab12fa0bb2037f9be59b422ab9cccecc';
$fbRedirectURL = 'http://www.mytest.me/facebook_login';
$fbPermissions = ['email']; 


$fb = new Facebook\Facebook([
  'app_id' => $appId,
  'app_secret' => $appSecret,
  'default_graph_version' => 'v2.8',
]);

// Get redirect login helper
$helper = $fb->getRedirectLoginHelper();

// Try to get access token
try {
    if(isset($_SESSION['facebook_access_token'])){
        $accessToken = $_SESSION['facebook_access_token'];
    }else{
          $accessToken = $helper->getAccessToken();
    }
} catch(FacebookResponseException $e) {
     echo 'Graph returned an error: ' . $e->getMessage();
      exit;
} catch(FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
}
?>
