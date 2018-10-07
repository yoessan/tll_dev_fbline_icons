<?php

	include('../db_connect.php');
	include('../tll_functions.php');
	include('../session_handler.inc');
	
	//mail("yoes@khuntin.com", $_POST['userid'], $Sess_UserId);
	
	if(!isset($_POST['userid']) || !isset($Sess_UserId)){
		header("Location: $CONST_LINK_ROOT/home.php");
		exit;
	}
	
	if (isset($_POST['userid']) && isset($Sess_UserId)) {
	//if(1==0){
	
		if($_POST['from_who']){
			$Sess_UserId = $_POST['from_who'];
			//201420427 - this should be id only
			//$Sess_UserId = $Sess_UserName;
		}
	
		$userid=$_POST['userid'];
	
		$handle=$_POST['handle'];
		$retval=mysql_query("SELECT adv_title FROM adverts WHERE adv_userid='$userid'",$link);
		$sql_array = mysql_fetch_object($retval);
		$advtitle=addslashes($sql_array->adv_title);
		
		$retval=mysql_query("SELECT * FROM hotlist WHERE (hot_userid='$Sess_UserId' AND hot_advid='$userid')",$link);
		$result=mysql_num_rows($retval);
	
		if ($result == 0) {
			$tempDate=date("Y/m/d");
			
			//20140427
			//default handle to userid
			if(!$handle){
				$handle = $userid;	
			}
			
			$query="INSERT INTO hotlist (hot_userid, hot_advid, hot_screenname, hot_dateadded, hot_title) values ('$Sess_UserId', '$userid', '$handle','$tempDate', \"$advtitle\")";
			
			
			$result=mysql_query($query,$link) or die(mysql_error());
			$querysave=$query;
		}
		
		//send internal favorite email to target member	
		$sender_name = ucfirst(getFirstItem("select mem_forename from members where mem_userid = '$Sess_UserId'"));
		$receiver_name = ucfirst(getFirstItem("select mem_forename from members where mem_userid = '$userid'"));
		
		//yoes 17 sep make it so suspended member can't send mails instead
		//$sender_approved = getFirstItem("select adv_approved from adverts where adv_userid = '$Sess_UserId'");
		$sender_suspend = getFirstItem("select mem_suspend from members where mem_userid = '$Sess_UserId'");
		
		# send the flirt internally
		$subject="You are one of My Favorites";
		
		$tempdate=date("Y/m/d"); //send date
			
		$data['receiver_name'] = $receiver_name;
		$data['receiver_id'] = $userid;
		$data['sender_name'] = $sender_name;
		$data['sender_id'] = $Sess_UserId;
		$data['his_her'] = getHisHer($Sess_UserId);
		
		list($type,$message) = getTemplateByName("got_favorited",$data,"EN");
		$message = addslashes($message);
		
		//if($sender_approved ==1){
		if($sender_suspend != "Y"){
			$query="INSERT INTO messages (msg_senderid, msg_receiverid, msg_senderhandle, msg_title, msg_text, msg_dateadded, msg_read) VALUES ('$Sess_UserId', '$userid', '$Sess_UserName', '$subject', '$message', '$tempdate', 'U')"; 		
			mysql_query($query,$link) or die(mysql_error());
		}
		
		//internal mail sent
		//then
		//send out external email
		// ';;;;
		$subject="You are a Favorite on ThaiLoveLines.com";
		
		$external_data['requester'] = $sender_name;
		$external_data['requesterid'] = $Sess_UserId;
		$external_data['requester_photo_medium'] = getFullPhotoPath($Sess_UserId);
		$external_data['target_name'] = $receiver_name;
		$external_data['targetid'] = $userid;
		
		$external_data['receiver_id'] = $userid;
		$external_data['receiver_password'] = getFirstItem("select mem_password from members where mem_userid = '$userid'");
		
		$receiever_email = getFirstItem("select mem_email from members where mem_userid = '$userid'");
		
		list($type,$external_message) = getTemplateByName("external_favorite",$external_data,"EN");
		$external_message = stripslashes($external_message);
	
		//if($sender_approved == 1){
		if($sender_suspend != "Y"){
			# send the mail externally
			send_mail($receiever_email.", support@thailovelines.com", "$CONST_MAIL", $subject , $external_message,$type,"ON");	
		}
		
		echo "fav_sent";
		
	}

?>