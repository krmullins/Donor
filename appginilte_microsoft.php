<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
$MicrosoftOauthData = json_decode(sqlValue("SELECT option_value FROM alte_options WHERE option_name='MicrosoftOAuth'"),true);
$appid = $MicrosoftOauthData['appid'] ? $MicrosoftOauthData['appid'] : '';
$tennantid = $MicrosoftOauthData['tennantid'] ? $MicrosoftOauthData['tennantid'] : '';
$secret= $MicrosoftOauthData['secret']? $MicrosoftOauthData['secret'] : '';
$redirectUri = $MicrosoftOauthData['redirectUri'] ? $MicrosoftOauthData['redirectUri'] : '';
$login_url = "https://login.microsoftonline.com/" . $tennantid . "/oauth2/v2.0/authorize";

if(empty($appid)||empty($tennantid)||empty($secret)||empty($redirectUri)){
  redirect('index.php');
  exit;
}


session_start();

$_SESSION['state'] = session_id();
if ($_GET['action'] == 'login') {

    $params = array(
        'client_id' => $appid,

        'redirect_uri' => $redirectUri,

        'response_type' => 'token',

        'scope' => 'https://graph.microsoft.com/User.Read',

        'state' => $_SESSION['state']
    );

    header('Location: ' . $login_url . '?' . http_build_query($params));

}


echo '

<script> url = window.location.href;

i=url.indexOf("#");

if(i>0) {

 url=url.replace("#","?");

 window.location.href=url;}

</script>

';


if (array_key_exists('access_token', $_GET)) {

    $_SESSION['t'] = $_GET['access_token'];

    $t = $_SESSION['t'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $t,

        'Conent-type: application/json'
    )
    );

    curl_setopt($ch, CURLOPT_URL, "https://graph.microsoft.com/v1.0/me/");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $rez = json_decode(curl_exec($ch), 1);

    if (array_key_exists('error', $rez)) {

        var_dump($rez['error']);

        die();

    } else {
        //get response parameters from microsoft
        $_SESSION['msatg'] = 1; //auth and verified
        $name = $rez["displayName"];
        $email=$rez["mail"];
        $session_id = $rez["id"];
    }

    curl_close($ch);

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

}