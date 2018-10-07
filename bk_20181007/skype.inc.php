<?php

$premium_test = mysql_query("SELECT * FROM adverts Where adv_expiredate >= '" . date("Y-m-d") . "' AND adv_userid=$sql_array->adv_userid limit 0,1") ;		

if (mysql_num_rows($premium_test) < 1){

	//echo "...";

}else{

	//echo "..";

$option_manager =& OptionManager::GetInstance();

if ($option_manager->getValue('skype')) {

$skype_auth=true;
if ($sql_array->mem_skypeset == "HOTLIST" && isset($Sess_UserId)) {
    $skype_test=mysql_query("SELECT * FROM hotlist WHERE hot_userid=$sql_array->adv_userid AND hot_advid=$Sess_UserId");
    if (mysql_num_rows($skype_test) < 1) $skype_auth=false;
}
if (trim($sql_array->mem_skype) !="" ){

if(isset($Sess_UserId)){
if ($skype_auth && (!$option_manager->getValue('skype_premium') || $Sess_Userlevel == 'gold')) { ?>
    
	<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
    <a href="skype:<?php echo $sql_array->mem_skype ?>?chat" title='Skype Chat- you need to open Skype'><img src="skins/green/images/skype_premium.gif" alt="Skype Chat- you need to open Skype" width="23" height="22" border="0" align=absmiddle /></a>

<?php } elseif ($skype_auth == false) {?>
    
    <img src="<?php echo CONST_IMAGE_ROOT ?>skype_blocked.gif" border=0 title="Favorites only can Skype Chat" align=absmiddle />

<?php } elseif ($option_manager->getValue('skype_premium') && $Sess_Userlevel != 'gold') {?>
    
    <a href="<?=$CONST_LINK_ROOT?>/get_premium.php" title='Skype Chat- you need to open Skype'><img src="skins/green/images/skype_premium.gif" width="23" height="22"  border=0 / align=absmiddle title="Chat and Phone other members with Premium membership on ThaiLoveLines.com"></a>

<?php }}else {?>
	
    <a href="<?=$CONST_LINK_ROOT?>/joinreg.php?userid=<?=$sql_array->adv_userid?>" title='Skype Chat- you need to open Skype'><img src="skins/green/images/skype_premium.gif" width="23" height="22"  border=0 / align="absmiddle"></a>

<? }?>
<? }}}?>