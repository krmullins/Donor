<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
// Include the twitter-api-php library
require_once('appginilte/twitter-api-php/TwitterAPIExchange.php');
// Define your Twitter API credentials
$TwitterOAuth = json_decode(sqlValue("SELECT option_value FROM alte_options WHERE option_name='TwitterOAuth'"),true);
define('CONSUMER_KEY', $TwitterOAuth['CONSUMER_KEY']);
define('CONSUMER_SECRET', $TwitterOAuth['CONSUMER_SECRET']);
define('oauthToken', $TwitterOAuth['oauthToken']);
define('oauthTokenSecret', $TwitterOAuth['oauthTokenSecret']);
define('oauth_callback', $TwitterOAuth['oauth_callback']);

if(empty(CONSUMER_KEY)||empty(CONSUMER_SECRET)||empty(oauthToken)||empty(oauthTokenSecret)||empty(oauth_callback)){
    redirect('index.php');
    exit;
}

$action = $_GET['action'] ? $_GET['action'] : "";

if ($action == 'login') {
    try {
        // The user hasn't authorized the app yet, so get a request token from Twitter
        $twitter = new TwitterAPIExchange(
            array(
                'oauth_access_token' => oauthToken,
                'oauth_access_token_secret' => oauthTokenSecret,
                'consumer_key' => CONSUMER_KEY,
                'consumer_secret' => CONSUMER_SECRET
            )
        );
    
        $postfields = array(
            'oauth_callback' => oauth_callback
        );
    
        $response = $twitter->buildOauth('https://api.twitter.com/oauth/request_token', 'POST')
            ->setPostfields($postfields)
            ->performRequest();
    
        parse_str($response, $tokens);
        $oauth_token = $tokens['oauth_token'];
        $oauth_token_secret = $tokens['oauth_token_secret'];
    
        // Redirect the user to the Twitter authorization URL
        $url = 'https://api.twitter.com/oauth/authorize?oauth_token=' . $oauth_token;
        header('Location: ' . $url);
        exit;
    } catch (Exception $e) {
        // Handle the error
        echo 'The Following error occurred: ' . $e->getMessage();
        echo "<br> To get you going, please <a href='index.php'><b>Go back to login page and try again</b></a>. If the error persists, contact your site admin.";
    }    
} else {
    // Start a new session
    session_start();

    // If the oauth_token and oauth_verifier are set, the user has authorized the app
    if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {

        // Create a new TwitterAPIExchange instance with your API credentials
        $settings = array(
            'oauth_access_token' => oauthToken,
            'oauth_access_token_secret' => oauthTokenSecret,
            'consumer_key' => CONSUMER_KEY,
            'consumer_secret' => CONSUMER_SECRET
        );
        $twitter = new TwitterAPIExchange($settings);

        // Get the access token and access token secret from Twitter
        $oauth_token = $_GET['oauth_token'];
        $oauth_verifier = $_GET['oauth_verifier'];
        $response = $twitter->setGetfield("oauth_token=$oauth_token&oauth_verifier=$oauth_verifier")
            ->buildOauth('https://api.twitter.com/oauth/access_token', 'GET')
            ->performRequest();
        parse_str($response, $tokens);
        $access_token = $tokens['oauth_token'];
        $access_token_secret = $tokens['oauth_token_secret'];

        // Use the access token and access token secret to get the user's Twitter ID and screen name
        $twitter = new TwitterAPIExchange(
            array(
                'oauth_access_token' => $access_token,
                'oauth_access_token_secret' => $access_token_secret,
                'consumer_key' => CONSUMER_KEY,
                'consumer_secret' => CONSUMER_SECRET
            )
        );
        $response = $twitter->setGetfield('user_id')
            ->buildOauth('https://api.twitter.com/1.1/account/verify_credentials.json', 'GET')
            ->performRequest();
        $user = json_decode($response);
        $user_id = $user->id_str;
        $screen_name = $user->screen_name;

        // Store the access token, access token secret, user ID, and screen name in the session
        $_SESSION['oauth_access_token'] = $access_token;
        $_SESSION['oauth_access_token_secret'] = $access_token_secret;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['screen_name'] = $screen_name;

        // check if user is already registered
        if (sqlValue("SELECT COUNT(*) FROM membership_users WHERE memberID='$screen_name'") == 0) {
            header('Location: index.php?signIn=1&error=nouser');
        } elseif (sqlValue("SELECT COUNT(*) FROM membership_users WHERE memberID='$screen_name'") > 1) {
            header('Location: index.php?signIn=1&error=dupusers');
        } elseif (sqlValue("SELECT COUNT(*) FROM membership_users WHERE memberID='$screen_name' AND isApproved=1 AND isBanned=0") == 0) {
            header('Location: index.php?signIn=1&error=notapproved');
        } else {
            //store session data and login user.
            $memberID = sqlValue("SELECT memberID FROM membership_users WHERE memberID='$screen_name'");
            $memberGroupID = sqlValue("SELECT groupID FROM membership_users WHERE memberID='$screen_name'");
            $setupMembership = time();
            //get config data
            include('config.php'); //config file
            $definedadminUsername = $adminConfig['adminUsername'];
            $adminUsername = ($memberID == $definedadminUsername) ? $definedadminUsername : '';
            $_SESSION['memberID'] = $memberID;
            $_SESSION['memberGroupID'] = $memberGroupID;
            $_SESSION['adminUsername'] = $adminUsername;
            $_SESSION['setupMembership'] = $setupMembership;
            redirect('index.php');
        }

    }
}