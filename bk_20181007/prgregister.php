<?php
/*****************************************************
* � copyright 1999 - 2003 Interactive Arts Ltd.
*
* All materials and software are copyrighted by Interactive Arts Ltd.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:                 prgregister.php
#
# Description:  creates or updates registration information
#
# Version:                7.3
#
######################################################################

include('db_connect.php');
$is_speeddating = $_POST[speeddating];
if($is_speeddating)
        include('speeddating/error.php');
else
        include('pre_error.php');
include('imagesizer.php');
include('message.php');
include('tll_functions.php');

$_SESSION["post"] = $_POST;
$client_ip = $_SERVER["REMOTE_ADDR"]." ".$_SERVER["HTTP_X_FORWARDED_FOR"];

setcookie("lstCountry", $_POST['lstCountry']);
setcookie("lstState", $_POST['lstState']);
setcookie("lstCity", $_POST['lstCity']);

$mode=$_GET['mode'];
$txtSurname= doCleanInput(substr($_POST['txtSurname'],0,25));
$txtForename= doCleanInput(substr($_POST['txtForename'],0,25));
$lstDay= substr($_POST['lstDay'],0,2);
$lstMonth= substr($_POST['lstMonth'],0,2);
$lstSex= substr($_POST['lstSex'],0,1);
$security=$_POST['security'];
$txtEmail=doCleanInput(substr($_POST['txtEmail'],0,75));
$txtYear= substr($_POST['txtYear'],0,2);
if (isset($_POST['chkNews'])) $chkNews=$_POST['chkNews'];
if (isset($_POST['chkDisclaimer'])) $chkDisclaimer=$_POST['chkDisclaimer'];
$txtConfirm=$_POST['txtConfirm'];
$txtPassword= doCleanInput(substr($_POST['txtPassword'],0,10));
$lstSkypeSettings= substr($_POST['lstSkypeSettings'],0,10);
$txtSkypename= doCleanInput($_POST['txtSkypename']);



//yoes 2018106 -- add fields for FB+Line
$lstFacebookSettings= substr($_POST['lstFacebookSettings'],0,10);
$txtFacebookname= doCleanInput($_POST['txtFacebookname']);

$lstLineSettings= substr($_POST['lstLineSettings'],0,10);
$txtLinename= doCleanInput($_POST['txtLinename']);


# retrieve the template
if($is_speeddating){
    $area = 'speeddating';
} elseif ($_SESSION['Sess_JustRegistered']==true) {
    $area = 'guest';
} else {
    $area = 'member';
}
if ($mode!='create') {
        session_cache_limiter('private, must-revalidate');
        session_start();
}
# if mode is create then there are extra fields to validate
if ($mode=='create') {
        $txtHandle = doCleanInput(substr(trim($_POST['txtHandle']),0,25));
        if (isset ($_POST['chkDisclaimer'])) $chkDisclaimer=$_POST['chkDisclaimer'];

        if ($security != $_SESSION['securityCode'] && $SECURITY_REGISTRATION) {
                $error_message=PRGREGISTER_TEXT39;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }

        if (strstr($txtHandle," ")) {
                $error_message=PRGREGISTER_TEXT32;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        if ((empty($txtHandle) || strlen($txtHandle) < 6)) {
                        $error_message=PRGREGISTER_TEXT33;
                        error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        if (strlen($txtHandle) > 25 && $mode=='create' ) {
                        $error_message=PRGREGISTER_TEXT34;
                        error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        if ($txtHandle == 'genericc' || $txtHandle == 'genericm' || $txtHandle == 'genericf') {
                        $error_message=PRGREGISTER_TEXT35;
                        error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
}
# for create and update these fields require validation
$txtPassword=trim($txtPassword);
if (empty($txtPassword) || strlen($txtPassword) < 6) {
        $error_message=PRGREGISTER_TEXT1;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtConfirm=trim($txtConfirm);
if (empty($txtConfirm) || strlen($txtConfirm) < 6) {
        $error_message=PRGREGISTER_TEXT2;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtConfirm=trim($txtConfirm);
if ($txtPassword != $txtConfirm) {
        $error_message=PRGREGISTER_TEXT3 ;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtSurname=trim($txtSurname);
if (empty($txtSurname) || strlen($txtSurname) < 2) {
        $error_message=PRGREGISTER_TEXT4;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if (strlen($txtSurname) > 25 ) {
                $error_message=PRGREGISTER_TEXT5;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtForename=trim($txtForename);
if (empty($txtForename) || strlen($txtForename) < 2) {
        $error_message=PRGREGISTER_TEXT6;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if (strlen($txtForename) > 25 ) {
                $error_message=PRGREGISTER_TEXT7;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if ($lstDay == "...") {
        $error_message=PRGREGISTER_TEXT8;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if ($lstMonth == "...") {
                $error_message=PRGREGISTER_TEXT9;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if (empty($txtYear) || strlen($txtYear) <> 2) {
        $error_message=PRGREGISTER_TEXT10;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
// Calculate age
$testdate=date("Ymd");
$dobdate=($txtYear+1900)."/".$lstMonth."/".$lstDay;
$testage = (int) (( $testdate - $dobdate ) / 10000);
if ($testage < 18 ) {
    $error_message=PRGREGISTER_TEXT11;
    error_page($error_message,GENERAL_USER_ERROR, $mode);
}

if ($lstSex == "- Choose -") {
        $error_message=PRGREGISTER_TEXT12;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtEmail=trim($txtEmail);
if (empty($txtEmail) || strlen($txtEmail) < 2) {
        $error_message=PRGREGISTER_TEXT36;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if ($mode=='create') {
        if (! isset ($chkDisclaimer))
        {
           $error_message=PRGREGISTER_TEXT15;
           error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
}
# if mode is create and it is not speeddating registration
# then there are advert fields to validate
if ($mode=='create' && !$is_speeddating) {

        $txtLocation=$_POST['txtLocation'];
        $lstSeeking=$_POST['lstSeeking'];

if (!$GEOGRAPHY_JAVASCRIPT){
    $aCountry= split(";",$_POST['lstCountry']);
    $lstCountry=$aCountry[0];
    $lstState=$aCountry[1];
} else {
    $lstCountry=$_POST['lstCountry'];
    $lstState=$_POST['lstState'];
}
        $lstCity=$_POST['lstCity'];
        $lstSmoker=$_POST['lstSmoker'];
        $lstDrink=$_POST['lstDrink'];
        $lstBodyType=$_POST['lstBodyType'];
        $lstChildren=$_POST['lstChildren'];
        $lstMarital=$_POST['lstMarital'];
        $lstReligion=$_POST['lstReligion'];
        $lstEthnicity=$_POST['lstEthnicity'];
        $lstEducation=$_POST['lstEducation'];
        $lstHeight=$_POST['lstHeight'];
        $lstEyecolor=$_POST['lstEyecolor'];
        $lstHaircolor=$_POST['lstHaircolor'];
        $lstEmployment=$_POST['lstEmployment'];
        $lstIncome=$_POST['lstIncome'];
        $txtTitle=$_POST['txtTitle'];
        $txtComment=$_POST['txtComment'];
        if ($CONST_ZIPCODES=='Y') {
                $txtZipcode=$_POST['txtZipcode'];
        } else {
                $txtZipcode="";
        }
        if (isset($_POST['chkSeekmen'])) $chkSeekmen=$_POST['chkSeekmen'];
        if (isset($_POST['chkSeekwmn'])) $chkSeekwmn=$_POST['chkSeekwmn'];
        if (isset($_POST['chkSeekcpl'])) $chkSeekcpl=$_POST['chkSeekcpl'];

        # My match variables
        $lstMySex=$_POST['lstMySex'];
        $txtMyComment=$_POST['txtMyComment'];
        $lstMySmoker=$_POST['lstMySmoker'];
        $txtMyFromAge=$_POST['txtMyFromAge'];
        $txtMyToAge=$_POST['txtMyToAge'];
        $lstMyMinHeight=$_POST['lstMyMinHeight'];
        $lstMyMaxHeight=$_POST['lstMyMaxHeight'];
        $lstMySeeking=$_POST['lstMySeeking'];
        $txtMyComment=$_POST['txtMyComment'];

        if ($lstCountry == "0") {
                $error_message=PRGADVERTISE_TEXT1;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if (trim($lstCountry) == "") {
                $error_message=PRGADVERTISE_TEXT1;
                error_page($error_message,GENERAL_USER_ERROR);
        }

        if($GEOGRAPHY_JAVASCRIPT || $GEOGRAPHY_AJAX){
            if ($lstCity <= "0") {
                    $error_message=PRGADVERTISE_TEXT2;
                    error_page($error_message,GENERAL_USER_ERROR);
            }
            if (trim($lstCity) == "") {
                    $error_message=PRGADVERTISE_TEXT2;
                    error_page($error_message,GENERAL_USER_ERROR);
            }
        } else {
            if (strlen($txtLocation) < 2 || strlen($txtLocation) > 30) {
                    $error_message=PRGADVERTISE_TEXT2;
                    error_page($error_message,GENERAL_USER_ERROR);
            }
        }
        if ($CONST_ZIPCODES=='Y') {
                if (trim($txtZipcode) != "" && strlen($txtZipcode) > 5) {
                        $error_message=PRGADVERTISE_TEXT3;
                        error_page($error_message,GENERAL_USER_ERROR);
                }
                if (trim($txtZipcode) != "") {
                        // Check for valid areacode
                        $sql = "SELECT zip_latitude,zip_longitude FROM zipcodes WHERE zip_zipcode = '$txtZipcode' LIMIT 1";
                        $result=mysql_query($sql,$link);
                        if (mysql_num_rows($result) < 1) {
                                $error_message=PRGADVERTISE_TEXT4;
                                error_page($error_message,GENERAL_USER_ERROR);
                        }
                }
        }
        if ($lstSeeking == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT5;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstBodyType == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT6;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstHeight == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT7;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstChildren == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT8;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstSmoker == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT9;
                error_page($error_message,GENERAL_USER_ERROR);
        }
    if ($lstDrink == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT10;
        error_page($error_message,GENERAL_USER_ERROR);
    }
        if ($lstReligion == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT11;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstMarital == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT12;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstEthnicity == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT13;
                error_page($error_message,GENERAL_USER_ERROR);
        }
    if ($lstEyecolor == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT31;
        error_page($error_message,GENERAL_USER_ERROR);
    }
    if ($lstHaircolor == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT32;
        error_page($error_message,GENERAL_USER_ERROR);
    }
        if ($lstEducation == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT14;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstEmployment == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT15;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ($lstIncome == "- Choose -") {
                $error_message=PRGADVERTISE_TEXT16;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if ((! isset($chkSeekmen)) && (! isset($chkSeekwmn)) && (! isset($chkSeekcpl))) {
                $error_message=PRGADVERTISE_TEXT17;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if (strlen($txtTitle) < 5 || strlen($txtTitle) > 30) {
                $error_message=PRGADVERTISE_TEXT18;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if (strlen($txtComment) < 120) {
                $error_message=PRGADVERTISE_TEXT19;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if (strlen($txtComment) > 4000) {
                $error_message=PRGADVERTISE_TEXT20;
                error_page($error_message,GENERAL_USER_ERROR);
        }
/*
        # Get the root path from params to store the photo
        $root_path=$CONST_INCLUDE_ROOT."/";
        $max_size=$option_manager->GetValue('maxpicsize');
        # Check the picture
        if ($_FILES['fupload']['size'] != 0) {
                if ($_FILES['fupload']['size'] > $max_size) {
                $max_size=$max_size/1000;
                        error_page(sprintf(PRGADVERTISE_TEXT22,$max_size),GENERAL_USER_ERROR);
                }
                if ($_FILES['fupload']['type'] != "image/pjpeg" && $_FILES['fupload']['type'] != "image/jpeg") {
                        error_page(PRGADVERTISE_TEXT23.$_FILES['fupload']['type'],GENERAL_USER_ERROR);
                }
        }
*/
}

# if the validation is successful, this registers the user
# removed 23/04/2002 email is now default to HTML
# if ($rdoEmailType[0] == 'H') { $EmailType='H';} else {$EmailType='T';}
$EmailType='H';
if ($mode=='create') {
        $tempDate=date("Y/m/d");
        $dob='19'.$txtYear.'/'.$lstMonth.'/'.$lstDay;
        if (! isset($chkNews)) {
            $chkNews="0";
        }else{
			$chkNews="1";
		}
        # check for duplicate username
        $query="SELECT * FROM members WHERE mem_username = '$txtHandle'";
        $retval=mysql_query($query,$link) or die(mysql_error());
        $result=mysql_num_rows($retval);
        if ($result > 0) {
                $error_message=PRGREGISTER_TEXT16;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        # check for duplicate email address
		
		//only do this if not "support email"		
		$query="SELECT * FROM members WHERE mem_email = '$txtEmail'";
		$retval=mysql_query($query,$link) or die(mysql_error());
		$result=mysql_num_rows($retval);
		if ($result > 0) {
				$error_message=PRGREGISTER_TEXT17;
				error_page($error_message,GENERAL_USER_ERROR, $mode);
		}
			
        # check who referred and action offers

        $freetime = $option_manager->GetValue('freetime');
        $trial_gender = $option_manager->GetValue('trial_gender');
        if ($freetime != -1 && ($trial_gender == 'B' || $trial_gender == $lstSex))
                $expiredate=mktime (0,0,0,date("m") ,date("d")+$freetime,date("Y"));
        else
                $expiredate=mktime (0,0,0,date("m") ,date("d")-1,date("Y"));

        $expiredate=date('Y/m/d',$expiredate);
        if (isset($referrer)) {
                if ($referrer == '101005') {
                        $expiredate=mktime (0,0,0,date("m") ,date("d")+5,date("Y"));
                        $cleandate=date('d/M/Y',$expiredate);
                        $expiredate=date('Y/m/d',$expiredate);
                        $paragraph=PRGREGISTER_TEXT18;
                }
                $paragraph="$expiredate".PRGREGISTER_TEXT20;
        } else {
                $referrer='';
                $paragraph=PRGREGISTER_TEXT20;
        }
        # insert the new member
		//yoes 08 feb 09, change newsletter to mem_get_marketing
        $query="INSERT INTO members
           (mem_username,
            mem_password,
            mem_expiredate,
            mem_surname,
            mem_forename,
            mem_email,
            mem_joindate,
            mem_sex,
            mem_dob,
            mem_lastvisit,
            mem_get_marketing,
            mem_emailtype,
            mem_update,
            mem_type,
            mem_referrer,
            
			
			
			
			mem_skype,            
			mem_skypeset,			
			
			mem_facebook,
			mem_facebookset,
			
			mem_line,
			mem_lineset,			
			
			
			
			
            mem_ip,
            lang_id,
            mem_confirm)
            VALUES
            ('$txtHandle',
            '$txtPassword',
            '$expiredate',
            '$txtSurname',
            '$txtForename',
            '$txtEmail',
            '$tempDate',
            '$lstSex',
            '$dob',
            '$tempDate',
            '$chkNews',
            '$EmailType',
            0,
            'U',
            '$referrer',
            
			
			
			
			
			'$txtSkypename',
            '$lstSkypeSettings',
			
			'$txtFacebookname',
            '$lstFacebookSettings',
			
			'$txtLinename',
            '$lstLineSettings',
			
			
			
			
			
			
            '$client_ip',
            '".$_SESSION['lang_id']."',
            ".($CONST_EMAIL_CONFIRM == 'Y' ? 0 : 1).")";
			
		mail("joe@atlanticthai.com", "prgresiter line 436 (insert new)", $query);	
			
        if (!mysql_query($query,$link)){
                error_page("Failed ".mysql_error(),GENERAL_SYSTEM_ERROR, $mode);
        } else {
                $mem_id=mysql_insert_id();
                setcookie("NetSingles","$mem_id",0);
                session_cache_limiter('private, must-revalidate');
                session_start();
                $_SESSION['Sess_JustRegistered']=true;
                $_SESSION['Sess_UserType']="U";
                $Sess_UserName = $_SESSION['Sess_UserName']=$txtHandle;
                $Sess_UserId = $_SESSION['Sess_UserId']=$mem_id;
                $_SESSION['Sess_Userlevel']="silver";

                include_once __INCLUDE_CLASS_PATH."/class.Network.php";
                $network = new Network();
                $network->addInvitedUser($Sess_UserId, $txtEmail);

                //###### Save the advertise information ##########
                # checks to see if a member exists and extracts certain info for use in the advert
                $tempDate=date("Y-m-d H:i:s"); // this is used as the create/update date of the advert
                $query="SELECT mem_userid, mem_dob, mem_username, mem_sex, mem_expiredate FROM members WHERE mem_userid = '$Sess_UserId'";
                if (! $result=mysql_query($query,$link)) {
                        error_page(mysql_error(),GENERAL_SYSTEM_ERROR);
                }
                if (mysql_num_rows($result) < 1) {
                        error_page(PRGADVERTISE_TEXT21,GENERAL_SYSTEM_ERROR);
                } else {
                        # extract member information from members table
                        $sql_array=mysql_fetch_object($result);
                        $tempdob=$sql_array->mem_dob;
                        $tempusername=$sql_array->mem_username;
                        $tempsex=$sql_array->mem_sex;
                        $tempid=$sql_array->mem_userid;
                        $tempexpire=$sql_array->mem_expiredate;
                        $tempseekmen='N'; $tempseekwmn='N'; $tempseekcpl='N';
                        if (isset($chkSeekmen)) {$tempseekmen='Y';}
            if (isset($chkSeekwmn)) {$tempseekwmn='Y';}
            if (isset($chkSeekcpl)) {$tempseekcpl='Y';}
                }
                # Upload the picture for the first time if it exists
/*
                if ($_FILES['fupload']['size'] != 0) {
                        if ( $_FILES['fupload']['type'] == "image/pjpeg" ) { $extension=".jpg"; }
                        if ( $_FILES['fupload']['type'] == "image/jpeg" ) { $extension=".jpg"; }
                        $filename="$tempusername"."$extension";
                        $targetfile="members/"."$filename";
                        copy($_FILES['fupload']['tmp_name'],"$targetfile");
                        if ($CONST_THUMBS == 'Y') {
                                $thumbfile=str_replace("members/", "thumbs/", $targetfile);
                                $new_w=60;
                                $new_h=66;
                                createthumb($targetfile, $thumbfile,$new_w,$new_h);
                                $thumbfile=str_replace("members/", "thumbs/large-", $targetfile);
                                $new_w=120;
                                $new_h=160;
                                createthumb($targetfile, $thumbfile,$new_w,$new_h);
                        }
                        $targetfile="/members/"."$filename";
                # if it doesn't exist then put in the generic picture
                } elseif($_POST['avatar']) {
                    $avatar_id = $_POST['avatar'];
                    $res_avatar = mysql_query($query_avat = "SELECT * FROM avatars AS a
                                                                INNER JOIN pictures AS p
                                                                    ON (a.pic_id = p.pic_id)
                                                                WHERE
                                                                    a.avatar_id = $avatar_id
                                                                ");
                    $oAvatar = mysql_fetch_object($res_avatar);
                    $targetfile = $oAvatar->pic_picture;
//                    echo '<pre>';
//                    print_r($targetfile);
//                    exit;
                } else {
                                if ($tempsex=='M') {$targetfile='/images/genericm.gif';}
                                if ($tempsex=='F') {$targetfile='/images/genericf.gif';}
                                if ($tempsex=='C') {$targetfile='/images/genericc.gif';}
                }
*/
                # check whether immediate authorisation
                $approved=$option_manager->GetValue('authorisead');
                # Insert the new advert
                $txtComment=mysql_escape_string($txtComment);
                $txtTitle=mysql_escape_string($txtTitle);
                $expiredate=mktime (0,0,0,date("m") ,date("d")-1,date("Y"));
                $expiredate=date('Y-m-d',$expiredate);
                if ($tempexpire > $expiredate) {
                        $expiredate=$tempexpire;
                }

                $query="INSERT INTO adverts
                        (adv_zipcode, adv_userid, adv_username, adv_smoker, adv_drink, adv_children, adv_dob, adv_comment,  adv_countryid, adv_stateid, adv_cityid, adv_location,adv_height, adv_marital, adv_bodytype, adv_ethnicity, adv_religion, adv_sex, adv_seeking, adv_picture, adv_createdate, adv_seekmen, adv_seekwmn,adv_seekcpl, adv_approved, adv_title, adv_profession,adv_income,adv_education, adv_expiredate, adv_views, adv_eyecolor, adv_haircolor, adv_ip)
                        VALUES
                        ('$txtZipcode', '$tempid','$tempusername','$lstSmoker','$lstDrink','$lstChildren','$tempdob','$txtComment', '$lstCountry','$lstState','$lstCity','$txtLocation', '$lstHeight', '$lstMarital','$lstBodyType','$lstEthnicity','$lstReligion','$tempsex','$lstSeeking', '$targetfile', '$tempDate', '$tempseekmen', '$tempseekwmn','$tempseekcpl', '$approved', '$txtTitle' ,'$lstEmployment','$lstIncome','$lstEducation','$expiredate',0,'$lstEyecolor','$lstHaircolor','$client_ip')";

                if (!mysql_query($query,$link)) {
                        if (mysql_errno($link) == 1062) {
                                $query="UPDATE adverts  SET
                                adv_zipcode='$txtZipcode',
                                adv_title = '$txtTitle',
                                adv_smoker = '$lstSmoker',
                                adv_drink = '$lstDrink',
                                adv_children = '$lstChildren',
                                adv_comment = '$txtComment',
                                adv_countryid = '$lstCountry',
                                adv_stateid = '$lstState',
                                adv_cityid = '$lstCity',
                                adv_location = '$txtLocation',

                                adv_height = '$lstHeight',
                                adv_marital = '$lstMarital',
                                adv_bodytype = '$lstBodyType',
                                                    adv_approved = '$approved',
                                adv_createdate = '$tempDate',
                                adv_ethnicity = '$lstEthnicity',
                                adv_religion = '$lstReligion',
                                adv_education = '$lstEducation',
                                adv_Income = '$lstIncome',
                                adv_profession= '$lstEmployment',
                                adv_seeking = '$lstSeeking',
                                adv_picture = '$targetfile',
                                adv_expiredate = '$expiredate',
                                adv_seekmen = '$tempseekmen',
                                adv_ip = '$client_ip',
                            adv_seekwmn = '$tempseekwmn',
                            adv_seekcpl = '$tempseekcpl',
                            adv_eyecolor = '$lstEyecolor',
                            adv_haircolor = '$lstHaircolor'
                                WHERE adv_userid = '$Sess_UserId'";
                                if (!mysql_query($query,$link)) {error_page(mysql_error(),GENERAL_SYSTEM_ERROR);}

                        }else{
                                error_page(mysql_error(),GENERAL_SYSTEM_ERROR);
                        }
                }

                include("generate_profile.php");

                $txtMyComment=mysql_escape_string($txtMyComment);

                $query="INSERT INTO mymatch (mym_userid, mym_gender, mym_smoker, mym_comment, mym_minheight, mym_maxheight, mym_bodytype, mym_agemin, mym_agemax, mym_relationship)
                                VALUES ($Sess_UserId, '$lstMySex', '$lstMySmoker', '$txtMyComment', '$lstMyMinHeight', '$lstMyMaxHeight', '$lstMyBodyType','$txtMyFromAge','$txtMyToAge', '$lstMySeeking')";
                mysql_query($query,$link) or die(mysql_error());

                mysql_close( $link );

                unset($_SESSION["post"]);

                if($is_speeddating)
                        header("Location: $CONST_LINK_ROOT/prgprofile.php?speeddating=1");
                else
                        header("Location: $CONST_LINK_ROOT/profile.php");
                exit;
        }
		
		
		
} else {

		//Yoes 08 Feb 2009
		//mode == edit
        if ($Sess_UserName == 'manager') {
            restrict_demo();
        }

        include ('session_handler.inc');
        if (! isset($chkNews)) {
            $chkNews="0";
        }else{
			$chkNews="1";
		}
        $dob='19'.$txtYear."/".$lstMonth."/".$lstDay;
        # check for duplicate email address
		if($txtEmail != "emailsupport@atlanticthai.com"){
		
			$query="SELECT * FROM members WHERE mem_email = '$txtEmail' AND mem_userid != '$Sess_UserId'";
			$retval=mysql_query($query,$link) or die(mysql_error());
			$result=mysql_num_rows($retval);
			if ($result > 0) {
					$error_message=PRGREGISTER_TEXT24;
					error_page($error_message,GENERAL_USER_ERROR, $mode);
			}
		}
        # update the member
		//Yoes 08 Feb 2009
		//change mem_newsletter to mem_get_marketing
        $query="UPDATE
                members
                SET mem_password='$txtPassword',
                mem_surname='$txtSurname',
                mem_forename='$txtForename',
                mem_email='$txtEmail',
                mem_ip = '$client_ip',
                mem_sex='$lstSex',
                mem_dob='$dob',
                mem_get_marketing='$chkNews',
                mem_emailtype='$EmailType',
                
				
				mem_skype='$txtSkypename',
                mem_skypeset='$lstSkypeSettings',
				
				mem_facebook='$txtFacebookname',
                mem_facebookset='$lstFacebookSettings',
				mem_line='$txtLinename',
                mem_lineset='$lstLineSettings',				
				
				
                mem_update=0
                where mem_userid = '$Sess_UserId'";
				
		mail("joe@atlanticthai.com", "prgresiter line 436 (update)", $query);	
		
        if (!mysql_query($query,$link)){
                error_page("Failed",GENERAL_SYSTEM_ERROR, $mode);
        }
		
		
		
if(!$is_speeddating){
        # update the advert
        $query="SELECT adv_userid, adv_picture FROM adverts WHERE adv_userid = '$Sess_UserId'";
        $retval=mysql_query($query,$link) or die(mysql_error());
        $result=mysql_num_rows($retval);
        # if the sex has changed and default photo exists then the photo need updating
        if ($result > 0) {
                $sql_array = mysql_fetch_object($retval);
                $query="UPDATE adverts SET adv_dob='$dob', adv_sex='$lstSex' where adv_userid = '$Sess_UserId'";
                if (!mysql_query($query,$link)){
                        error_page("Failed",GENERAL_SYSTEM_ERROR, $mode);
                }
        }
}
}

unset($_SESSION["post"]);

$regtitle=PRGREGISTER_TEXT27;
$regtext=PRGREGISTER_TEXT28;
if($is_speeddating)
    $reglink="<input type=button class=input_button name=ok value='".GENERAL_CONTINUE."' onClick=\"location.href='$CONST_LINK_ROOT/speeddating/home.php'\">";
else
    $reglink="<input type=button class=button name=ok value='".GENERAL_CONTINUE."' onClick=\"location.href='$CONST_LINK_ROOT/myinfo.php'\">";
$paragraph='';
mysql_close($link);
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo REGISTER_SECTION_NAME ?></td>
    </tr>
    <tr>
    <td><b><?php print("$regtitle"); ?></b> <p><?php print("$regtext"); ?></p>
      <p><?php print("$paragraph"); ?></p>
      <?php print("$reglink"); ?></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>