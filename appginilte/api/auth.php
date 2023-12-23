<?php
include_once 'includes.php';

//get POST request parameters
$response= json_decode(file_get_contents('php://input'), true);
//required: username, password
$username=makeSafe(strtolower($response['username']));
$password=$response['password'];
$hash=sqlValue("select passMD5 from membership_users where lcase(memberID)='{$username}' and isApproved=1 and isBanned=0");

if (password_match($password, $hash)) {
	# code...
     //generate access token
    $access_token=encyptString($hash);
	$user_data=sql("SELECT * FROM membership_users WHERE lcase(memberID)='{$username}' AND isApproved=1 AND isBanned=0",$eo);
    $Data=[];
    foreach ($user_data as $key => $value) {
        # code...
        $Data['memberID']=$value['memberID'];
        $Data['email']=$value['email'];
        $Data['groupID']=$value['groupID'];
        $Data['custom1']=$value['custom1'];
        $Data['custom2']=$value['custom2'];
        $Data['custom3']=$value['custom3'];
        $Data['custom4']=$value['custom4'];
        $Data['signupDate']=$value['signupDate'];
    }
	echo json_response(200, array(
		'Error'=>'0',
		'Message' =>"User Authenticated Successfully",
		'User_Data'=>$Data,
        'Access_Token'=>$access_token
	));
}
else{
	echo json_response(200, array(
		'Error'=>'1',
		'Message'=>"Login attempt failed, try again."
	));
}