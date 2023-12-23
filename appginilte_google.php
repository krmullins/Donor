<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
require_once 'appginilte/vendor/autoload.php'; 
$GoogleOauthData=json_decode(sqlValue("SELECT option_value FROM alte_options WHERE option_name='GoogleOAuth'"),true);
// init configuration
$clientID = $GoogleOauthData['clientID'];
$clientSecret = $GoogleOauthData['clientSecret'];
$redirectUri = $GoogleOauthData['redirectUri'];

if(empty($clientID)||empty($clientSecret)||empty($redirectUri)){
  redirect('index.php');
  exit;
}
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
  
// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);
   
  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

  // check if user is already registered
  if(sqlValue("SELECT COUNT(*) FROM membership_users WHERE email='$email'")==0){
    header('Location: index.php?signIn=1&error=nouser');
  }
  elseif(sqlValue("SELECT COUNT(*) FROM membership_users WHERE email='$email'")>1){
    header('Location: index.php?signIn=1&error=dupusers');
  }
  elseif(sqlValue("SELECT COUNT(*) FROM membership_users WHERE email='$email' AND isApproved=1 AND isBanned=0")==0){
    header('Location: index.php?signIn=1&error=notapproved');
  }
  else{
    //store session data and login user.
    $memberID=sqlValue("SELECT memberID FROM membership_users WHERE email='$email'");
    $memberGroupID=sqlValue("SELECT groupID FROM membership_users WHERE email='$email'");
    $setupMembership=time();
    //get config data
    include('config.php'); //config file
    $definedadminUsername = $adminConfig['adminUsername'];
    $adminUsername=($memberID==$definedadminUsername)?$definedadminUsername:'';
    $_SESSION['memberID']=$memberID;
    $_SESSION['memberGroupID']=$memberGroupID;
    $_SESSION['adminUsername']=$adminUsername;
    $_SESSION['setupMembership']=$setupMembership;
    redirect('index.php');
  }

} else {
  $authurl=$client->createAuthUrl();
  redirect($authurl,true);
}
?>