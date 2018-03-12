<?php
ob_start();
session_start();
require_once('inc/db.php');
require_once('inc/Facebook/autoload.php');
$fb = new Facebook\Facebook([
  'app_id' => $FacebookAppID,
  'app_secret' => $FacebookAppSecret,
  'default_graph_version' => 'v2.10',
  ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = [];

try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
}
catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();

    exit;
}
catch (Facebook\Exceptions\FacebookSDKException $e) {
// When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    ;
}

if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        // getting short-lived access token
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        // OAuth 2.0 client handler
        $oAuth2Client                      = $fb->getOAuth2Client();
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken              = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        // setting default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    // redirect the user back to the same page if it has "code" GET variable

    if (isset($_GET['code'])) {
        header("Location: ./");
    }
    // getting basic info about user
    try {
        $profile_request = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,picture');
        $profile         = $profile_request->getGraphNode()->asArray();
        if (!isset($_SESSION['facebook_user_name'])) {
            $_SESSION['facebook_user_id']   = $profile['id'];
            $_SESSION['facebook_user_name'] = $profile['name'];
        }
    }
    catch (Facebook\Exceptions\FacebookResponseException $e) {
        session_destroy();
        // Redirect user back to app login page
        header("Location: ./");

    }
    catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

} else {
    $loginUrl = $helper->getLoginUrl($url . '/login.php', $permissions);
}



ob_end_flush();
?>
